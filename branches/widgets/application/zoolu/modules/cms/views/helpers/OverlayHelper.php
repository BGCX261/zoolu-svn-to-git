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
 * OverlayHelper
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-24: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

require_once (dirname(__FILE__).'/../../../media/views/helpers/ViewHelper.php');

class OverlayHelper {

  /**
   * @var Core
   */
  private $core;

  /**
   * @var ViewHelper
   */
  private $objViewHelper;

  /**
   * Constructor
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }

  /**
   * getNavigationElements
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getNavigationElements($rowset, $viewtype, $intFolderId = 0) {
    $this->core->logger->debug('cms->views->helpers->OverlayHelper->getNavigationElements()');

    $strOutput = '';

    if(count($rowset) > 0){
      foreach ($rowset as $row){
        if($intFolderId == 0){
          $strOutput .= '<div id="olnavitem'.$row->id.'" class="olnavrootitem">
                           <div onclick="myOverlay.getNavItem('.$row->id.','.$viewtype.'); return false;" style="position:relative;">
                             <div class="icon img_folder_off"></div>'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                           </div>
                         </div>';
        }else{
          $strOutput .= '<div id="olnavitem'.$row->id.'" class="olnavchilditem">
                           <div onclick="myOverlay.getNavItem('.$row->id.','.$viewtype.'); return false;" style="position:relative;">
                             <div class="icon img_folder_off"></div>'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                           </div>
                         </div>';
        }
      }
    }

    /**
     * return html output
     */
    return $strOutput;
  }

  /**
   * getNavigationElements
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getContactNavigationElements($rowset, $intUnitId = 0) {
    $this->core->logger->debug('cms->views->helpers->OverlayHelper->getContactNavigationElements()');

    $strOutput = '';

    if($intUnitId == 0){
      $strOutput .= '<div id="olnavitem0" class="olnavrootitem">
                       <div onclick="myOverlay.getContactNavItem(0); return false;" style="position:relative;">
                         <div class="icon img_folder_off"></div>Kontakte
                       </div>
                       <div id="olsubnav0" class="" style="display: none;">';
    }

    if(count($rowset) > 0){
      foreach ($rowset as $row){
        $strOutput .= '<div id="olnavitem'.$row->id.'" class="olnavchilditem">
                         <div onclick="myOverlay.getContactNavItem('.$row->id.'); return false;" style="position:relative;">
                           <div class="icon img_folder_off"></div>'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                         </div>
                       </div>';

      }
    }

    if($intUnitId == 0){
      $strOutput .= '
                      </div>
                    </div>';
    }

    /**
     * return html output
     */
    return $strOutput;
  }

  /**
   * getPageTree
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getPageTree($objRowset) {
    $this->core->logger->debug('cms->views->helpers->OverlayHelper->getPageTree()');

    $strOutput = '';

    if(count($objRowset) > 0){
      $intLastFolderId = 0;
      foreach ($objRowset as $objRow){

        if($intLastFolderId != $objRow->folderId){

          $intFolderDepth = $objRow->depth;

          $strOutput .= '<div id="olnavitem'.$objRow->folderId.'" class="olnavrootitem">
                           <div style="position:relative; padding-left:'.(20*$intFolderDepth).'px">
                             <div class="icon img_folder_'.(($objRow->folderStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>'.htmlentities($objRow->folderTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                           </div>
                         </div>';

          $intLastFolderId = $objRow->folderId;
        }

        if($objRow->idPage > 0){
          $strOutput .= '<div id="olnavitem'.$objRow->pageId.'" class="olnavrootitem">
                         <div style="position:relative; padding-left:'.(20*$intFolderDepth+20).'px">
                           <a href="#" onclick="myOverlay.selectPage('.$objRow->idPage.'); return false;"><div class="icon img_'.(($objRow->isStartPage == 1) ? 'startpage' : 'page').'_'.(($objRow->pageStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>'.htmlentities($objRow->pageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                         </div>
                       </div>';
        }
      }
    }

    /**
     * return html output
     */
    return $strOutput;
  }

  /**
   * getThumbView
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getThumbView($rowset, $arrFileIds){
  	$this->core->logger->debug('cms->views->helpers->OverlayHelper->getThumbView()');

    $strOutputTop = '<div class="olmediacontainer">';

    /**
     * output of each thumb
     */
    $strOutput = '';
    foreach ($rowset as $row) {
    	if($row->isImage){
    		$strHidden = '';

    		if($row->xDim < $row->yDim){
	        $strMediaSize = 'height="100"';
	      }else{
	        $strMediaSize = 'width="100"';
	      }

        if(array_search($row->id, $arrFileIds) !== false){
          $strHidden = ' style="display:none;"';
        }

	      $strOutput .= '<div id="olMediaItem'.$row->id.'" class="olmediaitem" fileid="'.$row->id.'"'.$strHidden.'>
                         <table>
                           <tbody>
                             <tr>
                               <td>
                                 <img onclick="myOverlay.addItemToThumbArea(\'olMediaItem'.$row->id.'\', '.$row->id.'); return false;" id="Img'.$row->id.'" alt="'.$row->title.'" title="'.$row->title.'" src="'.$this->core->sysConfig->media->paths->thumb.$row->filename.'" '.$strMediaSize.'/>
                               </td>
                             </tr>
                           </tbody>
                         </table>
                         <div id="Remove'.$row->id.'" class="itemremovethumb" style="display:none;"></div>
                       </div>';
	    }
    }

    /**
     * return html output
     */
    if($strOutput != ''){
	    return $strOutputTop.$strOutput.'
		           <div class="clear"></div>
		         </div>';
    }
  }

  /**
   * getListView
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getListView($rowset, $arrFileIds){
    $this->core->logger->debug('cms->views->helpers->OverlayHelper->getListView()');

    $this->objViewHelper = new ViewHelper();

    /**
     * create header of list output
     */
    $strOutputTop = '
            <div>
              <div class="oldoctopleft"></div>
              <div class="oldoctopitemicon"></div>
              <div class="oldoctopitemtitle bold">Titel</div>
              <div class="oldoctopright"></div>
              <div class="clear"></div>
            </div>
            <div class="oldocitemcontainer">';

    /**
     * output of list rows (elements)
     */
    $strOutput = '';
    foreach ($rowset as $row) {
    	$strHidden = '';
    	if(array_search($row->id, $arrFileIds) !== false){
    	 $strHidden = ' style="display:none;"';
    	}
    	if($row->isImage){
      	$strOutput .= '
            <div class="oldocitem" id="olDocItem'.$row->id.'" onclick="myOverlay.addItemToListArea(\'olDocItem'.$row->id.'\', '.$row->id.'); return false;"'.$strHidden.'>
              <div class="oldocleft"></div>
              <div style="display:none;" id="Remove'.$row->id.'" class="itemremovelist"></div>
              <div class="oldocitemicon"><img width="32" height="32" id="Doc'.$row->id.'" src="'.$this->core->sysConfig->media->paths->icon32.$row->filename.'" alt="'.$row->description.'" width="16" height="16"/></div>
              <div class="oldocitemtitle">'.$row->title.'</div>
              <div class="oldocright"></div>
              <div class="clear"></div>
            </div>';
      }else{
        $strOutput .= '
            <div class="oldocitem" id="olDocItem'.$row->id.'" onclick="myOverlay.addItemToListArea(\'olDocItem'.$row->id.'\', '.$row->id.'); return false;"'.$strHidden.'>
              <div class="oldocleft"></div>
              <div style="display:none;" id="Remove'.$row->id.'" class="itemremovelist"></div>
              <div class="oldocitemicon"><img width="32" height="32" id="Doc'.$row->id.'" src="'.$this->objViewHelper->getDocIcon($row->extension, 32).'" alt="'.$row->description.'"/></div>
              <div class="oldocitemtitle">'.$row->title.'</div>
              <div class="oldocright"></div>
              <div class="clear"></div>
            </div>';
      }
    }
    /**
     * list footer
     */
    $strOutputBottom = '
              <div class="clear"></div>
            </div>
            <div>
              <div class="oldocbottomleft"></div>
              <div class="oldocbottomcenter"></div>
              <div class="oldocbottomright"></div>
              <div class="clear"></div>
            </div>';

    /**
     * return html output
     */
    if($strOutput != ''){
    	return $strOutputTop.$strOutput.$strOutputBottom.'<div class="clear"></div>';
    }
  }

  /**
   * getContactListView
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getContactListView($rowset, $arrFileIds){
    $this->core->logger->debug('cms->views->helpers->OverlayHelper->getContactListView()');

    $this->objViewHelper = new ViewHelper();

    /**
     * create header of list output
     */
    $strOutputTop = '
            <div>
              <div class="oldoctopleft"></div>
              <div class="oldoctopitemicon"></div>
              <div class="oldoctopitemtitle bold">Titel</div>
              <div class="oldoctopright"></div>
              <div class="clear"></div>
            </div>
            <div class="oldocitemcontainer">';

    /**
     * output of list rows (elements)
     */
    $strOutput = '';
    foreach ($rowset as $row) {
      $strHidden = '';
      if(array_search($row->id, $arrFileIds) !== false){
       $strHidden = ' style="display:none;"';
      }

      $strOutput .= '
          <div class="oldocitem" id="olDocItem'.$row->id.'" onclick="myOverlay.addItemToListArea(\'olDocItem'.$row->id.'\', '.$row->id.'); return false;"'.$strHidden.'>
            <div class="oldocleft"></div>
            <div style="display:none;" id="Remove'.$row->id.'" class="itemremovelist"></div>
            <div class="oldocitemicon">';
      if($row->filename != ''){
        $strOutput .= '<img width="32" height="32" id="Doc'.$row->id.'" src="'.$this->core->sysConfig->media->paths->icon32.$row->filename.'" alt="'.$row->title.'" width="16" height="16"/>';
      }

      $strOutput .= '</div>
            <div class="oldocitemtitle">'.$row->title.'</div>
            <div class="oldocright"></div>
            <div class="clear"></div>
          </div>';
    }
    /**
     * list footer
     */
    $strOutputBottom = '
              <div class="clear"></div>
            </div>
            <div>
              <div class="oldocbottomleft"></div>
              <div class="oldocbottomcenter"></div>
              <div class="oldocbottomright"></div>
              <div class="clear"></div>
            </div>';

    /**
     * return html output
     */
    if($strOutput != ''){
      return $strOutputTop.$strOutput.$strOutputBottom.'<div class="clear"></div>';
    }
  }
}

?>