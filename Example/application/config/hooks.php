<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
/*
 * IMPORTANT: Be sure to set $config['enable_hooks'] = TRUE; 
 * in application/config/config.php or, 
 * if you're using an ENVIRONMENT config folder in application/config/{ENVIRONMENT}/config.php
 */

$hook['pre_system'][] = array(
  'class' => '',
  'function' => 'register_autoloader',
  'filename' => 'Auto_load.php',
  'filepath' => 'hooks'
);

