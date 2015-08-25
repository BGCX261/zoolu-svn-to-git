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
 * @package    library.massiveart.website
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Navigation
 * 
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-09: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 * @package massiveart.website
 * @subpackage Navigation
 */

require_once(dirname(__FILE__).'/navigation/tree.class.php');
require_once(dirname(__FILE__).'/navigation/item.class.php');

class Navigation {

  /**
   * @var Core
   */
  protected $core;
  
  /**
   * @var Model_Folders
   */
  protected $objModelFolders;
  
  /**
   * @var Page
   */
  protected $objPage;
  /**
   * @return Page
   */
  public function Page(){
    return $this->objPage;
  }
  
  /**
   * @return Widget
   */
  public function Widget(){
  	if(Zend_Registry::isRegistered('Widget')) {
  		return Zend_Registry::get('Widget');
  	}
  }
  
  /**
   * @var Zend_Db_Table_Rowset_Abstract
   */
  protected $objMainNavigation;
  public function MainNavigation(){
    return $this->objMainNavigation;
  }
  
  /**
   * @var NavigationTree
   */
  protected $objSubNavigation;
  public function SubNavigation(){
    return $this->objSubNavigation;
  }
  
  /**
   * @var Zend_Db_Table_Rowset_Abstract
   */
  protected $objParentFolders;
  public function ParentFolders(){
    return $this->objParentFolders;
  }
  
  protected $intRootLevelId;
  protected $intRootFolderId = 0;
  protected $strRootFolderId = '';
  protected $intLanguageId;
  
  /**
   * Constructor
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');   
  }
    
  /**
   * loadMainNavigation
   * @author Thomas Schedler <tsh@massiveart.com>   
   * @version 1.0
   */
  public function loadMainNavigation(){
    try{
      $this->getModelFolders();
      
      $this->evaluateRootFolderId();
      
      $this->objMainNavigation = $this->objModelFolders->loadWebsiteRootNavigation($this->intRootLevelId);
      
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * loadNavigation
   * @param integer $intDepth
   * @author Cornelius Hansjakob <cha@massiveart.com>   
   * @version 1.0
   */
  public function loadNavigation($intDepth = 1){
    try{
      $this->getModelFolders();
      
      $this->evaluateRootFolderId();
            
      $objNavigationTree = new NavigationTree();
      $objNavigationTree->setId(0);
      
      if($this->intRootLevelId > 0){
        $objNavigationData = $this->objModelFolders->loadWebsiteRootLevelChilds($this->intRootLevelId, $intDepth);
        
        $intTreeId = 0;
        foreach($objNavigationData as $objNavigationItem){

	        if($objNavigationItem->isStartPage == 1 && $objNavigationItem->depth == 0){
            
	         /**
            * add to parent tree
            */   
            if(isset($objTree) && is_object($objTree) && $objTree instanceof NavigationTree){
              $objNavigationTree->addToParentTree($objTree, 'tree_'.$objTree->getId());
            }
	        	
	        	$objTree = new NavigationTree();
            $objTree->setTitle($objNavigationItem->folderTitle);              
            $objTree->setId($objNavigationItem->idFolder);
            $objTree->setParentId(0);
            $objTree->setItemId($objNavigationItem->folderId);
            $objTree->setUrl(($objNavigationItem->idPageTypes == $this->core->sysConfig->page_types->external->id) ? $objNavigationItem->external : '/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url);
              
            $intTreeId = $objNavigationItem->idFolder;
	        	
	        }else{
	        	if($intTreeId != $objNavigationItem->idFolder){

	        	  /**
               * add to parent tree
               */   
              if(isset($objTree) && is_object($objTree) && $objTree instanceof NavigationTree){
                $objNavigationTree->addToParentTree($objTree, 'tree_'.$objTree->getId());
              }
              
              $objTree = new NavigationTree();
              $objTree->setTitle($objNavigationItem->folderTitle);              
              $objTree->setId($objNavigationItem->idFolder);
              $objTree->setParentId($objNavigationItem->parentId);
              $objTree->setItemId($objNavigationItem->folderId);
              $objTree->setUrl(($objNavigationItem->idPageTypes == $this->core->sysConfig->page_types->external->id) ? $objNavigationItem->external : '/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url);
              
              $intTreeId = $objNavigationItem->idFolder;	        		
	        	}
	        	
	          if($objNavigationItem->pageId != null){
              if($objNavigationItem->isStartPage == 1){
                $objTree->setUrl(($objNavigationItem->idPageTypes == $this->core->sysConfig->page_types->external->id) ? $objNavigationItem->external : '/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url);
              }else{               
                $objItem = new NavigationItem();
                $objItem->setTitle($objNavigationItem->title);
                $objItem->setUrl(($objNavigationItem->idPageTypes == $this->core->sysConfig->page_types->external->id) ? $objNavigationItem->external : '/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url);
                $objItem->setId($objNavigationItem->idPage);
                $objItem->setParentId($objNavigationItem->idFolder);
                $objItem->setItemId($objNavigationItem->pageId);
                $objTree->addItem($objItem, 'item_'.$objItem->getId());
              }            
            }	        
	        }          	
        }
      }

     /**
       * add to parent tree
       */      
      if(isset($objTree) && is_object($objTree) && $objTree instanceof NavigationTree){
        $objNavigationTree->addToParentTree($objTree, 'tree_'.$objTree->getId());
      }
      
      $this->objMainNavigation = $objNavigationTree;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * loadStaticSubNavigation
   * @param integer $intDepth
   * @author Thomas Schedler <tsh@massiveart.com>   
   * @version 1.0
   */
  public function loadStaticSubNavigation($intDepth = 2){
    try{
      $this->getModelFolders();
      
      $this->evaluateRootFolderId();
      
      $objNavigationTree = new NavigationTree();
      $objNavigationTree->setId($this->intRootFolderId);
        
      if($this->intRootFolderId > 0){
        $objSubNavigationData = $this->objModelFolders->loadWebsiteStaticSubNavigation($this->intRootFolderId, $intDepth);
        $intTreeId = 0;
        foreach($objSubNavigationData as $objSubNavigationItem){
          if($this->intRootFolderId == $objSubNavigationItem->idFolder){
            if(isset($objSubNavigationItem->isStartPage) && $objSubNavigationItem->isStartPage == 1){
              $objNavigationTree->setTitle($objSubNavigationItem->folderTitle);
              $objNavigationTree->setUrl('/'.strtolower($objSubNavigationItem->languageCode).'/'.$objSubNavigationItem->url);
            }else{
              if(isset($objSubNavigationItem->idWidgetInstance)){
              	$objItem = new NavigationItem();
              	$objItem->setTitle($objSubNavigationItem->widgetInstanceTitle);
              	$objItem->setUrl('/'.strtolower($objSubNavigationItem->languageCode).'/'.$objSubNavigationItem->url);            //TODO Set Url
              	$objItem->setId($objSubNavigationItem->idWidgetInstance);
              	$objItem->setParentId($objSubNavigationItem->idFolder);
              	$objItem->setOrder($objSubNavigationItem->widgetInstanceOrder);
              	$objItem->setItemId($objSubNavigationItem->idWidgetInstance);
              }else{
              	if(isset($objSubNavigationItem->pageId)){
	                $objItem = new NavigationItem();
	                
	                $objItem->setTitle($objSubNavigationItem->pageTitle);
	                $objItem->setUrl('/'.strtolower($objSubNavigationItem->languageCode).'/'.$objSubNavigationItem->url);
	                $objItem->setId($objSubNavigationItem->idPage);
	                $objItem->setParentId($objSubNavigationItem->idFolder);
	                $objItem->setOrder($objSubNavigationItem->pageOrder);            
	                $objItem->setItemId($objSubNavigationItem->idPage);
              	}
              }
              if(isset($objItem)) $objNavigationTree->addItem($objItem, 'item_'.$objItem->getId());
            }
          }else{            
            if($intTreeId != $objSubNavigationItem->idFolder){
              /**
               * add to parent tree
               */   
              if(isset($objTree) && is_object($objTree) && $objTree instanceof NavigationTree){
                $objNavigationTree->addToParentTree($objTree, 'tree_'.$objTree->getId());
              }
                            
              $objTree = new NavigationTree();
              $objTree->setTitle($objSubNavigationItem->folderTitle);              
              $objTree->setId($objSubNavigationItem->idFolder);
              $objTree->setParentId($objSubNavigationItem->parentId);
              $objTree->setOrder($objSubNavigationItem->folderOrder);
              $objTree->setItemId($objSubNavigationItem->folderId);
              
              $intTreeId = $objSubNavigationItem->idFolder;
            }
            
            if($objSubNavigationItem->pageId != null){
              if($objSubNavigationItem->isStartPage == 1){
                $objTree->setUrl('/'.strtolower($objSubNavigationItem->languageCode).'/'.$objSubNavigationItem->url);
                //$objTree->setItemId($objSubNavigationItem->pageId);
              }else{            
                $objItem = new NavigationItem();
                $objItem->setTitle($objSubNavigationItem->pageTitle);
                $objItem->setUrl('/'.strtolower($objSubNavigationItem->languageCode).'/'.$objSubNavigationItem->url);
                $objItem->setId($objSubNavigationItem->idPage);
                $objItem->setParentId($objSubNavigationItem->idFolder);
                $objItem->setOrder($objSubNavigationItem->pageOrder);
                $objItem->setItemId($objSubNavigationItem->pageId);
                $objTree->addItem($objItem, 'item_'.$objItem->getId());
              }
            }
          }
        }
      }
      
      /**
       * add to parent tree
       */      
      if(isset($objTree) && is_object($objTree) && $objTree instanceof NavigationTree){
        $objNavigationTree->addToParentTree($objTree, 'tree_'.$objTree->getId());
      }
      
      $this->objSubNavigation = $objNavigationTree;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * evaluateRootFolderId
   * @author Thomas Schedler <tsh@massiveart.com>   
   * @version 1.0
   */
  public function evaluateRootFolderId(){
    if(isset($this->objPage) && is_object($this->objPage) && $this->intRootFolderId == 0){
      if($this->objPage->getParentTypeId() == $this->core->sysConfig->parent_types->folder){
        $this->objParentFolders = $this->getModelFolders()->loadParentFolders($this->objPage->getParentId());
        
        //print_r($this->objParentFolders);
        
        if(count($this->objParentFolders) > 0){
          $this->intRootFolderId = $this->objParentFolders[count($this->objParentFolders) - 1]->id;
          $this->strRootFolderId = $this->objParentFolders[count($this->objParentFolders) - 1]->folderId;
        }
      }
    }
  }
  
  /**
   * getParentFolderIds
   * @return array $arrParentFolderIds
   * @author Thomas Schedler <tsh@massiveart.com>   
   * @version 1.0
   */
  public function getParentFolderIds(){
    $arrParentFolderIds = array();
    if(count($this->objParentFolders) > 0){
      foreach($this->objParentFolders as $objParentFolder){
        $arrParentFolderIds[] = $objParentFolder->folderId;
      }
    }
    
    return $arrParentFolderIds;
  }
  
  /**
   * getModelFolders
   * @return Model_Folders
   * @author Thomas Schedler <tsh@massiveart.com>
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
   * setPage
   * @param Page $objPage
   */
  public function setPage(Page &$objPage){
    $this->objPage = $objPage;
  }
  
  /**
   * setRootLevelId
   * @param integer $intRootLevelId
   */
  public function setRootLevelId($intRootLevelId){
    $this->intRootLevelId = $intRootLevelId;
  }

  /**
   * getRootLevelId
   * @param integer $intRootLevelId
   */
  public function getRootLevelId(){
    return $this->intRootLevelId;
  }

  /**
   * getRootFolderId
   * @param string $strRootFolderId
   */
  public function getRootFolderId(){
    return $this->strRootFolderId;
  }  
  
  /**
   * setLanguageId
   * @param integer $intLanguageId
   */
  public function setLanguageId($intLanguageId){
    $this->intLanguageId = $intLanguageId;  
  }
  
  /**
   * getLanguageId
   * @param integer $intLanguageId
   */
  public function getLanguageId(){
    return $this->intLanguageId;  
  }
}
?>