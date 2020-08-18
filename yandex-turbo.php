<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use Grav\Common\Grav;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

class YandexTurboPlugin extends Plugin
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
    * Composer autoload.
    *is
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
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
     * Generate data for the turbo pages.
     *
     * @return void
     */
    public function onPagesInitialized()
    {
        $grav = Grav::instance();
        $current_lang = $grav['language']->getLanguage() ?: 'en';

        /** @var Pages $pages */
        $pages = $this->grav['pages'];
        $routes = array_unique(array_slice($pages->routes(), 0, 1000));
        ksort($routes);

        foreach ($routes as $route => $path) {
            $page = $pages->get($path);
            $header = $page->header();
            $external_url = isset($header->external_url);
            $protected_page = isset($header->access);
            $page_ignored = $protected_page || $external_url;

            if ($page->published() && $page->routable() && !$page_ignored) {
                $entry = new \stdClass();
                $entry->title = $page->title();
                $entry->link = $page->canonical();
                $entry->created = $page->date();
                $entry->media = $page->media()->images();
                $entry->content = $header->metadata['description'] ?: strip_tags($page->summary()) ?: $page->metadata()['description']['content'];
                $this->items[$route] = $entry;
            }
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
     * Add current directory to twig lookup paths.
     *
     * @return void
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
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
}
