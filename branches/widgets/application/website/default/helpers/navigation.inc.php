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
 * @package    application.website.default.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Navigation output functions
 *
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-09: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

/**
 * getNavigationObject
 * @return Navigation
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */
function getNavigationObject(){
  return Zend_Registry::get('Navigation');
}

/**
 * get_main_navigation
 * @param string $strElement
 * @param string|array $mixedElementProperties element css class or array with element properties
 * @param string $strSelectedClass
 * @param boolean $blnWithHomeLink
 * @param boolean $blnImageNavigation
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */	
function get_main_navigation($strElement = 'li', $mixedElementProperties = '', $strSelectedClass = 'selected', $blnWithHomeLink = true, $blnImageNavigation = false){
  $strMainNavigation = '';
  $strHomeLink = '';
  
	$objNavigation = getNavigationObject();
	$objCore = Zend_Registry::get('Core');
	
	$objNavigation->loadMainNavigation();
	
	$strPageId = '';	
	if(is_object($objNavigation->Page())){
	  $strPageId = $objNavigation->Page()->getPageId();
	}		
	$strFolderId = $objNavigation->getRootFolderId();
	
	$strElementProperties = '';
	if(is_array($mixedElementProperties)){
	  foreach($mixedElementProperties as $strProperty => $strValue){
	   $strElementProperties .= ' '.$strProperty.'="'.$strValue.'"';  
	  }
	}else if($mixedElementProperties != ''){
	  $strElementProperties = ' class="'.$mixedElementProperties.'"';
	}
	
	if(count($objNavigation->MainNavigation()) > 0){	  
	  foreach($objNavigation->MainNavigation() as $objNavigationItem){
	    
	    $strSelectedItem = '';
	    $strSelectedImg = 'off';
	    if($strPageId == $objNavigationItem->pageId){
        $strSelectedItem = ' class="'.$strSelectedClass.'"';
        $strSelectedImg = 'on';
	    }else if($strFolderId == $objNavigationItem->folderId){
	      $strSelectedItem = ' class="'.$strSelectedClass.'"';
	      $strSelectedImg = 'on';
	    }
	    
	    $strImgFileTitle = strtolower($objNavigationItem->url);
	    if(strpos($strImgFileTitle, '/') > -1){
	      $strImgFileTitle = substr($strImgFileTitle, 0, strpos($strImgFileTitle, '/'));   	
	    }
	    
	    if($objNavigationItem->isStartPage == 1 && $blnWithHomeLink == true){
	      if($blnImageNavigation){
	        $strHomeLink = '<'.$strElement.$strElementProperties.$strSelectedItem.'><a href="/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url.'"'.$strSelectedItem.'><img src="/website/themes/default/images/navigation/home_'.$strSelectedImg.'.gif" alt="'.htmlentities($objNavigationItem->title, ENT_COMPAT, $objCore->sysConfig->encoding->default).'"/></a></'.$strElement.'>';	
	      }else{
	        $strHomeLink = '<'.$strElement.$strElementProperties.$strSelectedItem.'><a href="/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url.'"'.$strSelectedItem.'>'.htmlentities($objNavigationItem->title, ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a></'.$strElement.'>';	
	      }
	    }else{
	    	if($blnImageNavigation){
	    	  $strMainNavigation  .= '<'.$strElement.$strElementProperties.$strSelectedItem.'><a href="/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url.'"'.$strSelectedItem.'><img src="/website/themes/default/images/navigation/'.$strImgFileTitle.'_'.$strSelectedImg.'.gif" alt="'.htmlentities($objNavigationItem->title, ENT_COMPAT, $objCore->sysConfig->encoding->default).'"/></a></'.$strElement.'>';	
	    	}else{
	    	  $strMainNavigation  .= '<'.$strElement.$strElementProperties.$strSelectedItem.'><a href="/'.strtolower($objNavigationItem->languageCode).'/'.$objNavigationItem->url.'"'.$strSelectedItem.'>'.htmlentities($objNavigationItem->title, ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a></'.$strElement.'>';  
	    	}
	    }
	  }
	}
		
  echo $strHomeLink.$strMainNavigation;
}

/**
 * get_main_navigation_title
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */ 
function get_main_navigation_title(){
  $objNavigation = getNavigationObject();
  $objCore = Zend_Registry::get('Core');
  
  $strHtmlOutput = '';
  
  $objNavigation->loadMainNavigation();
  
  $strPageId = ''; 
  $strWidgetId = ''; 
  if(is_object($objNavigation->Page())){
    $strPageId = $objNavigation->Page()->getPageId();
  }else if(is_object($objNavigation->Widget())){
  	$strWidgetId = $objNavigation->Widget()->getWidgetInstanceId();
  }
  $strFolderId = $objNavigation->getRootFolderId();
  
  if(count($objNavigation->MainNavigation()) > 0){    
    foreach($objNavigation->MainNavigation() as $objNavigationItem){
			
      $blnIsSelected = false;
      if($strPageId == $objNavigationItem->pageId){
        $blnIsSelected = true;
      }else if($strFolderId == $objNavigationItem->folderId){
        $blnIsSelected = true;
      }else if($strWidgetId == $objNavigationItem->widgetId){
      	$blnIsSelected = true;
      }else if(is_object($objNavigation->Widget())){
      	if($objNavigationItem->title == $objNavigation->Widget()->getWidgetTitle()){
      		$blnIsSelected = true;
      	}
      }
      
      if($blnIsSelected){
	      $strHtmlOutput  .= htmlentities($objNavigationItem->title, ENT_COMPAT, $objCore->sysConfig->encoding->default);   
      }
    }
  }    
  echo $strHtmlOutput;
}

/**
 * has_sub_navigation
 * @return boolean
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */
function has_sub_navigation(){
  $objNavigation = getNavigationObject();
  
  $objNavigation->evaluateRootFolderId();
  
  if($objNavigation->getRootFolderId() != ''){
    return true;
  }else{
    return false;
  }
}

/**
 * get_static_one_column_sub_navigation
 * @param string $strElement
 * @param string|array $mixedElementProperties element css class or array with element properties
 * @param string $strSelectedClass 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */ 
function get_static_one_column_sub_navigation($strElement = 'div', $mixedElementProperties = 'divSubNavItem', $strSelectedClass = 'selected'){
  $strSubNavigation = '';
  
  $objNavigation = getNavigationObject();
  $objCore = Zend_Registry::get('Core');
    
  $objNavigation->loadStaticSubNavigation(2);
  
  $strPageId = '';  
  if(is_object($objNavigation->Page())){
    $strPageId = $objNavigation->Page()->getPageId();
  } 
  
  $arrFolderIds = $objNavigation->getParentFolderIds();
    
  $strElementProperties = '';
  $arrElementProperies = array();
  if(is_array($mixedElementProperties)){
    $arrElementProperies = $mixedElementProperties;
  }else{
    $arrElementProperies['class'] = $mixedElementProperties;
  }
  
  if(count($objNavigation->SubNavigation()) > 0){
    foreach($objNavigation->SubNavigation() as $objNavi){
      if($objNavi instanceof NavigationTree){
        $strSubNavigation  .= '
              <div class="divSubNavRegion">
                <div class="divSubNavRegionTitle">'.htmlentities($objNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</div>';
        if(count($objNavi) > 0){
          foreach($objNavi as $objSubNavi){
            $arrProperies = $arrElementProperies;
            if($strPageId == $objSubNavi->getItemId() || in_array($objSubNavi->getItemId(), $arrFolderIds)){
              $arrProperies['class'] = (array_key_exists('class', $arrElementProperies)) ? $arrElementProperies['class'].' '.$strSelectedClass : $strSelectedClass;
            }    
            
            $strSubNavigation  .= '
                <'.$strElement.return_html_attributes($arrProperies).'>
                  <div class="divSubNavLink"><a href="'.$objSubNavi->getUrl().'">'.htmlentities($objSubNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a></div>
                </'.$strElement.'>'; 
          }   
        }else{
          $strSubNavigation  .= '&nbsp;';     
        }
        $strSubNavigation  .= '
              </div>';      
      }else{
        $arrProperies = $arrElementProperies;
        if($strPageId == $objNavi->getItemId() || in_array($objNavi->getItemId(), $arrFolderIds)){
          $arrProperies['class'] = (array_key_exists('class', $arrElementProperies)) ? $arrElementProperies['class'].' '.$strSelectedClass : $strSelectedClass;
        }
         
        $strSubNavigation  .= '
              <'.$strElement.return_html_attributes($arrProperies).'>              
                <div class="divSubNavLink"><a href="'.$objNavi->getUrl().'">'.htmlentities($objNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a></div>
              </'.$strElement.'>';
      }
    }    
  }
  
  echo $strSubNavigation;
}

/**
 * get_static_sub_navigation
 * @param string $strElement
 * @param string|array $mixedElementProperties element css class or array with element properties
 * @param string $strSelectedClass 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */ 
function get_static_sub_navigation($strElement = 'li', $mixedElementProperties = '', $strSelectedClass = 'selected'){
  $strSubNavigation = '';
  
  $objNavigation = getNavigationObject();
  $objCore = Zend_Registry::get('Core');
    
  $objNavigation->loadStaticSubNavigation(2);
  
  $strPageId = '';  
  if(is_object($objNavigation->Page())){
    $strPageId = $objNavigation->Page()->getPageId();
  } 
  
  $arrFolderIds = $objNavigation->getParentFolderIds();
    
  $strElementProperties = '';
  $arrElementProperies = array();
  if(is_array($mixedElementProperties)){
    $arrElementProperies = $mixedElementProperties;
  }else{
    $arrElementProperies['class'] = $mixedElementProperties;
  }
  
  if(count($objNavigation->SubNavigation()) > 0){
    foreach($objNavigation->SubNavigation() as $objNavi){
      if($objNavi instanceof NavigationTree){
        $strSubNavigation  .= '
              <'.$strElement.'>
                <a href="'.$objNavi->getUrl().'">'.htmlentities($objNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a>';
        if(count($objNavi) > 0){
          $strSubNavigation  .= '<ul>';
        	foreach($objNavi as $objSubNavi){           
            $strSubNavigation  .= '
                <'.$strElement.'>
                  <a href="'.$objSubNavi->getUrl().'">'.htmlentities($objSubNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a>
                </'.$strElement.'>'; 
          } 
          $strSubNavigation  .= '</ul>';  
        }else{
          $strSubNavigation  .= '&nbsp;';     
        }
        $strSubNavigation  .= '
             </'.$strElement.'>';      
      }else{         
        $strSubNavigation  .= '
              <'.$strElement.'>              
                <a href="'.$objNavi->getUrl().'">'.htmlentities($objNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a>
              </'.$strElement.'>';
      }
    }    
  }
  
  echo $strSubNavigation;
}

/**
 * return_html_attributes
 * @param array $arrAttributes
 * @return string $strXhtml 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */
function return_html_attributes($arrAttributes){
  $strXhtml = '';
  
  foreach((array) $arrAttributes as $key => $val){
    if (strpos($val, '"') !== false) {
      $strXhtml .= " $key='$val'";
    } else {
      $strXhtml .= " $key=\"$val\"";
    }
  }
  return $strXhtml;
}

/**
 * return_sub_navigation_sub_tree
 * @param NavigationTree $objNaviTree
 * @param integer $intLevel
 * @return string
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */
function return_sub_navigation_sub_tree($objNaviTree, $intLevel){
  $strSubNavigation = '';
  $objCore = Zend_Registry::get('Core');
  
  foreach($objNaviTree as $objNavi){
    if(is_object($objNavi) && $objNavi instanceof NavigationTree && ($objNavi->hasSubTrees() || $intLevel == 1)){
      $strSubNavigation  .= '
            <div class="divSubNavRegion">
              <div class="divSubNavRegionTitle">'.htmlentities($objNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</div>';
      $strSubNavigation .= get_sub_navigation_sub_tree($objNavi, $intLevel + 1);      
      $strSubNavigation .= '
            </div>';      
    }else{
      $strSubNavigation  .= '
            <div class="divSubNavItem">
              <div class="divSubNavLink"><a href="'.$objNavi->getUrl().'">'.htmlentities($objNavi->getTitle(), ENT_COMPAT, $objCore->sysConfig->encoding->default).'</a></div>
            </div>';
    }
  }
  return $strSubNavigation;
}

/**
 * get_tree_sub_navigation
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */ 
function get_tree_sub_navigation(){
  //TODO:: get_tree_sub_navigation
}

/**
 * get_breadcrumb
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */ 
function get_breadcrumb(){
  $strBreadcrumb = '';
 
  $objNavigation = getNavigationObject();
  $objCore = Zend_Registry::get('Core');
  
  if(count($objNavigation->ParentFolders()) > 0){
    $arrParentFolders = array_reverse($objNavigation->ParentFolders());
    
    foreach($arrParentFolders as $obFolder){
      $strBreadcrumb .= htmlentities($obFolder->title, ENT_COMPAT, $objCore->sysConfig->encoding->default).' / '; 
    }
  }
  
  echo $strBreadcrumb;
}

?>