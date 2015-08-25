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
 * @package    application.widgets.blog.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * WidgetControllerAction 
 * Class for interacting with Zoolu Widgets
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-10-09: Florian Mathis
 *  
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */

class WidgetControllerAction extends Zend_Controller_Action  {
	
	/**
	 * @var Core
	 */
	protected $core;
	
	/**
   * request object instance
   * @var Zend_Controller_Request_Abstract
   */
  protected $objRequest;
  protected $strLanguageCode;
  protected $intLanguageId;
  
  /**
   * @var Widget
   */
  protected $objWidget;
  
  /**
   * @var Model_Widgets
   */
  protected $objModelWidgets;
  
  /**
   * @var Model_Folders
   */
  protected $objModelFolders;  
  protected $strTemplateFile;
  
  /**
   * Render with Masterpage or not?
   * @var boolean
   */
  protected $blnRenderMaster = true;
  
  /**
   * @var Object objTheme
   */
  protected $objTheme;
  protected $strWidgetArgs;
  protected $strWidgetParams;
  
  /**
   * @var Object
   */
  static $objWidgetCss = array();
  static $objWidgetJs = array();
  static $objWidgetLink = array();
  
	/**
   * Add Filter Zoolu_PageReplacer and get language prefix from url string,
   * assign url to Zend_View and load Widget object by URL.
   * 
   * @return void
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function init(){
    parent::init();
    
    // Add Default FilterPath and PageReplacer Filter
    $this->view->addBasePath('../application/website/default/views','Zend_View');
    $this->view->addFilter('PageReplacer');
    
    $this->objRequest = $this->getRequest();
    $this->core = Zend_Registry::get('Core');
    
    $strUrl = $_SERVER['REQUEST_URI'];
  	$strDomain = $_SERVER['SERVER_NAME'];
  	  	
  	/**
  	 * Save language-prefix and remove it from the Url
  	 */
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
 
    $this->view->assign('strWidgetUrl', $strUrl);
    
    // Get the theme for this domain
    $this->getModelFolders();
    $this->objTheme = $this->objModelFolders->getThemeByDomain($strDomain)->current();
    $this->objWidget = Zend_Registry::get('Widget');
  }
  
  /**
   * Post-dispatch routines. Called _before_ widget action method
   * and is used to set widget specific information. This method also
   * reset the ScriptPath with current theme path.
   * 
   * @return void
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function postDispatch(){  
    
    //FIXME: Front- and Backend-Options? Cache?
  	$objNavigation = new Navigation();
    $objNavigation->setRootLevelId($this->objTheme->idRootLevels);
    $objNavigation->setLanguageId($this->intLanguageId);
    
    require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_website.'default/helpers/navigation.inc.php';
    Zend_Registry::set('Navigation', $objNavigation);
    
    $this->objWidget->setTemplateFile($this->strTemplateFile);

    /**
     * set values for replacers
     */
    Zend_Registry::set('WidgetCss', $this->getThemeCssFiles());
    Zend_Registry::set('WidgetJs', $this->getThemeJsFiles());
    Zend_Registry::set('WidgetLink', $this->getThemeLinks());
    Zend_Registry::set('Widget', $this->objWidget);
    
  	require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_website.'default/helpers/widget.inc.php';
  	$this->loadWidgetHelpers();
  	
  	$this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/');
    if($this->blnRenderMaster){
    	$this->renderScript('master.php');
    }
    else{
    	$this->renderScript('empty.php');
    }
  }
  
	/**
	 * addThemeLink
	 * @param string strPath
	 * @param string strMedia
	 * @return array objWidgetLink
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function addThemeLink($rel='', $type='', $title='', $href=''){
		if(isset($href) && isset($rel) && isset($type)) { 
			$this->objWidgetLink[] = array('rel' => $rel, 'type' => $type, 'title' => $title, 'href' => $href); 
			return $this->objWidgetLink;
		}
	}
	
	/**
	 * getThemeLinks
	 * Returns all given links for html header
	 * @return strOutput
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function getThemeLinks() {
		if(isset($this->objWidgetLink) && count($this->objWidgetLink) > 0) {
			$strOutput='';
			$strCssPath='';	
			
			// loop array for each css media type
			foreach ($this->objWidgetLink AS $option) {
				$strOutput .= '<link rel="'.$option['rel'].'" type="'.$option['type'].'" title="'.$option['title'].'" href="'.$option['href'].'">';
			}
			
			return $strOutput;
		}
	}
  
	/**
	 * addThemeCss
	 * @param string strPath
	 * @param string strMedia
	 * @return array objWidgetCss
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function addThemeCss($strPath = NULL, $strMedia = 'all'){
		if(isset($strPath)) { 
			$strWidgetPath = '/widgets/'.Zend_Registry::get('Widget')->getWidgetName().'/css/'.$strPath.'.css';
			$this->objWidgetCss[$strMedia][] = $strWidgetPath; 
			return $this->objWidgetCss;
		}
	}
	
	/**
	 * getThemeCssFiles
	 * Returns the CSS Stylesheet link with minify
	 * @return strOutput
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function getThemeCssFiles() {
		if(isset($this->objWidgetCss) && count($this->objWidgetCss) > 0) {
			$strOutput='';
			$strCssPath='';	
			
			// loop array for each css media type
			foreach ($this->objWidgetCss AS $media => $files) {
				foreach($files AS $path) {
					if(file_exists(GLOBAL_ROOT_PATH.'public'.$path)) {
						$strCssPath .= $path.',';
					}
				}
				$strOutput .= "  <link type=\"text/css\" rel=\"stylesheet\" media=\"".$media."\" href=\"".substr($strCssPath,0,-1)."\" />\n";
				$strCssPath='';
			}
			
			return $strOutput;
		}
	}
	
	/**
	 * addThemeJs
	 * @param string strPath
	 * @return array objWidgetCss
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function addThemeJs($strPath = NULL){
		if(isset($strPath)) { 
			$strWidgetPath = '/widgets/'.Zend_Registry::get('Widget')->getWidgetName().'/js/'.$strPath.'.js';
			$this->objWidgetJs[] = $strWidgetPath; 
			return $this->objWidgetJs;
		}
	}
	
	/**
	 * getThemeJsFiles
	 * Returns the javascript link with minify
	 * @return strOutput
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function getThemeJsFiles() {
		if(isset($this->objWidgetJs) && count($this->objWidgetJs) > 0) {
			$strOutput='';
			$strJsPath='';	
			
			// loop array for each script
			foreach($this->objWidgetJs AS $path) {
				if(file_exists(GLOBAL_ROOT_PATH.'public'.$path)) {
					$strJsPath .= $path.',';
				}
			}
			
			$strOutput .= "  <script type=\"text/javascript\" src=\"".substr($strJsPath,0,-1)."\"></script>\n";

			return $strOutput;
		}
	}
  
	/**
   * getWidgetObject
   * @return Zend_Registry object 
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getWidgetObject(){
  	return Zend_Registry::get('Widget');
  }
  
  /**
   * Load all Helpers for zoolu widget.
   * @return void
   * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
   */
  public function loadWidgetHelpers(){
  	$strWidgetHelperPath = GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.Zend_Registry::get('Widget')->getWidgetName().'/views/helpers/';
		$handle = opendir($strWidgetHelperPath);
		
		while ($file = readdir ($handle)) {
			if($file != "." && $file != "..") {
		  	if(!is_dir($strWidgetHelperPath."/".$file)) {
		    	$compl = $strWidgetHelperPath."/".$file;
		      $file_info=pathinfo($compl);
		      if($file_info["extension"] == 'php') {
		      	require_once $compl;
		      }
		    }
			}
		}
		
		closedir($handle);
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
   * @author Florian Mathis <flo@massiveart.com>
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