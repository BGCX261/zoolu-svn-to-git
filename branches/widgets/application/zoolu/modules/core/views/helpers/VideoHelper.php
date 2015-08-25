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
 * @package    application.zoolu.modules.core.views.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * VideoHelper
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-03-04: Thomas Schedler
 * 1.1, 2009-07-30: Florian Mathis, Youtube Service
 * 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class VideoHelper {
  
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
   * getVideoTree 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getVideoSelect($objVideos, $mixedSelectedId, $strElementId) {
    $this->core->logger->debug('core->views->helpers->VideoHelper->getFolderTree()');
    
    $strOutput = '';
    
    $intCounter = 0;
    
    $strOutput .= '
          <div class="videoItem bg1" id="div_'.$strElementId.'_0" onclick="myForm.selectVideo(\''.$strElementId.'\', \'0\');"  >
            <div class="videoThumb"><img src="/zoolu/images/icons/icon_novideo.png" with="100" style="border-right:1px solid #ccc;"/></div>
            <input type="hidden" id="thumb_'.$strElementId.'_0" name="thumb_'.$strElementId.'_0" value=""/>
            <div class="videoInfos"><strong>Kein Video</strong></div>
            <div class="clear"></div>
          </div>';  
    
    foreach($objVideos as $objVideo){
     $intCounter++;
      
      // Vimeo Controller
      if($objVideos instanceof VimeoVideoList) {
	      $objThumbnails = $objVideo->getThumbnails();
	      $objThumbnail = current(current($objThumbnails));
	      
	      // Get Tags
	      $arrTags = array();
	      foreach($objVideo->getTags() as $objTag) {
	        $arrTags[] = $objTag->getTag();
	      }
	      
	      $strBgClass = ($intCounter % 2) ? ' bg2' : ' bg1';
	      $strSelected = ($objVideo->getID() === $mixedSelectedId) ? ' selected' : '';
	      
	      $strOutput .= '
	          <div class="videoItem'.$strBgClass.$strSelected.'" id="div_'.$strElementId.'_'.$objVideo->getID().'" onclick="myForm.selectVideo(\''.$strElementId.'\', \''.$objVideo->getID().'\');"  >
	            <div class="videoThumb"><img src="'.$objThumbnail->getContent().'" with="100"/></div>
	            <input type="hidden" id="thumb_'.$strElementId.'_'.$objVideo->getID().'" name="thumb_'.$strElementId.'_'.$objVideo->getID().'" value="'.$objThumbnail->getContent().'"/>
	            <div class="videoInfos">
	              <strong>'.$objVideo->getTitle().'</strong> <span class="gray666">('.date('d.m.Y H:i', $objVideo->getUploadTimestamp()).')</span><br/>
	              '.((count($arrTags) > 0) ? '<div class="smaller"><span class="gray666">Tags:</span> '.implode(', ', $arrTags).'</div>' : '').'
	            </div>
	            <div class="clear"></div>
	          </div>';   
      } 
      // Youtube Controller
      else {
      	$objThumbnails = $objVideo->getVideoThumbnails();
      	$arrThumbnail = current($objThumbnails);
      	$arrTags = $objVideo->getVideoTags();
      	
      	$strBgClass = ($intCounter % 2) ? ' bg2' : ' bg1';
	      $strSelected = ($objVideo->getVideoId() === $mixedSelectedId) ? ' selected' : '';
	      $strOutput .= '
	          <div class="videoItem'.$strBgClass.$strSelected.'" id="div_'.$strElementId.'_'.$objVideo->getVideoId ().'" onclick="myForm.selectVideo(\''.$strElementId.'\', \''.$objVideo->getVideoId ().'\');"  >
	            <div class="videoThumb"><img src="'.$arrThumbnail['url'].'" with="100"/></div>
	            <input type="hidden" id="thumb_'.$strElementId.'_'.$objVideo->getVideoId ().'" name="thumb_'.$strElementId.'_'.$objVideo->getVideoId ().'" value="'.$arrThumbnail['url'].'"/>
	            <div class="videoInfos">
	              <strong>'.$objVideo->getTitle().'</strong>';
	      
	      // Check if VideoRecorded Date isnt null
      	if($objVideo->getVideoRecorded() != null) {
      		$strVideoUploadDate = date('d.m.Y H:i', strtotime($objVideo->getVideoRecorded()));
      		$strOutput .= '<span class="gray666">('.$strVideoUploadDate.')</span>';
      	}
      	
	      $strOutput .='<br/>
	              '.((count($arrTags) > 0) ? '<div class="smaller"><span class="gray666">Tags:</span> '.implode(', ', $arrTags).'</div>' : '').'
	            </div>
	            <div class="clear"></div>
	          </div>';      
      }  
    }
    
    /**
     * return html output
     */
    return $strOutput;
  }
}

?>