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
                             <div class="icon img_folder_on"></div>'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                           </div>
                         </div>';
        }else{
          $strOutput .= '<div id="olnavitem'.$row->id.'" class="olnavchilditem">
                           <div onclick="myOverlay.getNavItem('.$row->id.','.$viewtype.'); return false;" style="position:relative;">
                             <div class="icon img_folder_on"></div>'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
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
   * getContactNavigationElements
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
        $intFolderDepth = $row->depth + 1;
        $strOutput .= '<div id="olnavitem'.$row->id.'" class="olnavchilditem" style="padding-left:'.(22*$intFolderDepth).'px">
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
  public function getPageTree($objRowset, $strItemAction, $arrPageIds = array()) {
    $this->core->logger->debug('cms->views->helpers->OverlayHelper->getPageTree()');

    $strOutput = '';

    if(count($objRowset) > 0){
      $intLastFolderId = 0;
      foreach ($objRowset as $objRow){
        $strHidden = '';
        if(array_search($objRow->pageId, $arrPageIds) !== false){
         $strHidden = ' style="display:none;"';
        }

        if($intLastFolderId != $objRow->folderId){

          $intFolderDepth = $objRow->depth;

          $strOutput .= '<div id="folder'.$objRow->folderId.'" class="olnavrootitem">
                           <div style="position:relative; padding-left:'.(20*$intFolderDepth).'px">
                             <div class="icon img_folder_'.(($objRow->folderStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>'.htmlentities($objRow->folderTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                           </div>
                         </div>';

          $intLastFolderId = $objRow->folderId;
        }

        if($objRow->idPage > 0){
          $strOutput .= '
                        <div id="olItem'.$objRow->pageId.'" class="olnavrootitem"'.$strHidden.'>
                          <div style="display:none;" id="Remove'.$objRow->idPage.'" class="itemremovelist2"></div>
                          <div id="Item'.$objRow->idPage.'" style="position:relative; margin-left:'.(20*$intFolderDepth+20).'px; cursor:pointer;" onclick="'.$strItemAction.'('.$objRow->idPage.', \''.$objRow->pageId.'\'); return false;">
                            <div class="icon img_'.(($objRow->isStartPage == 1) ? 'startpage' : 'page').'_'.(($objRow->pageStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>'.htmlentities($objRow->pageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
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
                                 <img onclick="myOverlay.addItemToThumbArea(\'olMediaItem'.$row->id.'\', '.$row->id.'); return false;" id="Img'.$row->id.'" alt="'.$row->title.'" title="'.$row->title.'" src="'.sprintf($this->core->sysConfig->media->paths->thumb, $row->path).$row->filename.'?v='.$row->version.'" '.$strMediaSize.'/>
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
              <div class="olfiletopleft"></div>
              <div class="olfiletopitemicon"></div>
              <div class="olfiletopitemtitle bold">Titel</div>
              <div class="olfiletopright"></div>
              <div class="clear"></div>
            </div>';

    /**
     * output of list rows (elements)
     */
    $strOutput = '';
    $blnIsImageView = false;
    if(count($rowset) > 0){
      $strOutput .= '  
            <div class="olfileitemcontainer">';
      foreach ($rowset as $row) {
      	$strHidden = '';
      	if(array_search($row->id, $arrFileIds) !== false){
      	 $strHidden = ' style="display:none;"';
      	}
      	if($row->isImage){
      	  $blnIsImageView = true;
          if($row->xDim < $row->yDim){
            $strMediaSize = 'height="32"';
          }else{
            $strMediaSize = 'width="32"';
          }
        	$strOutput .= '
              <div class="olfileitem" id="olFileItem'.$row->id.'" onclick="myOverlay.addItemToThumbArea(\'olFileItem'.$row->id.'\', '.$row->id.'); return false;"'.$strHidden.'>
                <div class="olfileleft"></div>
                <div style="display:none;" id="Remove'.$row->id.'" class="itemremovelist"></div>
                <div class="olfileitemicon"><img '.$strMediaSize.' id="File'.$row->id.'" src="'.sprintf($this->core->sysConfig->media->paths->icon32, $row->path).$row->filename.'?v='.$row->version.'" alt="'.$row->description.'"/></div>
                <div class="olfileitemtitle">'.htmlentities((($row->title == '' && (isset($row->alternativTitle) || isset($row->fallbackTitle))) ? ((isset($row->alternativTitle) && $row->alternativTitle != '') ? $row->alternativTitle : $row->fallbackTitle) : $row->title), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
                <div class="olfileright"></div>
                <div class="clear"></div>
              </div>';
        }else{
          $strOutput .= '
              <div class="olfileitem" id="olFileItem'.$row->id.'" onclick="myOverlay.addFileItemToListArea(\'olFileItem'.$row->id.'\', '.$row->id.'); return false;"'.$strHidden.'>
                <div class="olfileleft"></div>
                <div style="display:none;" id="Remove'.$row->id.'" class="itemremovelist"></div>
                <div class="olfileitemicon"><img width="32" height="32" id="File'.$row->id.'" src="'.$this->objViewHelper->getDocIcon($row->extension, 32).'" alt="'.$row->description.'"/></div>
                <div class="olfileitemtitle">'.htmlentities((($row->title == '' && (isset($row->alternativTitle) || isset($row->fallbackTitle))) ? ((isset($row->alternativTitle) && $row->alternativTitle != '') ? $row->alternativTitle : $row->fallbackTitle) : $row->title), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
                <div class="olfileright"></div>
                <div class="clear"></div>
              </div>';
        }
      }
      $strOutput .= '
              <div class="clear"></div>
            </div>';
    }
    
    /**
     * list footer
     */
    $strOutputBottom = '
            <div>
              <div class="olfilebottomleft"></div>
              <div class="olfilebottomcenter"></div>
              <div class="olfilebottomright"></div>
              <div class="clear"></div>
            </div>';

    /**
     * return html output
     */
    if($strOutput != ''){
      if($blnIsImageView){
        return $strOutput.'<div class="clear"></div>';
      }else{
        return $strOutputTop.$strOutput.$strOutputBottom.'<div class="clear"></div>';
      }    	
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
              <div class="olcontacttopleft"></div>
              <div class="olcontacttopitemicon"></div>
              <div class="olcontacttopitemtitle bold">Titel</div>
              <div class="olcontacttopright"></div>
              <div class="clear"></div>
            </div>
            <div class="olcontactitemcontainer">';

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
          <div class="olcontactitem" id="olContactItem'.$row->id.'" onclick="myOverlay.addContactItemToListArea(\'olContactItem'.$row->id.'\', '.$row->id.'); return false;"'.$strHidden.'>
            <div class="olcontactleft"></div>
            <div style="display:none;" id="Remove'.$row->id.'" class="itemremovelist"></div>
            <div class="olcontactitemicon">';
      if($row->filename != ''){
        $strOutput .= '<img width="32" height="32" id="Contact'.$row->id.'" src="'.sprintf($this->core->sysConfig->media->paths->icon32, $row->filepath).$row->filename.'?v='.$row->fileversion.'" alt="'.$row->title.'" width="16" height="16"/>';
      }

      $strOutput .= '</div>
            <div class="olcontactitemtitle">'.$row->title.'</div>
            <div class="olcontactright"></div>
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
              <div class="olcontactbottomleft"></div>
              <div class="olcontactbottomcenter"></div>
              <div class="olcontactbottomright"></div>
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