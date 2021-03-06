<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9c9ef494f68e39c499e56e686013fb1f
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'M5\\' => 3,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'M5\\' => 
        array (
            0 => __DIR__ . '/../..' . '/m5',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9c9ef494f68e39c499e56e686013fb1f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9c9ef494f68e39c499e56e686013fb1f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
