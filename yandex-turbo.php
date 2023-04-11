<?php
namespace Grav\Plugin;

use Grav\Common\Data;
use Grav\Common\Grav;
use Grav\Common\Page\Pages;
use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Page\Collection;
use Grav\Common\Uri;
use RocketTheme\Toolbox\Event\Event;

class YandexTurboPlugin extends Plugin
{
    /** @var array */
    protected $items = [];

    /**
     * Subscribe to required events
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onBlueprintCreated' => ['onBlueprintCreated', 0]
        ];
    }

    /**
     * Enable turbo RSS only if url matches to the configuration.
     *
     * @return void
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        /** @var Uri $uri */
        $uri = $this->grav['uri'];
        $route = $this->config->get('plugins.yandex-turbo.route');

        if ($route && $route == $uri->path()) {
            $this->enable([
                'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
                'onPagesInitialized' => ['onPagesInitialized', 0],
                'onPageInitialized' => ['onPageInitialized', 0],
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
            ]);
        }
    }

    /**
     * Add current directory to twig lookup paths.
     *
     * @return void
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Generate data for the turbo pages.
     *
     * @return void
     */
    public function onPagesInitialized()
    {
        if ($this->config->get('plugins.yandex-turbo.enable_cache')) {
            $cache = Grav::instance()['cache'];
            $cache_id = md5('yandex_turbo_plugin');

            if ($data = $cache->fetch($cache_id)) {
                $this->items = $data;
            }
        }

        if (empty($this->items)) {
            $content = (array) $this->config->get('plugins.yandex-turbo.content');

            if (empty($content)) {
                /** @var Pages $pages */
                $pages = $this->grav['pages'];
                $collection = $pages->all();
            } else {
                $collection = new Collection();
                $page = Grav::instance()['page'];

                foreach ($content as $route) {
                    if ($route === '/') {
                        $collection->append($page->evaluate(['@page.self' => $route, '@page' => $route]));
                    } else {
                        $collection->append($page->evaluate(['@page.self' => $route, '@page.children' => $route]));
                    }
                }
            }

            $by = $this->config->get('plugins.yandex-turbo.sort_by') ?? 'date';
            $dir = $this->config->get('plugins.yandex-turbo.sort_dir') ?? 'desc';
            $options = [$by, $dir];

            if ($by === 'manual' && ! empty($content)) {
                $options = array_merge($options, [$content, SORT_NUMERIC]);
            }

            $collection = $collection->published()->routable()->order(...$options)->slice(0, 1000);

            foreach ($collection as $page) {
                $header = $page->header();

                if (isset($header->external_url) || isset($header->access))
                    continue;

                $entry = new \stdClass();
                $entry->title = $page->title();
                $entry->link = $page->canonical();

                $date = $by == 'modified' ? $page->modified() : $page->date();
                if ($by == 'publish_date' && ! empty($page->publishDate()))
                    $date = $page->publishDate();

                $entry->date = date(DATE_RFC822, $date);

                if (! empty($header->metadata['author'])) {
                    $entry->author = $header->metadata['author'];
                } elseif (! empty($header->author['name'])) {
                    $entry->author = $header->author['name'];
                }

                $entry->media = $page->media()->images();

                if ($this->config->get('plugins.yandex-turbo.sort_by') === 'desc') {
                    $entry->content = ! empty($header->metadata['description']) ? $header->metadata['description'] : $page->summary();
                } else {
                    $entry->content = $page->content();
                }

                $entry->active = ! isset($header->yandex_turbo['active']) || $header->yandex_turbo['active'];

                if (! empty($entry->content))
                    $this->items[$page->route()] = $entry;
            }
        }

        if (! empty($cache_id) && ! empty($cache)) {
            $cache->save($cache_id, $this->items);
        }

        $this->grav->fireEvent('onYandexTurboProcessed', new Event(['turbo' => &$this->items]));
    }

    /**
     * Define twig-template and md-page for turbo RSS
     *
     * @return void
     */
    public function onPageInitialized()
    {
        $page = $event['page'] ?? null;
        $route = $this->config->get('plugins.yandex-turbo.route');

        if (is_null($page) || $page->route() !== $route) {
            $page = new Page;
            $page->init(new \SplFileInfo(__DIR__ . '/pages/turbo.md'));

            unset($this->grav['page']);

            $this->grav['page'] = $page;

            $twig = $this->grav['twig'];
            $twig->template = 'turbo.rss.twig';
        }
    }

    /**
     * Set needed variables to display the turbo RSS.
     *
     * @return void
     */
    public function onTwigSiteVariables()
    {
        $twig = $this->grav['twig'];
        $twig->twig_vars['items'] = $this->items;
    }

    /**
     * Extend page blueprints with feed configuration options.
     *
     * @param Event $event
     */
    public function onBlueprintCreated(Event $event)
    {
        static $inEvent = false;

        /** @var Data\Blueprint $blueprint */
        $blueprint = $event['blueprint'];
        if (! $inEvent && $blueprint->get('form/fields/tabs', null, '/')) {
            if (! in_array($blueprint->getFilename(), array_keys($this->grav['pages']->modularTypes()))) {
                $inEvent = true;
                $blueprints = new Data\Blueprints(__DIR__ . '/blueprints/');
                $extends = $blueprints->get('yandex_turbo');
                $blueprint->extend($extends, true);
                $inEvent = false;
            }
        }
    }
}
