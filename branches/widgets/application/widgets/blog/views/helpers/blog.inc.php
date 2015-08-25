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
 * @package    application.widgets.blog.views.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Blog Methods
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2010-01-22: Florian Mathis
 * 
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */

/**
 * get_tags
 * @return void
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_tags($objWidgetTag){
	if(isset($objWidgetTag)) {
		$tags = $objWidgetTag->getTags();
		// in css file, blogWidgetTag1-5
		$min_size = 1;
		$max_size = 5;
		
		$minimum_count = min(array_values($tags));
		$maximum_count = max(array_values($tags));
		$spread = $maximum_count - $minimum_count;
	
		if($spread == 0) {
			$spread = 1;
		}
		
		$cloud_html = '';
		$cloud_tags = array();
		
		foreach ($tags as $tag => $count) {
			$size = $min_size + ($count - $minimum_count)
			* ($max_size - $min_size) / $spread;
			
			$cloud_tags[] = '<a href="?t='.htmlspecialchars(stripslashes($tag)).'" class="blogWidgetTag'.floor($size).'">'
			. htmlspecialchars(stripslashes($tag)) . '</a>';
		}
		$cloud_html = join("\n", $cloud_tags) . "\n";
		
		echo $cloud_html;
	}
}

/**
 * getCoreObject
 * @return Core
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function getCoreObject(){
  return Zend_Registry::get('Core');  
}

/**
 * has_tags
 * @return boolean
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function has_tags($objWidgetTag){
	if($objWidgetTag != null) {
		if(count($objWidgetTag->getTags()) > 0){
	    return true;  
	  }else{
	    return false;
	  }
	}
}

/**
 * get_image_main
 * @param string $strImageFolder        define output folder 
 * @param boolean $blnZoom              set if image should be enlargeable
 * @param boolean $blnUseLightbox       set if image zoom should open in a lightbox
 * @param string $strImageFolderZoom    define folder for zoom
 * @param string $strContainerClass     css class for the div container
 * @return string $strHtmlOutput
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_image_main($strImageFolder = '420x', $blnZoom = true, $blnUseLightbox = true, $strImageFolderZoom = '660x', $strContainerClass = 'divMainImage'){
  $objCore = getCoreObject();
  $objWidget = getWidgetObject();
  $strHtmlOutput = '';
  
  $objFiles = $objWidget->loadFieldFiles($objWidget->getWidgetInstanceId());  
 
  if($objFiles != '' && count($objFiles) > 0){
    $strHtmlOutput .= '<div class="'.$strContainerClass.'">';   
    foreach($objFiles as $objFile){
    	$objFile = $objWidget->getFileFieldValueById($objFile['idFiles']);
      $strHtmlOutput .= '<img src="'.$objCore->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile[0]->filename.'" alt="'.$objFile[0]->title.'" title="'.$objFile[0]->title.'"/><br/>';
    }
    $strHtmlOutput .= '</div><div class="clear"></div>'; 
  } 
  
  echo $strHtmlOutput; 
}

/**
 * get_blog_text_blocks
 * @return boolean
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_blog_text_blocks($strImageFolder = '', $blnZoom = true, $blnUseLightbox = true, $strImageFolderZoom = '', $strContainerClass = 'divTextBlock', $strImageContainerClass = 'divImgLeft'){
	$objCore = getCoreObject();
	$objWidget = getWidgetObject();
	$strHtmlOutput = '';
	
	$arrMultiplyFields = $objWidget->loadMultiplyFields();
	foreach($arrMultiplyFields AS $field){
		$strBlockTitle = htmlentities($field['block_title'], ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
		
		$strHtmlOutput .= '<div class="'.$strContainerClass.'">';      
    $strHtmlOutput .= '  <h2>'.$strBlockTitle.'</h2>';
		
    $objFiles = $objWidget->loadMultiplyFieldFiles($field['id']); 
		if($objFiles != '' && count($objFiles) > 0){      
    	$strHtmlOutput .= '<div class="'.$strImageContainerClass.'">';
      foreach($objFiles as $objFile){
      	$objFile = $objWidget->getFileFieldValueById($objFile['idFiles']);
        $strHtmlOutput .= '<img src="'.$objCore->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile[0]->filename.'" alt="'.$objFile[0]->title.'" title="'.$objFile[0]->title.'"/><br/>';   
      }
      $strHtmlOutput .= '</div>';         
    }
    $strHtmlOutput .= $field['block_description'];
    $strHtmlOutput .= '<div class="clear"></div>';
    $strHtmlOutput .= '</div>'; 
	}
	
	echo $strHtmlOutput;
}

?>