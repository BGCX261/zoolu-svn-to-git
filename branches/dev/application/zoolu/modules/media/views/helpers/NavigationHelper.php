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
 * @package    application.zoolu.modules.core.media.views.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * NavigationHelper
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-10-16: Thomas Schedler
 * 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class NavigationHelper {
  
	/**
   * @var Core
   */
  private $core;
  
  /**
   * Constructor 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }
  
  /**
   * getMediaTypes 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  function getMediaTypes($objRowset) {
    $this->core->logger->debug('media->views->helpers->NavigationHelper->getMediaTypes()');
    
    $strOutput = '';
    
    $strRootLevelIconCss = '';
    $strViewType = 'list';
    foreach ($objRowset as $objRow) {      
      if(Security::get()->isAllowed(Security::RESOURCE_ROOT_LEVEL_PREFIX.$objRow->id, Security::PRIVILEGE_VIEW)){
      	switch($objRow->idRootLevelTypes){    		
      		case $this->core->sysConfig->root_level_types->images: 
      		  $strRootLevelIconCss = 'imageicon';
      		  $strViewType = 'thumb';
            $strRootLevelType = 'image';
      		  break;
      		
      		case $this->core->sysConfig->root_level_types->documents: 
            $strRootLevelIconCss = 'documenticon';
            $strViewType = 'list';
            $strRootLevelType = 'document';
            break;
            
          case $this->core->sysConfig->root_level_types->videos: 
            $strRootLevelIconCss = 'videoicon';
            $strViewType = 'list';
            $strRootLevelType = 'video';
            break;
      	}
    
        /**
         * get values of the row and create output
         */ 
        $strOutput .= '<div class="portalcontainer">
          <div id="portal'.$objRow->id.'top" class="portaltop"><img src="/zoolu-statics/images/main/bg_box_230_top.png" width="230" height="4"/></div>
          <div id="portal'.$objRow->id.'" class="portal" onclick="myNavigation.selectMediaType('.$objRow->id.', \''.$strViewType.'\'); return false;">
            <div class="'.$strRootLevelIconCss.'"></div>
            <div id="divRootLevelTitle_'.$objRow->id.'" class="portaltitle">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
            <input type="hidden" value="'.$strRootLevelType.'" id="rootLevelType'.$objRow->id.'"/>
            <div class="clear"></div>
          </div>
          <div id="portal'.$objRow->id.'bottom" class="portalbottom"><img src="/zoolu-statics/images/main/bg_box_230_bottom.png" width="230" height="4"/></div>
          <div class="clear"></div>
        </div>';
      }
    }
       
    return $strOutput;
  }
	
	/**
   * getNavigationElements 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  function getNavigationElements($objRowset, $currLevel) {
    $this->core->logger->debug('media->views->helpers->NavigationHelper->getNavigationElements()');
    
    $strOutput = '';
    
    if(count($objRowset) > 0){
      foreach ($objRowset as $strField => $objRow){
        if(Security::get()->isAllowed(Security::RESOURCE_FOLDER_PREFIX.$objRow->id, Security::PRIVILEGE_VIEW)){      
          
          $strFolderTitle = $objRow->title;
          
          // gui fallback title
          if($strFolderTitle == '' && $objRow->elementType == 'folder'){
            $strFolderTitle = $objRow->guiTitle;
            $objRow->type = 'folder';
            $objRow->genericFormId = $this->core->sysConfig->form->ids->folders->default;
            $objRow->version = 'null';
            $objRow->templateId = -1;
          }
        
          /**
           * get values of the row and create default output
           */
          $strOutput .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div id="divNavigationEdit_'.$objRow->id.'" class="icon img_'.$objRow->type.'_on" ondblclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.'); return false;"></div>
            <div id="divNavigationTitle_'.$objRow->type.$objRow->id.'" class="title" onclick="myNavigation.selectNavigationItem('.$currLevel.', \''.$objRow->type.'\','.$objRow->id.'); myMedia.getMediaFolderContent('.$objRow->id.'); return false;">'.htmlentities($strFolderTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';
        }
      }
    }
     
    return $strOutput;
  }
  
}

?>