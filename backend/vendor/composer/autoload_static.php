<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5caa9b825bbf923c4e99dba661dc714a
{
    public static $files = array (
        '320cde22f66dd4f5d3fd621d3e88b98f' => __DIR__ . '/..' . '/symfony/polyfill-ctype/bootstrap.php',
        '4cdafd4a5191caf078235e7dd119fdaf' => __DIR__ . '/..' . '/flightphp/core/flight/autoload.php',
        '0ccdf99b8f62f02c52cba55802e0c2e7' => __DIR__ . '/..' . '/zircote/swagger-php/src/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Polyfill\\Ctype\\' => 23,
            'Symfony\\Component\\Yaml\\' => 23,
            'Symfony\\Component\\Finder\\' => 25,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
            'Psr\\Cache\\' => 10,
        ),
        'O' => 
        array (
            'OpenApi\\' => 8,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
        'D' => 
        array (
            'Doctrine\\Deprecations\\' => 22,
            'Doctrine\\Common\\Lexer\\' => 22,
            'Doctrine\\Common\\Annotations\\' => 28,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Polyfill\\Ctype\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-ctype',
        ),
        'Symfony\\Component\\Yaml\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/yaml',
        ),
        'Symfony\\Component\\Finder\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/finder',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'OpenApi\\' => 
        array (
            0 => __DIR__ . '/..' . '/zircote/swagger-php/src',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
        'Doctrine\\Deprecations\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/deprecations/lib/Doctrine/Deprecations',
        ),
        'Doctrine\\Common\\Lexer\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/lexer/src',
        ),
        'Doctrine\\Common\\Annotations\\' => 
        array (
            0 => __DIR__ . '/..' . '/doctrine/annotations/lib/Doctrine/Common/Annotations',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5caa9b825bbf923c4e99dba661dc714a::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5caa9b825bbf923c4e99dba661dc714a::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5caa9b825bbf923c4e99dba661dc714a::$classMap;

        }, null, ClassLoader::class);
    }
}
