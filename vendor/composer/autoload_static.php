<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8bb654bf2ac48d1271a66447134df756
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

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8bb654bf2ac48d1271a66447134df756::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8bb654bf2ac48d1271a66447134df756::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}