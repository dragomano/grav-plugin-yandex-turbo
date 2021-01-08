# Yandex Turbo Plugin

The **Yandex Turbo** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav) that generates a RSS feed of your blog in accordance with the [Yandex.Turbo](https://tech.yandex.com/turbo/) technology. The `yandex-turbo` plugin works out of the box. You can just go directly to `https://yoursite.com/turbo` and you will see the generated `RSS`.

Плагин **Yandex Turbo** при обращении к адресу `https://yoursite.com/turbo` формирует специальную RSS-ленту, которую можно добавить в качестве источника [Турбо-страниц](https://yandex.ru/dev/turbo/) в [Вебмастере](https://webmaster.yandex.ru/).

## Installation | Установка

Installing the Yandex Turbo plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

Установка плагина Yandex Turbo может быть выполнена одним из трех способов: через GPM (менеджер пакетов Grav), с помощью zip-файла, с помощью плагина админки.

### GPM Installation (Preferred) | Установка через GPM

To install the plugin via the [GPM](https://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

В консоли перейдите в корень установленного сайта и введите команду:

    bin/gpm install yandex-turbo

This will install the Yandex Turbo plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/yandex-turbo`.

### Manual Installation | Ручная установка

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `yandex-turbo`. You can find these files on [GitHub](https://github.com/dragomano/grav-plugin-yandex-turbo) or via [GetGrav.org](https://getgrav.org/downloads/plugins#extras).

При ручной установке просто распакуйте содержимое zip-архива в директорию `/your/site/grav/user/plugins`.

You should now have all the plugin files under

    /your/site/grav/user/plugins/yandex-turbo

### Admin Plugin | Плагин админки

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

При использовании плагина админки данный плагин можно установить в разделе `Плагины`, нажав на кнопку `Добавить`.

## Configuration | Конфигурация

Before configuring this plugin, you should copy the `user/plugins/yandex-turbo/yandex-turbo.yaml` to `user/config/plugins/yandex-turbo.yaml` and only edit that copy.

Прежде чем изменять настройки плагина, скопируйте файл `user/plugins/yandex-turbo/yandex-turbo.yaml` в `user/config/plugins/yandex-turbo.yaml` и редактируйте последний.

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

Note that if you use the Admin Plugin, a file with your configuration named yandex-turbo.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

При сохранении настроек через плагин админки нужный файл с настройками плагина будет создан автоматически.

## How to remove a specific page from the feed | Как убрать конкретную страницу из ленты

Add the following lines to the header of the file (the frontmatter):

Добавьте в шапку файла строчки:

```yaml
yandex_turbo:
    active: false
```
