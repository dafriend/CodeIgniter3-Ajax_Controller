<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function register_autoloader()
{
    spl_autoload_register('ci_autoloader');
}
/*
 * This piece of code will allow classes that do not start with CI_ 
 * and that are stored in one of the application subdirectories 
 *   'libraries', 
 *   'core', 
 *   'controllers', or 
 *   'models' 
 * to be found and loaded when called for.
 */

function ci_autoloader($class)
{
    $paths = ['libraries/', 'core/', 'controllers/', 'models/'];
    if(strpos($class, 'CI_') !== 0)
    {
        foreach($paths as $path)
        {
            if(file_exists($file = APPPATH.$path.$class.'.php'))
            {
                require_once $file;
                break;
            }
        }
    }
}
