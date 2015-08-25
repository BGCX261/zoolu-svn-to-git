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
 * @package    application.website.default.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * IndexController
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-04-15: Cornelius Hansjakob

 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class IndexController extends Zend_Controller_Action {

  /**
   * @var Core
   */
  private $core;

  /**
   * @var Model_Pages
   */
  private $objModelPages;

  /**
   * @var Model_Widgets
   */
  private $objModelWidgets;
  
  /**
   * @var Model_Folders
   */
  private $objModelFolders;

  /**
   * @var Model_Urls
   */
  private $objModelUrls;
  
  /**
   * @var Zend_Cache_Frontend_Output
   */
  private $objCache;

  /**
   * @var Zend_Db_Table_Row_Abstract
   */
  private $objUrlsData;
  
  /**
   * @var Page
   */
  private $objPage;
  private $objWidget;
  private $blnCachingStart = false;
  private $intLanguageId;
  private $strLanguageCode;

  /**
   * init index controller and get core obj
   */
  public function init(){
    $this->core = Zend_Registry::get('Core');
  }

  /**
   * postDispatch
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function postDispatch(){

    if(isset($this->objCache) && $this->objCache instanceof Zend_Cache_Frontend_Output){
      if($this->blnCachingStart === true){
        $response = $this->getResponse()->getBody();
        $this->getResponse()->setBody(str_replace("<head>", "<head>
  <!-- This is a ZOOLU cached page (".date('d.m.Y H:i:s').") -->", $response));
        $this->getResponse()->outputBody();

        $arrTags = array();

        if($this->objPage->getIsStartElement(false) == true)
          $arrTags[] = 'StartPage';

        $arrTags[] = 'PageType'.$this->objPage->getPageTypeId();

        $this->core->logger->debug($arrTags);
        $this->objCache->end($arrTags);
        exit();
      }
    }
    parent::postDispatch();
  }

  /**
	 * indexAction
	 * @author Cornelius Hansjakob <cha@massiveart.com>
	 * @version 1.0
	 */
  public function indexAction(){
    $this->view->addFilter('PageReplacer');

    /**
     * get domain
     */
    $strDomain = $_SERVER['SERVER_NAME'];

    /**
     * get uri
     */
    $strUrl = $_SERVER['REQUEST_URI'];

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

    $this->getModelUrls();
    $this->getModelFolders();
    $objTheme = $this->objModelFolders->getThemeByDomain($strDomain)->current();

    $this->objUrlsData = $this->objModelUrls->loadByUrl($objTheme->idRootLevels, (parse_url($strUrl, PHP_URL_PATH) === null) ? '' : parse_url($strUrl, PHP_URL_PATH));

    if(count($this->objUrlsData) > 0){
    	$objUrlData = $this->objUrlsData->current();

      switch($objUrlData->idUrlTypes) {
      	
      	// UrlType: Widget
      	case $this->core->sysConfig->url_types->widget: {
      		$objWidget = new Widget();
       		$this->getModelWidgets();

       		$objWidgetInstance = $this->objModelWidgets->loadWidgetByInstanceId($objUrlData->relationId);
       		$objWidget->setWidgetInstanceId($objUrlData->relationId);
       		($this->_getParam('do') ==  null) ? $objWidget->setAction('index') : $objWidget->setAction($this->_getParam('do'));

					$objWidget->setWidgetName($objWidgetInstance->name);
					$objWidget->setNavigationUrl((parse_url($strUrl, PHP_URL_PATH) === null) ? '' : parse_url($strUrl, PHP_URL_PATH));
					$objWidget->setWidgetTitle($objWidgetInstance->title);
					$objWidget->setRootLevelTitle($objTheme->title);
					$objWidget->setRootLevelId($objTheme->idRootLevels);
					$objWidget->setWidgetVersion($objUrlData->version);
					$objWidget->setLanguageId($objUrlData->idLanguages);
					$objWidget->setLanguageCode($this->strLanguageCode);
					$objWidget->setUrlParentId($objUrlData->idParent);
					
					Zend_Registry::set('Widget', $objWidget);
					$this->_forward($objWidget->getAction(),'index',$objWidgetInstance->name);
      	} break;
      	
      	// UrlType: SubWidget
      	case $this->core->sysConfig->url_types->subwidget: {
      		$objWidget = new Widget();
       		$this->getModelWidgets();
       		
       		$objWidgetInstance = $this->objModelWidgets->loadSubWidgetByInstanceId($objUrlData->relationId);
					$objWidget->setWidgetInstanceId($objUrlData->relationId);
       		($this->_getParam('do') == null) ? $objWidget->setAction('view') : $objWidget->setAction($this->_getParam('do'));

       		$objWidget->setGenericFormTypesId($objWidgetInstance->idGenericFormTypes);
       		$objWidget->setGenericFormId($objWidgetInstance->genericFormId);
       		$objWidget->setGenericFormVersion($objWidgetInstance->version);
       		$objWidget->setWidgetName($objWidgetInstance->name);
					$objWidget->setNavigationUrl((parse_url($strUrl, PHP_URL_PATH) === null) ? '' : parse_url($strUrl, PHP_URL_PATH));
					$objWidget->setWidgetTitle($objWidgetInstance->title);
					$objWidget->setRootLevelTitle($objTheme->title);
					$objWidget->setRootLevelId($objTheme->idRootLevels);
					$objWidget->setWidgetVersion($objUrlData->version);
					$objWidget->setLanguageId($objUrlData->idLanguages);
					$objWidget->setLanguageCode($this->strLanguageCode);
					
					Zend_Registry::set('Widget', $objWidget);
					$this->_forward($objWidget->getAction(),'index',$objWidgetInstance->name);
      	} break;
      	
				// Url Type: page
			  case $this->core->sysConfig->url_types->page: {    
		    
		  		$arrFrontendOptions = array(
			      'lifetime' => 604800, // cache lifetime (in seconds), if set to null, the cache is valid forever.
			      'automatic_serialization' => true
			    );
			
			    $arrBackendOptions = array(
			      'cache_dir' => GLOBAL_ROOT_PATH.'tmp/cache/pages/' // Directory where to put the cache files
			    );
			
			    // getting a Zend_Cache_Core object
			    $this->objCache = Zend_Cache::factory('Output',
			                                          'File',
			                                          $arrFrontendOptions,
			                                          $arrBackendOptions);
			
			    $strCacheId = 'page_'.$this->strLanguageCode.'_'.preg_replace('/[^a-zA-Z0-9_]/', '_', $strUrl);
			    if($this->core->sysConfig->cache->page == 'false' ||
			       ($this->core->sysConfig->cache->page == 'true' && $this->objCache->test($strCacheId) == false) ||
			       ($this->core->sysConfig->cache->page == 'true' && isset($_SESSION['sesTestMode']))){
			
			      $objNavigation = new Navigation();
			      $objNavigation->setRootLevelId($objTheme->idRootLevels);
			      $objNavigation->setLanguageId($this->intLanguageId);
			
			      require_once(dirname(__FILE__).'/../helpers/navigation.inc.php');
			      Zend_Registry::set('Navigation', $objNavigation);
			
			      $this->getModelPages();
			      $this->objUrlsData = $this->objModelPages->loadByUrl($objTheme->idRootLevels, $strUrl);
			      
			      foreach($this->objUrlsData as $objPageData){
			        $this->objUrlsData = $objPageData;
			      }
			      	
			      if(count($this->objUrlsData) > 0){
			
			        $this->core->logger->debug('Cache: '.$this->core->sysConfig->cache->page);
			        if($this->core->sysConfig->cache->page == 'true' && !isset($_SESSION['sesTestMode'])){
			          $this->core->logger->debug('Start caching...');
			          $this->objCache->start($strCacheId);
			          $this->blnCachingStart = true;
			        }
			        
			        $this->objPage = new Page();
			        $this->objPage->setRootLevelId($objTheme->idRootLevels);
			        $this->objPage->setRootLevelTitle($objTheme->title);
			        $this->objPage->setPageId($this->objUrlsData->relationId);
			        $this->objPage->setPageVersion($this->objUrlsData->version);
			        $this->objPage->setLanguageId($this->objUrlsData->idLanguages);
			
			        $this->objPage->load();
			        
			        /**
			         * set values for replacers
			         */
			        Zend_Registry::set('TemplateCss', ($this->objPage->getTemplateId() == $this->core->sysConfig->page_types->page->portal_startpage_templateId) ? '<link rel="stylesheet" type="text/css" media="screen" href="/website/themes/'.$objTheme->path.'/css/startpage.css"></link>' : '<link rel="stylesheet" type="text/css" media="screen" href="/website/themes/'.$objTheme->path.'/css/content.css"></link>');
			        Zend_Registry::set('TemplateJs', ($this->objPage->getTemplateId() == $this->core->sysConfig->page_types->page->event_templateId) ? '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$this->core->webConfig->gmaps->key.'" type="text/javascript"></script>' : '');
			
			        $objNavigation->setPage($this->objPage);
			
			        /**
			         * get page template filename
			         */
			        $this->view->template = $this->objPage->getTemplateFile();
			
			        $this->view->intRootLevelId = $this->objPage->getRootLevelId();
			        //$this->view->strRootLevelUrl = $this->core->sysConfig->url->base;
			        $this->view->publisher = $this->objPage->getPublisherName();
			        $this->view->publishdate = $this->objPage->getPublishDate();
			
			        Zend_Registry::set('Page', $this->objPage);
			        require_once(dirname(__FILE__).'/../helpers/page.inc.php');
			        
			        $this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$objTheme->path.'/');
			        $this->renderScript('master.php');
			
			      }else{
			      	$this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$objTheme->path.'/');
			        $this->renderScript('error-404.php');
			      }
			    }else{
			      $this->_helper->viewRenderer->setNoRender();
			      echo $this->objCache->load($strCacheId);
			    }
	    // end page type
    	}
  	}
		//end if type
    } else {
    	$objNavigation = new Navigation();
      $objNavigation->setRootLevelId($objTheme->idRootLevels);
      $objNavigation->setLanguageId($this->intLanguageId);
      require_once(dirname(__FILE__).'/../helpers/navigation.inc.php');
      Zend_Registry::set('Navigation', $objNavigation);
    	
    	$this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$objTheme->path.'/');
      $this->renderScript('error-404.php');
    }
  }

  /**
   * getModelPages
   * @return Model_Pages
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelPages(){
    if (null === $this->objModelPages) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Pages.php';
      $this->objModelPages = new Model_Pages();
      $this->objModelPages->setLanguageId($this->intLanguageId);
    }

    return $this->objModelPages;
  }
  
	/**
   * getModelUrls
   * @return Model_Urls
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelUrls(){
    if (null === $this->objModelUrls) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Urls.php';
      $this->objModelUrls = new Model_Urls();
      $this->objModelUrls->setLanguageId($this->intLanguageId);
    }

    return $this->objModelUrls;
  }

  /**
   * getModelFolders
   * @return Model_Folders
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelFolders(){
    if (null === $this->objModelFolders) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Folders.php';
      $this->objModelFolders = new Model_Folders();
      $this->objModelFolders->setLanguageId($this->intLanguageId);
    }

    return $this->objModelFolders;
  }
  
	/**
   * getModelWidgets
   * @return Model_Widgets
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelWidgets(){
    if (null === $this->objModelWidgets) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Widgets.php';
      $this->objModelWidgets = new Model_Widgets();
      $this->objModelWidgets->setLanguageId($this->intLanguageId);
    }

    return $this->objModelWidgets;
  }
}
?>