<?php

    /**
     * @copyright Cutzr, 2013
     * @author Andrey Knupp Vital <andreykvital@gmail.com>
     */

    define('PS', PATH_SEPARATOR);
    define('DS', DIRECTORY_SEPARATOR);
    define('BP', dirname(dirname(realpath(__FILE__))) . DS);

    $path = array_merge(explode(PS, get_include_path()), array(BP . 'src'));
    set_include_path(implode(PS, array_unique($path)));

    spl_autoload_register(function($class) {
        $file = sprintf('%s.php', str_replace('\\', DS, $class));
        if (($classPath = stream_resolve_include_path($file)) !== false) {
            require $classPath;
        }
    });
