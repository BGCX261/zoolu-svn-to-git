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
 * @package    library.massiveart.utilities
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * SeoUrlPlugin
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-20: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 * @package massiveart.utilities
 * @subpackage SeoUrlPlugin
 */
 
class SeoUrlPlugin extends Zend_Controller_Plugin_Abstract{
	
	/**
	 * @var Core
	 */
	protected $core;
	//urlId
	/**
	 * routeStartup
	 * @see library/Zend/Controller/Plugin/Zend_Controller_Plugin_Abstract#routeStartup($request)
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function routeStartup($request){
		/*$this->core = Zend_Registry::get('Core');
		$strUrl = $this->getValidUrl($request->getRequestUri());
		
		require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Folders.php';
		$objModelFolders = new Model_Folders();
		$objUrl = $objModelFolders->getSitetypeByUrl($strUrl);

		if(isset($objUrl->idUrlTypes)) {
			switch($objUrl->idUrlTypes) {
				// Widgets
				case $this->core->sysConfig->url_types->widget: {
					require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Widgets.php';
					$objModelWidgets = new Model_Widgets();
					$objModelFolders->setLanguageId($this->intLanguageId);
					$objTheme = $objModelFolders->getThemeByDomain($_SERVER['SERVER_NAME'])->current();
					$objModelWidgets->setLanguage($this->intLanguageId);
					$objWidgetUrl = $objModelWidgets->loadWidgetByUrl('null', $objUrl->url);
					
					
					//$objWidgetUrl->url;
					$strWidgetArgs = substr($strUrl,strlen($objWidgetUrl->url));
					$objWidget = new Widget();
					$objWidget->setWidgetName($objWidgetUrl->name);
					
					$objWidget->setNavigationUrl($objWidgetUrl->url);
					$objWidget->setWidgetTitle($objWidgetUrl->title);
					$objWidget->setWidgetInstanceId($objWidgetUrl->widgetInstanceId);
					$objWidget->setRootLevelTitle($objTheme->title);
					Zend_Registry::set('Widget', $objWidget);
					
					if(empty($strWidgetArgs)) {
						Zend_Registry::set('Widget', $objWidget);
						$this->getRequest()->setRequestUri('widget/'.$objWidgetUrl->name.'/index/index');
					} else {
						// action must be leading! -> even index Action (only if there are params)
						// e.g. /view/1/, /index/843/
						$strWidgetParams = explode('/',ltrim($strWidgetArgs,'/'));
						$this->getRequest()->setParams($strWidgetParams);						
						$this->getRequest()->setRequestUri('widget/'.$objWidgetUrl->name.'/index'.$strWidgetArgs);
					}
				} break;
				
				// load index controller and display page
				default: break;
			}
    }*/
	}
	
	/**
	 * getValidUrl
	 * @param $strUrl
	 * @return strUrl
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	protected function getValidUrl($strUrl) {
		if(preg_match('/^\/[a-zA-Z]{2}\//', $strUrl)){
      preg_match('/^\/[a-zA-Z]{2}\//', $strUrl, $arrMatches);
      $this->strLanguageCode = trim($arrMatches[0], '/');
      foreach($this->core->webConfig->languages->language->toArray() as $arrLanguage){
        if(array_key_exists('code', $arrLanguage) && $arrLanguage['code'] == strtolower($this->strLanguageCode)){
          $this->intLanguageId = $arrLanguage['id'];
          break;
        }
      }

      if($this->intLanguageId == null){
        $this->intLanguageId = $this->core->sysConfig->languages->default->id;
        $this->strLanguageCode = $this->core->sysConfig->languages->default->code;
      }
      $strUrl = preg_replace('/^\/[a-zA-Z]{2}\//', '', $strUrl);
    }else{
      $strUrl = preg_replace('/^\//', '', $strUrl);
      $this->intLanguageId = $this->core->sysConfig->languages->default->id;
      $this->strLanguageCode = $this->core->sysConfig->languages->default->code;
    }
    
    return $strUrl;
	}
}
?>