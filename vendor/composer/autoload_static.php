<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite73e532505e0cb6a4af593444bdb96cf
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Grav\\Plugin\\YandexTurboPlugin' => __DIR__ . '/../..' . '/yandex-turbo.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInite73e532505e0cb6a4af593444bdb96cf::$classMap;

        }, null, ClassLoader::class);
    }
}
