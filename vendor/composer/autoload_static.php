<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdc64561715b05070135107b49e80c674
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdc64561715b05070135107b49e80c674::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdc64561715b05070135107b49e80c674::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdc64561715b05070135107b49e80c674::$classMap;

        }, null, ClassLoader::class);
    }
}
