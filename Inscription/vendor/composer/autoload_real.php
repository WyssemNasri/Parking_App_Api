<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitdde5885e3723f5b88fe3c893fa5ffefc
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitdde5885e3723f5b88fe3c893fa5ffefc', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitdde5885e3723f5b88fe3c893fa5ffefc', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitdde5885e3723f5b88fe3c893fa5ffefc::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
