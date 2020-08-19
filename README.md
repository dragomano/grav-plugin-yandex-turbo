# Yandex Turbo Plugin

The **Yandex Turbo** Plugin is an extension for [Grav CMS](https://github.com/getgrav/grav) that generates a RSS feed of your blog in accordance with the [Yandex.Turbo](https://tech.yandex.com/turbo/) technology.

## Installation

Installing the Yandex Turbo plugin can be done in one of three ways: The GPM (Grav Package Manager) installation method lets you quickly install the plugin with a simple terminal command, the manual method lets you do so via a zip file, and the admin method lets you do so via the Admin Plugin.

### GPM Installation (Preferred)

To install the plugin via the [GPM](https://learn.getgrav.org/advanced/grav-gpm), through your system's terminal (also called the command line), navigate to the root of your Grav-installation, and enter:

    bin/gpm install yandex-turbo

This will install the Yandex Turbo plugin into your `/user/plugins`-directory within Grav. Its files can be found under `/your/site/grav/user/plugins/yandex-turbo`.

### Manual Installation

To install the plugin manually, download the zip-version of this repository and unzip it under `/your/site/grav/user/plugins`. Then rename the folder to `yandex-turbo`. You can find these files on [GitHub](https://github.com/dragomano/grav-plugin-yandex-turbo) or via [GetGrav.org](https://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/yandex-turbo

> NOTE: This plugin is a modular component for Grav which may require other plugins to operate, please see its [blueprints.yaml-file on GitHub](https://github.com/dragomano/grav-plugin-yandex-turbo/blob/master/blueprints.yaml).

### Admin Plugin

If you use the Admin Plugin, you can install the plugin directly by browsing the `Plugins`-menu and clicking on the `Add` button.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/yandex-turbo/yandex-turbo.yaml` to `user/config/plugins/yandex-turbo.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
route: '/turbo'
```

Note that if you use the Admin Plugin, a file with your configuration named yandex-turbo.yaml will be saved in the `user/config/plugins/`-folder once the configuration is saved in the Admin.

## Usage

The `yandex-turbo` plugin works out of the box. You can just go directly to `https://yoursite.com/turbo` and you will see the generated `RSS`.
