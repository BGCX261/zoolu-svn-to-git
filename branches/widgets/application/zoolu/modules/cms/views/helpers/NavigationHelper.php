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
 * @package    application.zoolu.modules.cms.views
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */


/**
 * NavigationHelper
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-10-16: Cornelius Hansjakob
 * 1.1, 2009-08-06: Daniel Rotter - Added Widget-Output
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class NavigationHelper {

	/**
   * @var Core
   */
  private $core;

  /**
   * Constructor
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }

	/**
   * getPortals
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  function getPortals($objRowset) {
    $this->core->logger->debug('cms->views->helpers->NavigationHelper->getPortals()');

  	$strOutput = '';

    foreach ($objRowset as $objRow) {
      /**
       * get values of the row and create output
       */
      $strOutput .= '<div class="portalcontainer">
        <div id="portal'.$objRow->id.'top" class="portaltop"><img src="/zoolu/images/main/bg_box_230_top.png" width="230" height="4"/></div>
        <div id="portal'.$objRow->id.'" class="portal" onclick="myNavigation.selectPortal('.$objRow->id.'); myNavigation.loadDashboard(); return false;">
          <div class="portalicon"></div>
          <div id="divRootLevelTitle_'.$objRow->id.'" class="portaltitle">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          <div class="clear"></div>
        </div>
        <div id="portal'.$objRow->id.'menu" style="display:none;" class="portalmenu">
          <div class="portalmenulink">
            <div class="portalcontenticon"></div>
            <div class="portalmenutitle"><a href="#" onclick="myNavigation.selectPortal('.$objRow->id.'); return false;">Webinhalte</a></div>
            <div class="clear"></div>
          </div>
          <!--<div class="portalmenulink">
            <div class="portalwidgetsicon"></div>
            <div class="portalmenutitle"><a href="#" onclick="return false;">Widgets</a></div>
            <div class="clear"></div>
          </div>
          <div class="portalmenulink">
            <div class="portalsettingsicon"></div>
            <div class="portalmenutitle"><a href="#" onclick="return false;">Einstellungen</a></div>
            <div class="clear"></div>
          </div>-->
          <div class="clear"></div>
        </div>
        <div id="portal'.$objRow->id.'bottom" class="portalbottom"><img src="/zoolu/images/main/bg_box_230_bottom.png" width="230" height="4"/></div>
        <div class="clear"></div>
      </div>';
    }

    return $strOutput;
  }

  /**
   * getNavigationElements
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  function getNavigationElements($objRowset, $currLevel) {
  	$this->core->logger->debug('cms->views->helpers->NavigationHelper->getNavigationElements()');

    $strOutput = '';
    $strOutputStartpage = '';

    $counter = 1;

    if(count($objRowset) > 0){
    	foreach ($objRowset as $objRow){

    		//$objRow->sortPosition
    		$strPageTitle = ($objRow->pageLinkTitle != '' & $objRow->pageLinkTitle != -1 && $objRow->pageType == $this->core->sysConfig->page_types->link->id) ? $objRow->pageLinkTitle : $objRow->title;

    		if($objRow->isStartPage == 1){
    		  /**
           * overwrite type with 'page'
           */
          $objRow->type = 'page';

          $strTitleAddonClass = '';
          $strTitleAddon = '';
          if($objRow->pageType == $this->core->sysConfig->page_types->link->id){
            $strTitleAddonClass = ' italic';
            $strTitleAddon = '&infin; ';
          }

    			/**
           * get values of the row and create startpage output
           */
          $strOutputStartpage .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div class="icon img_startpage_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>
            <div class="title'.$strTitleAddonClass.'" onclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.','.$objRow->templateId.'); return false;">'.$strTitleAddon.htmlentities($strPageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';
    		}else if($objRow->pageType == $this->core->sysConfig->page_types->page->id){
    		  /**
           * get values of the row and create page output
           */
          $strOutput .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div class="icon img_'.$objRow->type.'_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_'.$objRow->type.'_'.$objRow->id.'" id="pos_'.$objRow->type.'_'.$objRow->id.'" value="'.$counter.'" onfocus="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" onkeyup="if(event.keyCode==13){ myNavigation.updateSortPosition(\'pos_'.$objRow->type.'_'.$objRow->id.'\',\''.$objRow->type.'\','.$currLevel.'); myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false; }" onblur="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" /></div>
            <div class="title" onclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.','.$objRow->templateId.'); return false;">'.htmlentities($strPageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';

          $counter++;

    		}else if($objRow->pageType == $this->core->sysConfig->page_types->link->id){
    		  /**
    		   * overwrite type with 'page'
    		   */
    		  $objRow->type = 'page';

          /**
           * get values of the row and create page output
           */
          $strOutput .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div class="icon img_'.$objRow->type.'_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_'.$objRow->type.'_'.$objRow->id.'" id="pos_'.$objRow->type.'_'.$objRow->id.'" value="'.$counter.'" onfocus="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" onkeyup="if(event.keyCode==13){ myNavigation.updateSortPosition(\'pos_'.$objRow->type.'_'.$objRow->id.'\',\''.$objRow->type.'\','.$currLevel.'); myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false; }" onblur="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" /></div>
            <div class="title italic" onclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.','.$objRow->templateId.'); return false;">&infin; '.htmlentities($strPageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';

          $counter++;

        }else if($objRow->pageType == $this->core->sysConfig->page_types->external->id){
          /**
           * overwrite type with 'page'
           */
          $objRow->type = 'page';

          /**
           * get values of the row and create page output
           */
          $strOutput .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div class="icon img_'.$objRow->type.'_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_'.$objRow->type.'_'.$objRow->id.'" id="pos_'.$objRow->type.'_'.$objRow->id.'" value="'.$counter.'" onfocus="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" onkeyup="if(event.keyCode==13){ myNavigation.updateSortPosition(\'pos_'.$objRow->type.'_'.$objRow->id.'\',\''.$objRow->type.'\','.$currLevel.'); myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false; }" onblur="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" /></div>
            <div class="title" onclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.','.$objRow->templateId.'); return false;">'.htmlentities($strPageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';

          $counter++;
        }else if($objRow->pageType == $this->core->sysConfig->page_types->process->id){
          /**
           * overwrite type with 'page'
           */
          $objRow->type = 'page';

          /**
           * get values of the row and create page output
           */
          $strOutput .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div class="icon img_'.$objRow->type.'_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_'.$objRow->type.'_'.$objRow->id.'" id="pos_'.$objRow->type.'_'.$objRow->id.'" value="'.$counter.'" onfocus="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" onkeyup="if(event.keyCode==13){ myNavigation.updateSortPosition(\'pos_'.$objRow->type.'_'.$objRow->id.'\',\''.$objRow->type.'\','.$currLevel.'); myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false; }" onblur="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" /></div>
            <div class="title" onclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.','.$objRow->templateId.'); return false;">'.htmlentities($strPageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';

          $counter++;
        }
        elseif($objRow->widgetType != -1)
        {
        	/**
           * get values of the row and create widget output
           */
          $strOutput .= '<div id="widget'.$objRow->id.'" class="widget">
            <div id="divNavigationEditWidget_'.$objRow->id.'" class="icon img_widget_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'" ondblclick="myNavigation.getWidgetEditForm('.$objRow->id.',\''.$objRow->type.'\', \''.$objRow->genericFormId.'\','.$objRow->version.', \''.$objRow->widgetInstanceId.'\'); return false;"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_'.$objRow->type.'_'.$objRow->id.'" id="pos_'.$objRow->type.'_'.$objRow->id.'" value="'.$counter.'" onfocus="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" onkeyup="if(event.keyCode==13){ myNavigation.updateSortPosition(\'pos_'.$objRow->type.'_'.$objRow->id.'\',\''.$objRow->type.'\','.$currLevel.'); myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false; }" onblur="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" /></div>
            <div id="divNavigationTitle_widget'.$objRow->id.'" class="title" onclick="myNavigation.selectWidgetItem('.$currLevel.', \''.$objRow->type.'\', '.$objRow->id.', \''.$objRow->widgetInstanceId.'\'); return false;">'.htmlentities($strPageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';

          $counter++;
        }
        else{
    			/**
	         * get values of the row and create default output
	         */
	        $strOutput .= '<div id="'.$objRow->type.$objRow->id.'" class="'.$objRow->type.'">
            <div id="divNavigationEdit_'.$objRow->id.'" class="icon img_'.$objRow->type.'_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'" ondblclick="myNavigation.getEditForm('.$objRow->id.',\''.$objRow->type.'\',\''.$objRow->genericFormId.'\','.$objRow->version.','.$objRow->templateId.'); return false;"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_'.$objRow->type.'_'.$objRow->id.'" id="pos_'.$objRow->type.'_'.$objRow->id.'" value="'.$counter.'" onfocus="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;" onkeyup="if(event.keyCode==13){ myNavigation.updateSortPosition(\'pos_'.$objRow->type.'_'.$objRow->id.'\',\''.$objRow->type.'\','.$currLevel.'); myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false; }" onblur="myNavigation.toggleSortPosBox(\'pos_'.$objRow->type.'_'.$objRow->id.'\'); return false;"/></div>
            <div id="divNavigationTitle_'.$objRow->type.$objRow->id.'" class="title" onclick="myNavigation.selectNavigationItem('.$currLevel.', \''.$objRow->type.'\','.$objRow->id.'); return false;">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';

	        $counter++;
    		}
    	}

    	if($strOutputStartpage != ''){
    	  $strOutputStartpage .= '<div class="linegray"></div>';
      }
    }

    return $strOutputStartpage.$strOutput;

  }

}

?>