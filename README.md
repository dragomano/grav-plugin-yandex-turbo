# Yandex Turbo Plugin

The **Yandex Turbo** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav) that generates a RSS feed of your blog in accordance with the [Yandex.Turbo](https://tech.yandex.com/turbo/) technology. The `yandex-turbo` plugin works out of the box. You can just go directly to `https://yoursite.com/turbo` and you will see the generated `RSS`.

## Installation

Installing the Custom Translate Widget plugin can be done in [one of three ways](https://learn.getgrav.org/17/plugins/plugin-install):

* via GPM (Grav Package Manager): `bin/gpm install yandex-turbo`
* via a zip file: extract the package into `/your/site/grav/user/plugins` and rename to `yandex-turbo`
* via the [Admin Plugin](https://github.com/getgrav/grav-plugin-admin).

## Configuration

Note that if you use the Admin Plugin, a file with your configuration named `yandex-turbo.yaml` will be saved in the `user/config/plugins/` folder once the configuration is saved in the Admin.

In manual mode, before configuring this plugin, you should copy the `user/plugins/yandex-turbo/yandex-turbo.yaml` to `user/config/plugins/yandex-turbo.yaml` and only edit that copy.

```yaml
enabled: true
route: '/turbo'
feed_title: null
feed_description: null
max_content_length: 150
content: null
content_source: desc
show_author: true
show_pubdate: true
sort_by: date
sort_dir: desc
image_effects:
  resize: false
  cropResize: true
  grayscale: false
image_width: 300
image_height: 200
enable_cache: false
```

## How to remove a specific page from the feed

Add the following lines to the header of the page file:

```yaml
yandex_turbo:
    active: false
```

---

Плагин **Yandex Turbo** при обращении к адресу `https://yoursite.com/turbo` формирует специальную RSS-ленту, которую можно добавить в качестве источника [Турбо-страниц](https://yandex.ru/dev/turbo/) в [Вебмастере](https://webmaster.yandex.ru/).

## Установка

Установка плагина Custom Translate Widget может быть выполнена [одним из трех способов](https://grav-docs.ru/plugins/plugin-install/):

* через GPM (менеджер пакетов Grav): `bin/gpm install yandex-turbo`
* с помощью zip-файла: извлеките содержимое архива в `/your/site/grav/user/plugins`, затем переименуйте в `yandex-turbo`
* с помощью [плагина админки](https://github.com/getgrav/grav-plugin-admin).

## Конфигурация

При сохранении настроек через плагин админки нужный файл с настройками плагина будет создан автоматически.

Если же админкой вы не пользуетесь, скопируйте файл `user/plugins/yandex-turbo/yandex-turbo.yaml` в `user/config/plugins/yandex-turbo.yaml` и редактируйте последний.

```yaml
enabled: true
route: '/turbo'
feed_title: null
feed_description: null
max_content_length: 150
content: null
content_source: desc
show_author: true
show_pubdate: true
sort_by: date
sort_dir: desc
image_effects:
  resize: false
  cropResize: true
  grayscale: false
image_width: 300
image_height: 200
enable_cache: false
```

## Как убрать конкретную страницу из ленты

Добавьте в шапку файла страницы строчки:

```yaml
yandex_turbo:
    active: false
```
