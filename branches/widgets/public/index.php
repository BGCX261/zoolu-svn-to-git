<?php
/**
 * ZOOLU - Content Management System
 * Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 *
 * LICENSE
 *
 * This file is part of ZOOLU.
 *
 * ZOOLU is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ZOOLU is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ZOOLU. If not, see http://www.gnu.org/licenses/gpl-3.0.html.
 *
 * For further information visit our website www.getzoolu.org 
 * or contact us at zoolu@getzoolu.org
 *
 * @category   ZOOLU
 * @package    application.zoolu.modules.core.models
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * index.php
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

/**
 * include general (autoloader, config)
 */
require_once(dirname(__FILE__).'/../sys_config/general.inc.php');

/**
 * Get the front controller instance
 */
$front = Zend_Controller_Front::getInstance();
$front->setControllerDirectory('../application/website/default/controllers');
$front->addControllerDirectory('../application/zoolu/modules/core/controllers', 'zoolu');
$front->addModuleDirectory('../application/widgets');                
$front->addModuleDirectory('../application/zoolu/modules');
$front->registerPlugin(new SeoUrlPlugin);

/**
 * add helper path
 */
$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer(new Zend_View());

/**
 * Add the own plugin loader
 */
$objLoader = new PluginLoader();
$objLoader->setPluginLoader($viewRenderer->view->getPluginLoader(PluginLoader::TYPE_FORM_HELPER));
$objLoader->setPluginType(PluginLoader::TYPE_FORM_HELPER);
$viewRenderer->view->setPluginLoader($objLoader, PluginLoader::TYPE_FORM_HELPER);

//$viewRenderer->view->addHelperPath('../library/massiveart/generic/forms/helpers', 'Form_Helper');
Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

/**
 * if log priority is DEBUG and firebug logging is true add firebug writer to logger
 * (needs an instance of Zend_Controller_Front)
 */
if($sysConfig->logger->priority == Zend_Log::DEBUG && $sysConfig->logger->firebug == 'true'){
  $writerFireBug = new Zend_Log_Writer_Firebug();
  $core->logger->addWriter($writerFireBug);
}

/**
 * Routing for modules (cms, blog, ...)
 */
$router = $front->getRouter();

/**
 * default website routing regex
 */
$route = new Zend_Controller_Router_Route_Regex('(?!(^zoolu))(.*)', array('controller' => 'Index',
                                                                          'action'     => 'index'));
$router->addRoute('index', $route);

/**
 * Widget routings
 */
$route = new Zend_Controller_Router_Route('widget/:module');
$router->addRoute('widget', $route);

$route = new Zend_Controller_Router_Route('widget/:module/:controller');
$router->addRoute('widgetController', $route);

$route = new Zend_Controller_Router_Route('widget/:module/:controller/:action/*');
$router->addRoute('widgetControllerAction', $route);

/**
 * default zoolu routings
 */
$route = new Zend_Controller_Router_Route('zoolu/:module');
$router->addRoute('cms', $route);

$route = new Zend_Controller_Router_Route('zoolu/:module/:controller');
$router->addRoute('cmsController', $route);

$route = new Zend_Controller_Router_Route('zoolu/:module/:controller/:action/*');
$router->addRoute('cmsControllerAction', $route);

/**
 * default zoolu-website routings
 */
$route = new Zend_Controller_Router_Route('zoolu-website/:controller');
$router->addRoute('webController', $route);

$route = new Zend_Controller_Router_Route('zoolu-website/:controller/:action/*');
$router->addRoute('webControllerAction', $route);

/**
 * only throw exceptions in developement mode
 */
if($sysConfig->show_errors === 'false'){
  $front->throwExceptions(false);
} else {
  $front->throwExceptions(true);
}
        
/** 
 * *** to debug ***
 * echo "<pre>";
 * print_r($_SESSION);
 * echo "</pre>";
 */

/*
$arrFrontendOptions = array(
   'lifetime' => null, // cache lifetime (in seconds), if set to null, the cache is valid forever.
   'default_options' => array(
            'cache_with_get_variables' => false,
            'cache_with_post_variables' => false,
            'cache_with_session_variables' => true,
            'cache_with_files_variables' => false,
            'cache_with_cookie_variables' => true,
            'make_id_with_get_variables' => true,
            'make_id_with_post_variables' => true,
            'make_id_with_session_variables' => false,
            'make_id_with_files_variables' => true,
            'make_id_with_cookie_variables' => false,
            'cache' => true,
            'specific_lifetime' => false,
            'tags' => array(),
            'priority' => null
        ),
   'automatic_serialization' => true
);

$arrBackendOptions = array(
    'cache_dir' => GLOBAL_ROOT_PATH.'tmp/cache/pages/' // Directory where to put the cache files
);
  
// getting a Zend_Cache_Core object
$objCache = Zend_Cache::factory('Page',
                                'File',
                                $arrFrontendOptions,
                                $arrBackendOptions);
$objCache->start();
*/

/**
 * Go Go Go!
 */
$front->dispatch();
?>
