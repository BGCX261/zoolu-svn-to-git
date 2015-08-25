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
 * Page output functions
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-09: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

/**
 * getPageObject
 * @return Page
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function getPageObject(){
  return Zend_Registry::get('Page');  
}

/**
 * getCoreObject
 * @return Core
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function getCoreObject(){
  return Zend_Registry::get('Core');  
}

/**
 * get_template_file
 * @param string strPrefix
 * @return string templateFilePath
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_template_file($strPrefix){
  return $strPrefix . getPageObject()->getTemplateFile();
}

/**
 * get_portal_title
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_portal_title(){
  echo getPageObject()->getRootLevelTitle();
}

/**
 * get_meta_keywords
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_meta_keywords(){
  $objPage = getPageObject();
  $objPageTags = $objPage->getTagsValues('page_tags');

  $strHtmlOutput = '';
  $strKeywords = '';
  if(count($objPageTags) > 0){
    $strHtmlOutput .= '<meta name="keywords" content="';
    foreach($objPageTags as $objTag){
      $strKeywords .= htmlentities($objTag->title, ENT_COMPAT, getCoreObject()->sysConfig->encoding->default).', ';
    }
    $strHtmlOutput .= trim($strKeywords, ', ').'"></meta>';  
  }
  echo $strHtmlOutput;  
}

/**
 * get_meta_description
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_meta_description(){
  $strHtmlOutput = '';
  if(getPageObject()->getFieldValue('shortdescription') != ''){
    $strHtmlOutput .= '<meta name="description" content="'.htmlentities(getPageObject()->getFieldValue('shortdescription'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default).'"></meta>';  
  }
  echo $strHtmlOutput;  
}

/**
 * get_zoolu_header
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_zoolu_header(){
  $strHtmlOutput = '';
  
  if(Zend_Auth::getInstance()->hasIdentity()){
    $strHtmlOutput .= '<div class="divModusContainer">
      <div class="divModusLogo">
        <a href="/zoolu/cms" target="_blank">
          <img src="/zoolu/images/modus/logo_zoolu_modus.gif" alt="ZOOLU" />
        </a>
      </div>
      <div class="divModusAdvice">Hinweis: Im Moment werden auch Seiten mit dem <strong>Status "Test"</strong> dargestellt.</div>
      <div class="divModusLogout"><a href="#">Abmelden</a></div>
      <div class="divModusStatus">Test/Live-Modus: 
        <select id="selTestMode" name="selTestMode" onchange="myDefault.changeTestMode(this.options[this.selectedIndex].value);">
          <option value="on" '.((isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == true) ? ' selected="selected"' : '').'>Aktiv</option>
          <option value="off" '.((isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == true) ? '' : ' selected="selected"').'>Inaktiv</option>
        </select>
      </div>
      <div class="clear"></div>
    </div>';  
  }
     
  echo $strHtmlOutput;
}

/**
 * get_elementId
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_elementId(){
  $strHtmlOutput = '';
  if(getPageObject()->getElementId() != ''){
    $strHtmlOutput = getPageObject()->getElementId();  
  }
  echo $strHtmlOutput;  
}

/**
 * get_title
 * @param string $strTag
 * @param boolean $blnTitleFallback
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_title($strTag = '', $blnTitleFallback = true){
  $strHtmlOutput = '';
  if(getPageObject()->getFieldValue('articletitle') != ''){
    if($strTag != '') $strHtmlOutput .= '<'.$strTag.'>';  
    $strHtmlOutput .= htmlentities(getPageObject()->getFieldValue('articletitle'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default); 
    if($strTag != '') $strHtmlOutput .= '</'.$strTag.'>';
  }
  else if(getPageObject()->getFieldValue('title') != '' && $blnTitleFallback){
    if($strTag != '') $strHtmlOutput .= '<'.$strTag.'>';
    $strHtmlOutput .= htmlentities(getPageObject()->getFieldValue('title'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
    if($strTag != '') $strHtmlOutput .= '</'.$strTag.'>';  
  }
  echo $strHtmlOutput;  
}

/**
 * get_description
 * @param string $strContainerClass
 * @param boolean $blnContainer
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_description($strContainerClass = 'divDescription', $blnContainer = true){
  $strHtmlOutput = '';
  if(getPageObject()->getFieldValue('description') != ''){
    if($blnContainer) $strHtmlOutput .= '<div class="'.$strContainerClass.'">';
    $strHtmlOutput .= getPageObject()->getFieldValue('description');
    if($blnContainer) $strHtmlOutput .= '</div>';  
  }
  echo $strHtmlOutput;
}

/**
 * get_abstract
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_abstract(){
  $strHtmlOutput = '';
  if(getPageObject()->getFieldValue('shortdescription') != ''){
    $strHtmlOutput .= htmlentities(getPageObject()->getFieldValue('shortdescription'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);  
  }
  echo $strHtmlOutput;  
}
  
/**
 * get_image_main
 * @param string $strImageFolder        define output folder 
 * @param boolean $blnZoom              set if image should be enlargeable
 * @param boolean $blnUseLightbox       set if image zoom should open in a lightbox
 * @param string $strImageFolderZoom    define folder for zoom
 * @param string $strContainerClass     css class for the div container
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_image_main($strImageFolder = '420x', $blnZoom = true, $blnUseLightbox = true, $strImageFolderZoom = '660x', $strContainerClass = 'divMainImage'){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $objFiles = $objPage->getFileFieldValue('mainpics');  
  
  $strHtmlOutput = '';
  
  if($objFiles != '' && count($objFiles) > 0){
    $strHtmlOutput .= '<div class="'.$strContainerClass.'">';   
    foreach($objFiles as $objFile){
      if($blnZoom){
        $strHtmlOutput .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$core->sysConfig->media->paths->imgbase.$strImageFolderZoom.'/'.$objFile->filename.'"';
        if($blnUseLightbox){
          $strHtmlOutput .= ' rel="lightbox[mainpics]"';  
        }
        $strHtmlOutput .= '>';  
      }
      $strHtmlOutput .= '<img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile->filename.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';  
      if($blnZoom){
        $strHtmlOutput .= '</a>';  
      }
    }
    $strHtmlOutput .= '</div>'; 
  } 
  echo $strHtmlOutput; 
}
  
/**
 * get_image_gallery_title
 * @param string $strElement = 'h3'
 * @param boolean $blnShowDefaultTitle = true
 * @return string $strImgageGalleryTitle
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_image_gallery_title($strElement = 'h3', $blnShowDefaultTitle = true){
  $objPage = getPageObject();

  $strImgageGalleryTitle = htmlentities($objPage->getFieldValue('pics_title'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
  if($strImgageGalleryTitle != ''){
    $strImgageGalleryTitle = '<'.$strElement.'>'.$strImgageGalleryTitle.'</'.$strElement.'>';
  }
  else if($blnShowDefaultTitle){
    $strImgageGalleryTitle = '<'.$strElement.'>Bildergalerie</'.$strElement.'>'; 
  }else{
    $strImgageGalleryTitle = '';
  }
  echo $strImgageGalleryTitle;
}
  
/**
 * get_image_gallery
 * @param integer $intLimitNumber         set a limit for the output (only show e.g. 10 images, the others are hidden)
 * @param string $strImageGalleryFolder   define output folder 
 * @param boolean $blnZoom                set if image should be enlargeable
 * @param boolean $blnUseLightbox         set if image zoom should open in a lightbox
 * @param string $strImageFolderZoom      define folder for zoom
 * @param string $strContainerClass       css class for the div container
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_image_gallery($intLimitNumber = 0, $strImageGalleryFolder = '', $blnZoom = true, $blnUseLightbox = true, $strImageFolderZoom = '', $strContainerClass = 'divGallery', $strThumbContainerClass = 'divGalleryItem'){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $objFiles = $objPage->getFileFieldValue('pics');  
  
  $strHtmlOutput = '';
  
  if($objFiles != '' && count($objFiles) > 0){
    $counter = 0;
    $strHtmlOutput .= '<div class="'.$strContainerClass.'">';    
    foreach($objFiles as $objFile){
      if($intLimitNumber > 0 && $counter == $intLimitNumber){
        $strHtmlOutput .= '
          <div id="divImageGalleryShowAll"><a onclick="myDefault.imgGalleryShowAll(\'divImageGalleryShowAll\'); return false;" href="#">Alle Bilder anzeigen</a></div>
          <div id="divImageGallery" style="display:none;">';        
      } 
      $strHtmlOutput .= '<div class="'.$strThumbContainerClass.'">';
      if($blnZoom){
        $strHtmlOutput .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$core->sysConfig->media->paths->imgbase.$strImageFolderZoom.'/'.$objFile->filename.'"';
        if($blnUseLightbox){
          $strHtmlOutput .= ' rel="lightbox[pics]"'; 
        }
        $strHtmlOutput .= '>'; 
      }
      $strHtmlOutput .= '<img src="'.$core->sysConfig->media->paths->imgbase.$strImageGalleryFolder.'/'.$objFile->filename.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';  
      if($blnZoom){
        $strHtmlOutput .= '</a>';  
      }
      $strHtmlOutput .= '</div>';
      if($counter >= $intLimitNumber && $counter == count($objFiles)-1){
        $strHtmlOutput .= '
            <div class="clear"></div>
          </div>';  
      } 
      $counter++;
    }
    $strHtmlOutput .= '
          <div class="clear"></div>
        </div>';      
  }
  echo $strHtmlOutput;
}

/**
 * has_image_gallery
 * @return boolean
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function has_image_gallery(){
  $objPage = getPageObject();  
  $objFiles = $objPage->getFileFieldValue('pics');  
  
  if($objFiles != '' && count($objFiles) > 0){
    return true;    
  }else{
    return false;
  }
}

/**
 * get_video_title
 * @param string $strElement
 * @param boolean $blnShowDefaultTitle
 * @return string $strVideoTitle
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_video_title($strElement = 'h3', $blnShowDefaultTitle = true){
  $objPage = getPageObject();

  $strVideoTitle = htmlentities($objPage->getFieldValue('video_title'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
  if($strVideoTitle != ''){
    $strVideoTitle = '<'.$strElement.'>'.$strVideoTitle.'</'.$strElement.'>';
  }else if($blnShowDefaultTitle){
    $strVideoTitle = '<'.$strElement.'>Video</'.$strElement.'>'; 
  }else{
    $strVideoTitle = '';
  }
  echo $strVideoTitle;
}
  
/**
 * get_video
 * @param integer $intVideoWidth
 * @param integer $intVideoHeight
 * @return string $strHtmlOutput 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_video($intVideoWidth = 420, $intVideoHeight = 236, $blnShowVideoTitle = true){
  $strHtmlOutput = '';
    
  $mixedVideoId = getPageObject()->getFieldValue('video');
  $arrFieldProperties = getPageObject()->getField('video')->getProperties();

  if($mixedVideoId != ''){   
    /*
     * Vimeo Service
     */
    if($arrFieldProperties['intVideoTypeId'] == '1') { 
    $strHtmlOutput .= '
               <div class="divVideoContainer"> 
                 <object width="'.$intVideoWidth.'" height="'.$intVideoHeight.'">
                    <param value="true" name="allowfullscreen"/>
                    <param value="always" name="allowscriptaccess"/>
                    <param value="http://vimeo.com/moogaloop.swf?clip_id='.$mixedVideoId.'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=bf000a&amp;fullscreen=1" name="movie"/>
                    <embed width="'.$intVideoWidth.'" height="'.$intVideoHeight.'" allowscriptaccess="always" allowfullscreen="true" type="application/x-shockwave-flash" src="http://vimeo.com/moogaloop.swf?clip_id='.$mixedVideoId.'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=bf000a&amp;fullscreen=1"></embed>
                  </object>
                </div>'; 
    } 
    /*
     * Youtube Service
     */
    else if($arrFieldProperties['intVideoTypeId'] == '2') {
      $strHtmlOutput .= '
        <div class="divVideoContainer"> 
          <object width="'.$intVideoWidth.'" height="'.$intVideoHeight.'">
            <param name="movie" value="http://www.youtube.com/v/'.$mixedVideoId.'"></param>
            <param name="allowFullScreen" value="true"></param>
              <embed src="http://www.youtube.com/v/'.$mixedVideoId.'"
                type="application/x-shockwave-flash"
                width="'.$intVideoWidth.'" height="'.$intVideoHeight.'" 
                allowfullscreen="true">
              </embed>
          </object>
        </div>';
    }
  }else if(getPageObject()->getFieldValue('video_embed_code') != ''){
    $strHtmlOutput .= '<div class="divVideoContainer">'.getPageObject()->getFieldValue('video_embed_code').'</div>';  
  } 
  
  echo (($blnShowVideoTitle) ? get_video_title('h3', false) : '').$strHtmlOutput;
}
  
/**
 * get_documents_title
 * @param string $strElement
 * @param boolean $blnShowDefaultTitle
 * @return string $strDocumentsTitle
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_documents_title($strElement = 'h3', $blnShowDefaultTitle = true){
  $objPage = getPageObject();
  
  $strDocumentsTitle = htmlentities($objPage->getFieldValue('docs_title'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
  if($strDocumentsTitle != ''){
    $strDocumentsTitle = '<'.$strElement.'>'.$strDocumentsTitle.'</'.$strElement.'>';
  }else if($blnShowDefaultTitle){
    $strDocumentsTitle = '<'.$strElement.'>Dokumente</'.$strElement.'>'; 
  }else{
    $strDocumentsTitle = '';  
  }
  echo $strDocumentsTitle;
}
  
/**
 * get_documents
 * @param string $strContainerCss
 * @param string $strIconCss
 * @param string $strTitleCss
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_documents($strContainerCss = 'divDocItem', $strIconCss = 'divDocIcon', $strTitleCss = 'divDocInfos'){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $objFiles = $objPage->getFileFieldValue('docs');

  $strHtmlOutput = '';
  
  if($objFiles != '' && count($objFiles) > 0){
    foreach($objFiles as $objFile){
      $strHtmlOutput .= '<div class="'.$strContainerCss.'">
              <div class="'.$strIconCss.'"><img src="/website/themes/default/images/icons/icon_'.$objFile->extension.'.gif" alt="'.$objFile->title.'" title="'.$objFile->title.'"/></div>
              <div class="'.$strTitleCss.'">
                <a href="'.$core->sysConfig->media->paths->docbase.$objFile->filename.'" target="_blank">'.$objFile->title.'</a><br/>
                <span>Format:</span> <span class="black">'.$objFile->extension.'</span> <span>Gr&ouml;&szlig;e:</span> <span class="black">'.$objFile->size.' KB</span>
              </div>
              <div class="clear"></div>
            </div>';
    }  
  }  
  echo $strHtmlOutput;
}

/**
 * has_documents
 * @return boolean
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function has_documents(){
  $objPage = getPageObject();  
  $objFiles = $objPage->getFileFieldValue('docs');  
  
  if($objFiles != '' && count($objFiles) > 0){
    return true;    
  }else{
    return false;
  }
}

/**
 * get_text_blocks
 * @param string $strImageFolder
 * @param boolean $blnZoom
 * @param boolean $blnUseLightbox
 * @param string $strImageFolderZoom
 * @param string $strContainerClass
 * @param string $strImageContainerClass
 * @param string $strDescriptionContainerClass
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_text_blocks($strImageFolder = '', $blnZoom = true, $blnUseLightbox = true, $strImageFolderZoom = '', $strContainerClass = 'divTextBlock', $strImageContainerClass = 'divImgLeft'){
  $core = getCoreObject();
  $objPage = getPageObject();

  $objMyMultiRegion = $objPage->getRegion(11); //11 is the default text block region
  
  $strHtmlOutput = '';
  
  if($objMyMultiRegion instanceof GenericElementRegion){
    foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
      
      $strBlockTitle = htmlentities($objMyMultiRegion->getField('block_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
      if($strBlockTitle != ''){
        $strHtmlOutput .= '<div class="'.$strContainerClass.'">';      
        $strHtmlOutput .= '  <h2>'.$strBlockTitle.'</h2>';
    
        $objFiles = $objPage->getFileFieldValueById($objMyMultiRegion->getField('block_pics')->getInstanceValue($intRegionInstanceId)); 
    
        if($objFiles != '' && count($objFiles) > 0){          
          $strHtmlOutput .= '<div class="'.$strImageContainerClass.'">';
          foreach($objFiles as $objFile){
            if($blnZoom && $strImageFolderZoom != ''){
              $strHtmlOutput .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$core->sysConfig->media->paths->imgbase.$strImageFolderZoom.'/'.$objFile->filename.'"';
              if($blnUseLightbox) $strHtmlOutput .= ' rel="lightbox[textblocks]"';
              $strHtmlOutput .= '>';             
            } 
            $strHtmlOutput .= '<img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile->filename.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';   
            if($blnZoom && $strImageFolderZoom != '') $strHtmlOutput .= '</a>';
          }
          $strHtmlOutput .= '</div>';         
        }
        $strHtmlOutput .= $objMyMultiRegion->getField('block_description')->getInstanceValue($intRegionInstanceId);
        $strHtmlOutput .= '<div class="clear"></div>';
        $strHtmlOutput .= '</div>'; 
      }
    } 
  }  
  echo $strHtmlOutput;   
}

/**
 * get_banner_static
 * @param string $strImageFolder
 * @param string $strContainerClass
 * @param string $strImageContainerClass
 * @param string $strTextContainerClass
 * @return string $strHtmlOutput  
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_banner_static($strImageFolder = '80x80', $strContainerClass = 'divAdRight', $strImageContainerClass = 'divAdImgLeft', $strTextContainerClass = 'divAdInfoSmall'){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $strBannerTitle = htmlentities($objPage->getFieldValue('banner_title'), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
  $strBannerDescription = $objPage->getFieldValue('banner_description');
  
  $objBannerFiles = $objPage->getFileFieldValue('banner_pics');  
  $strBannerImage = '';
  $counter = 0;
  if($objBannerFiles != '' && count($objBannerFiles) > 0){   
    foreach($objBannerFiles as $objFile){
      if($counter == 0){
        $strBannerImage .= '<img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile->filename.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';  
      }
      $counter++;
    }
  }
  
  $strHtmlOutput = '
                <div class="'.$strContainerClass.'">';
  if($strBannerImage != ''){
    $strHtmlOutput .= '
                  <div class="'.$strImageContainerClass.'">'.$strBannerImage.'</div>';  
  }
  $strHtmlOutput .= '
                  <div class="'.$strTextContainerClass.'">
                    <h2 class="adblock">'.$strBannerTitle.'</h2>
                    <div>'.$strBannerDescription.'</div>
                  </div>
                  <div class="clear"></div>
                </div>';  
 
  echo $strHtmlOutput;
}

/**
 * get_banner_dynamic
 * @param string $strImageFolder
 * @param string $strContainerClass
 * @param string $strImageContainerClass
 * @param string $strTextContainerClass
 * @return string $strHtmlOutput  
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_banner_dynamic($strImageFolder = '80x80', $strContainerClass = 'divAdLeftAbsolute', $strImageContainerClass = 'divAdImgLeft', $strTextContainerClass = 'divAdInfoBig'){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $objMyMultiRegion = $objPage->getRegion(11); //11 is the default text block region
   
  $strHtmlOutput = '';
  
  if($objMyMultiRegion instanceof GenericElementRegion){
    $counter = 0;
    foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
      
      $strBannerImage = '';
      $objFiles = $objPage->getFileFieldValueById($objMyMultiRegion->getField('block_pics')->getInstanceValue($intRegionInstanceId));
      $imgCounter = 0;
      if($objFiles != '' && count($objFiles) > 0){ 
        foreach($objFiles as $objFile){
          if($imgCounter == 0){
            $strBannerImage = '<img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile->filename.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';   
          }
          $imgCounter++;  
        }        
      }
      
      $strHtmlOutput .= '
              <div id="divDynBanner_'.$counter.'" class="'.$strContainerClass.'"';
      if($counter > 0){
        $strHtmlOutput .= ' style="left:618px"';
      }
      $strHtmlOutput .= '>';
      if($strBannerImage != ''){
        $strHtmlOutput .= '
                <div class="'.$strImageContainerClass.'">
                  '.$strBannerImage.'
                </div>';
      }
      $strHtmlOutput .= '
                <div class="'.$strTextContainerClass.'">
                  <h2 class="adblock">'.htmlentities($objMyMultiRegion->getField('block_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $core->sysConfig->encoding->default).'</h2>
                  <div>'.$objMyMultiRegion->getField('block_description')->getInstanceValue($intRegionInstanceId).'</div>
                </div>
                <div class="clear"></div>
              </div>';

      $counter++;
    }   
  }
  echo $strHtmlOutput;
}

/**
 * get_ad_blocks
 * @param string $strImageFolder
 * @param boolean $blnZoom = true 
 * @param boolean $blnUseLightbox = true
 * @param string $strImageFolderZoom
 * @param string $strContainerClassBase
 * @param string $strImageContainerClass
 * @param string $strDescriptionContainerClass
 * @return string $strHtmlOutput  
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_ad_blocks($strImageFolder = '', $blnZoom = true, $blnUseLightbox = true, $strImageFolderZoom = '', $strContainerClassBase = 'divTextBlock', $strImageContainerClass = 'divTextBlockImage', $strDescriptionContainerClass = 'divTextBlockDescription'){
  $core = getCoreObject();
  $objPage = getPageObject();

  $objMyMultiRegion = $objPage->getRegion(11); //11 is the default text block region
  
  $strHtmlOutput = '';
  
  if($objMyMultiRegion instanceof GenericElementRegion){
    
    $strHtmlOutput .= '
          <div class="divNormalPosts">
            <div class="divContentTop"></div>
            <div class="divContentMiddle">';
    
    $counter = 0;
    foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
      if($counter < 2){
        $strHtmlOutput .= '
                <div class="'.$strContainerClassBase.(($counter % 2 == 0) ? 'Left' : 'Right').'">';
                  
        $objFiles = $objPage->getFileFieldValueById($objMyMultiRegion->getField('block_pics')->getInstanceValue($intRegionInstanceId)); 
    
        if($objFiles != '' && count($objFiles) > 0){          
          $strHtmlOutput .= '
                  <div class="'.$strImageContainerClass.'">';
          foreach($objFiles as $objFile){
            if($blnZoom && $strImageFolderZoom != ''){
              $strHtmlOutput .= '<a href="'.$core->sysConfig->media->paths->imgbase.$strImageFolderZoom.'/'.$objFile->filename.'"';
              if($blnUseLightbox) $strHtmlOutput .= ' rel="lightbox[adblocks]"';
              $strHtmlOutput .= '>';             
            } 
            $strHtmlOutput .= '<img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objFile->filename.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';   
            if($blnZoom && $strImageFolderZoom != '') $strHtmlOutput .= '</a>';
          }
          $strHtmlOutput .= '</div>';         
        }
  
        $strHtmlOutput .= '
                  <div class="divAdInfo'.(($counter % 2 == 0) ? 'Big' : 'Small').'">
                    <h2 class="adblock">'.htmlentities($objMyMultiRegion->getField('block_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, getCoreObject()->sysConfig->encoding->default).'</h2>
                    <div class="'.$strDescriptionContainerClass.'">'.$objMyMultiRegion->getField('block_description')->getInstanceValue($intRegionInstanceId).'</div>
                  </div>
                  <div class="clear"></div>
                </div>';  
      }
      $counter++;
    }
    
    $strHtmlOutput .= '
              <div class="clear"></div>
            </div>
            <div class="divContentBottom"></div>
            <div class="clear"></div>
          </div>';
  }  
  echo $strHtmlOutput;   
} 

/**
 * get_overview
 * @param string $strImageFolder
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_overview($strImageFolderCol1 = '80x80', $strImageFolderCol2 = '180x', $strImageFolderList = '40x40'){
  $core = getCoreObject();
  $objPage = getPageObject();  
  
  $arrOverview = $objPage->getOverviewContainer();
  
  $strHtmlOutput = '';
  if(count($arrOverview) > 0){
    foreach($arrOverview as $key => $objPageContainer){
      if(count($objPageContainer) > 0){
        $strHtmlOutput .= '
              <h3>'.htmlentities($objPageContainer->getContainerTitle(), ENT_COMPAT, $core->sysConfig->encoding->default).'</h3>';
        
        $arrPageEntries = $objPageContainer->getEntries();
        
        switch($objPageContainer->getEntryViewType()){
          case $core->webConfig->viewtypes->col1->id:
            foreach($arrPageEntries as $objPageEntry){
              $strDescription = '';
              if($objPageEntry->shortdescription != ''){
                $strDescription = '<p>'.htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $core->sysConfig->encoding->default).'</p>'; 
              }else if($objPageEntry->description != ''){
                if(strlen($objPageEntry->description) > 200){
                  $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 200))).' ...'; 
                }else{
                  $strDescription = strip_tags($objPageEntry->description); 
                }   
              }
              
              $strHtmlOutput .= '
                <div class="divContentItem">
                  <h2><a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h2>';
              if($strDescription != ''){
                $strHtmlOutput .= $strDescription; 
              }
              $strHtmlOutput .= '
                  <a href="'.$objPageEntry->url.'">Weiter lesen...</a>
                  <div class="clear"></div>
                </div>';              
            }
            break; 
                    
          case $core->webConfig->viewtypes->col1_img->id:
            foreach($arrPageEntries as $objPageEntry){
              $strDescription = '';
              if($objPageEntry->shortdescription != ''){
                $strDescription = '<p>'.htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $core->sysConfig->encoding->default).'</p>'; 
              }else if($objPageEntry->description != ''){
                if(strlen($objPageEntry->description) > 200){
                  $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 200))).' ...'; 
                }else{
                  $strDescription = strip_tags($objPageEntry->description); 
                }   
              }
              
              $strHtmlOutput .= '
                <div class="divContentItem">
                  <h2><a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h2>';
              if($objPageEntry->filename != ''){
                $strHtmlOutput .= '    
                  <div class="divImgLeft">
                    <a href="'.$objPageEntry->url.'">
                      <img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolderCol1.'/'.$objPageEntry->filename.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/>
                    </a>
                  </div>';  
              }
              if($strDescription != ''){
                $strHtmlOutput .= $strDescription; 
              }
              $strHtmlOutput .= '                
                  <a href="'.$objPageEntry->url.'">Weiter lesen...</a>
                  <div class="clear"></div>
                </div>';              
            }
            break;
            
          case $core->webConfig->viewtypes->col2->id:
            
            $strHtmlOutput .= '             
                <div class="divColContainer">';                
          
            $counter = 0;
            foreach($arrPageEntries as $objPageEntry){            
              $strDescription = '';
              if($objPageEntry->shortdescription != ''){
                $strDescription = '<p>'.htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $core->sysConfig->encoding->default).'</p>'; 
              }else if($objPageEntry->description != ''){
                if(strlen($objPageEntry->description) > 100){
                  $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 100))).' ...'; 
                }else{
                  $strDescription = strip_tags($objPageEntry->description); 
                }   
              }
              
              $strHtmlOutput .= '
                  <div class="divColItem left'.(($counter % 2 == 0) ? ' pright20' : '').'">
                    <h2><a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h2>';
              if($strDescription != ''){
                $strHtmlOutput .= $strDescription; 
              }
              $strHtmlOutput .= '
                    <a href="'.$objPageEntry->url.'">Weiter lesen...</a>
                  </div>';
              if($counter % 2 == 1){
                $strHtmlOutput .= '
                  <div class="clear"></div>'; 
              }             
              $counter++;
            }         
            $strHtmlOutput .= '
                  <div class="clear"></div>                              
                </div>';
            break;
            
          case $core->webConfig->viewtypes->col2_img->id:
            $strHtmlOutput .= '             
                <div class="divColContainer">';                
          
            $counter = 0;
            foreach($arrPageEntries as $objPageEntry){            
              $strDescription = '';
              if($objPageEntry->shortdescription != ''){
                $strDescription = '<p>'.htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $core->sysConfig->encoding->default).'</p>'; 
              }else if($objPageEntry->description != ''){
                if(strlen($objPageEntry->description) > 100){
                  $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 100))).' ...'; 
                }else{
                  $strDescription = strip_tags($objPageEntry->description); 
                }   
              }
              
              $strHtmlOutput .= '
                  <div class="divColItem left'.(($counter % 2 == 0) ? ' pright20' : '').'">
                    <h2 class="padding10"><a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h2>';      
              if($objPageEntry->filename != ''){
                $strHtmlOutput .= '    
                  <div class="divImgLeft">
                    <a href="'.$objPageEntry->url.'">
                      <img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolderCol2.'/'.$objPageEntry->filename.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/>
                    </a>
                  </div>';  
              }
              if($strDescription != ''){
                $strHtmlOutput .= $strDescription; 
              }
              $strHtmlOutput .= '
                    <a href="'.$objPageEntry->url.'">Weiter lesen...</a>
                  </div>';
              if($counter % 2 == 1){
                $strHtmlOutput .= '
                  <div class="clear"></div>'; 
              }              
              $counter++;
            }
          
            $strHtmlOutput .= '
                  <div class="clear"></div>                              
                </div>';
            break;
            
          case $core->webConfig->viewtypes->list->id:
            
            $strHtmlOutput .= '
                <div class="divListContainer">';
            foreach($arrPageEntries as $objPageEntry){
              $strHtmlOutput .= '
                  <div class="divListItem">
                    <a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a>
                  </div>';
            }       
            $strHtmlOutput .= '
                </div>';
            break;
            
          case $core->webConfig->viewtypes->list_img->id:
            
            $strHtmlOutput .= '
                <div class="divListContainer">';
            foreach($arrPageEntries as $objPageEntry){
              $strHtmlOutput .= '
                  <div class="divListItemImg">';
              if($objPageEntry->filename != ''){
                $strHtmlOutput .= '
                    <div class="divListItemImgLeft">
                      <a href="'.$objPageEntry->url.'"><img src="'.$core->sysConfig->media->paths->imgbase.$strImageFolderList.'/'.$objPageEntry->filename.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/></a>
                    </div>';
              }              
              $strHtmlOutput .= '
                    <div class="divListItemImgRight">
                      <a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a>
                    </div>
                    <div class="clear"></div>
                  </div>';
            }
                      
            $strHtmlOutput .= '
                  <div class="clear"></div>
                </div>';
            break;
        }
      } 
    } 
  }  
  echo $strHtmlOutput;
} 

/**
 * get_video_overview
 * @param integer $intVideoBigWidth
 * @param integer $intVideoBigHeight
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_video_overview($intVideoBigWidth = 640, $intVideoBigHeight = 360){
  $core = getCoreObject();
  $objPage = getPageObject();  
  
  $objPages = $objPage->getPagesByCategory();
  
  $strHtmlOutput = '';
  $strTopMainHtmlOutput = '';
  $strdivTopSidebarItemsHtmlOutput = '';
  
  if(count($objPages) > 0){
    $counter = 0;
    $prevPageId = '';
    foreach($objPages as $objPageData){
      //$objPage->setCreateDate($objPageData->created);
      $objPage->setPublishDate($objPageData->published);
      $objPageInstanceData = $objPage->getPageInstanceDataById($objPageData->id, $objPageData->genericFormId.'-'.$objPageData->genericFormVersion);
      
      if($objPageData->pageId != $prevPageId){
        if($counter == 0){ 
          if(count($objPageInstanceData) > 0){
            if($objPageData->videoId != '' || $objPageInstanceData->video_embed_code != ''){
              $strTopMainHtmlOutput .= '    
                <div class="divTopMain">
                  <h1 class="padding0"><a href="'.$objPageInstanceData->url.'">'.htmlentities($objPageData->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h1>
                  <div>
                    <span>Ver&ouml;ffentlicht am</span> <span class="black">'.$objPage->getPublishDate().'</span>'.((isset($objPageData->catTitle) && ($objPageData->catTitle != ''))? ' <span>in der Kategorie</span> <span class="black">'.htmlentities($objPageData->catTitle, ENT_COMPAT, $core->sysConfig->encoding->default).'</span>' : '').'      
                  </div>
                  <div class="divTopVideo">';
              if($objPageData->videoId != ''){
                $strTopMainHtmlOutput .= '
                    <object width="'.$intVideoBigWidth.'" height="'.$intVideoBigHeight.'">
                      <param value="true" name="allowfullscreen"/>
                      <param value="always" name="allowscriptaccess"/>
                      <param value="http://vimeo.com/moogaloop.swf?clip_id='.$objPageData->videoId.'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=bf000a&amp;fullscreen=1" name="movie"/>
                      <embed width="'.$intVideoBigWidth.'" height="'.$intVideoBigHeight.'" allowscriptaccess="always" allowfullscreen="true" type="application/x-shockwave-flash" src="http://vimeo.com/moogaloop.swf?clip_id='.$objPageData->videoId.'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=bf000a&amp;fullscreen=1"></embed>
                    </object>'; 
              }else{
                $strTopMainHtmlOutput .= $objPageInstanceData->video_embed_code;  
              }           
              $strTopMainHtmlOutput .= '  
                  </div>
                </div>';    
            }
          }
        }else{
          if(count($objPageInstanceData) > 0){
            if($objPageData->videoId != '' || $objPageInstanceData->video_embed_code != ''){
              $strdivTopSidebarItemsHtmlOutput .= '
                        <div class="divTopSidebarItem">
                          <div class="divTopSidebarItemText">
                            <a href="'.$objPageInstanceData->url.'">'.((strlen($objPageData->title) > 23) ? htmlentities(substr($objPageData->title, 0, 20).'...', ENT_COMPAT, $core->sysConfig->encoding->default) : htmlentities($objPageData->title, ENT_COMPAT, $core->sysConfig->encoding->default)).'</a>
                            <div class="divTopSidebarItemDate">Ver&ouml;ffentlicht am <span class="black">'.$objPage->getPublishDate().'</span></div>';
              if(isset($objPageData->catTitle) && ($objPageData->catTitle != '')){
                $strdivTopSidebarItemsHtmlOutput .= '
                            <div class="divTopSidebarItemCat">Kategorie: <span class="black">'.htmlentities($objPageData->catTitle, ENT_COMPAT, $core->sysConfig->encoding->default).'</span></div>';
              }              
              $strdivTopSidebarItemsHtmlOutput .= '
                          </div>';
              if($objPageData->thumb != ''){
                $strdivTopSidebarItemsHtmlOutput .= '
                          <div class="divTopSidebarItemThumb">
                            <a href="'.$objPageInstanceData->url.'">
                              <img src="'.$objPageData->thumb.'" width="100" title="'.htmlentities($objPageData->title, ENT_COMPAT, $core->sysConfig->encoding->default).'" alt="'.htmlentities($objPageData->title, ENT_COMPAT, $core->sysConfig->encoding->default).'"/>
                            </a>
                          </div>';  
              }
              $strdivTopSidebarItemsHtmlOutput .= '
                          <div class="clear"></div>
                        </div>';
            }
          } 
        }
      }
      $prevPageId = $objPageData->pageId;   
      $counter++;
    }
    $strHtmlOutput .= $strTopMainHtmlOutput;
    
    if($strdivTopSidebarItemsHtmlOutput != ''){
      $strHtmlOutput .= ' 
            <div class="divTopSidebar">
              <div id="divSidebarUp" class="divSidebarArrows">
                <div onclick="myDefault.listScrollUp(); return false;"></div>
              </div>
              <div class="divSidebarItemListOuter">                
                <div id="divSidebarList" class="divSidebarItemListInner" style="top:0px;">';
      $strHtmlOutput .= $strdivTopSidebarItemsHtmlOutput;
      $strHtmlOutput .= '  
                  <div class="clear"></div>
                </div>
              </div>
              <div id="divSidebarDown" class="divSidebarArrows">
                <div onclick="myDefault.listScrollDown(); return false;"></div>
              </div>
              <div class="clear"></div>
            </div>';  
    }
  }
  
  echo $strHtmlOutput;
}

/**
 * get_pages_overview
 * @param string $strImageFolder
 * @param string $strThumbImageFolder
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_pages_overview($strImageFolder = '80x80', $strThumbImageFolder = '40x40'){
  $core = getCoreObject();
  $objPage = getPageObject();  
  
  $arrPagesOverview = $objPage->getPagesContainer();
  
  $strHtmlOutput = '';
  if(count($arrPagesOverview) > 0){
    foreach($arrPagesOverview as $key => $objPageContainer){
      if(count($objPageContainer) > 0){
        
        $strCssClassPostfix = '';
        if($key < 2){
          $strCssClassPostfix = ' pright20'; 
        }
        
        if($key < 3){
          
          $strHtmlOutput .= '
               <div class="col3'.$strCssClassPostfix.'">
                  <h3>'.htmlentities($objPageContainer->getContainerTitle(), ENT_COMPAT, $core->sysConfig->encoding->default).'</h3>';
          
          $arrPageEntries = $objPageContainer->getEntries();
          
          $strTopPostHtmlOutput = '';
          $strLinkItemsHtmlOutput = '';
          
          if(count($arrPageEntries) > 0){
            $counter = 0;
            foreach($arrPageEntries as $objPageEntry){
              if($counter == 0){

                $strTopPostHtmlOutput .= '
                  <div class="divTopPost">
                    <h2><a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h2>';
                if($objPageEntry->filename != ''){
                  $strTopPostHtmlOutput .= '
                   <div class="divImgLeft">
                     <img alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'" src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objPageEntry->filename.'"/>
                   </div>'; 
                }
                $strTopPostHtmlOutput .= '        
                    '.(($objPageEntry->shortdescription != '') ? '<p>'.htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $core->sysConfig->encoding->default).'</p>' : $objPageEntry->description).'
                    <a href="'.$objPageEntry->url.'">Weiter lesen...</a>
                  </div>';
                
              }else{
                $objPage->setCreateDate($objPageEntry->created);
                
                $strLinkItemsHtmlOutput .= '    
                    <div class="divListItemImg">';
                if($objPageEntry->filename != ''){
                  $strLinkItemsHtmlOutput .= '
                      <div class="divListItemImgLeft">
                        <a href="'.$objPageEntry->url.'"><img title="'.$objPageEntry->filetitle.'" alt="'.$objPageEntry->filetitle.'" src="'.$core->sysConfig->media->paths->imgbase.$strThumbImageFolder.'/'.$objPageEntry->filename.'"/></a>
                      </div>';  
                }               
                $strLinkItemsHtmlOutput .= '
                      <div class="divListItemImgRight">
                        <a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a><br/>
                        <span>Erstellt am</span> <span class="black">'.$objPage->getCreateDate().'</span>
                      </div>
                      <div class="clear"></div>
                    </div>';
              }             
              $counter++;
            } 
          }
          
          $strHtmlOutput .= $strTopPostHtmlOutput;
          if($strLinkItemsHtmlOutput != ''){
            $strHtmlOutput .= '
                <div class="divListContainer">
                  <h3>Weitere Themen</h3>';
            $strHtmlOutput .= $strLinkItemsHtmlOutput;
            $strHtmlOutput .= '
                  <div class="clear"></div>
                </div>';  
          }     
          $strHtmlOutput .= '
                <div class="clear"></div>
              </div>';
        }
      }
    }
  }

  echo $strHtmlOutput;  
}

/**
 * get_sidebar_blocks
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_sidebar_blocks($strContainerClass = 'divSidebarContainer'){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $objMyMultiRegion = $objPage->getRegion(14); //14 is the default sidebar block region with block_title and block_description
  
  $strHtmlOutput = '';
  
  if($objMyMultiRegion instanceof GenericElementRegion){
    if(count($objMyMultiRegion->RegionInstanceIds()) > 0){
      $counter = 0;
      foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
        $strBlockTitle = htmlentities($objMyMultiRegion->getField('block_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $core->sysConfig->encoding->default);
        $strBlockDescription = $objMyMultiRegion->getField('block_description')->getInstanceValue($intRegionInstanceId);
        $counter++;
        if(!($strBlockTitle == '' && $strBlockDescription == '')){
          if($counter == 1) $strHtmlOutput .= '<div class="'.$strContainerClass.'">';
          $strHtmlOutput .= '<h3>'.$strBlockTitle.'</h3>';        
          $strHtmlOutput .= $strBlockDescription;
          if($counter == count($objMyMultiRegion->RegionInstanceIds())) $strHtmlOutput .= '</div>';
        }
      }
    }
  }  
  echo $strHtmlOutput;   
}

/**
 * get_events_overview_header
 * @param integer $intQuarter
 * @param integer $intYear
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_events_overview_header($intQuarter = 0, $intYear = 0, $blnReturn = false){
  $timestamp = time();
  $year = ($intYear > 0) ? $intYear : date('Y', $timestamp);
  $quarter = ($intQuarter > 0 && $intQuarter <= 4) ? $intQuarter : ceil(date('m', $timestamp) / 3);
    
  $arrQuarterText = array(1 => 'J?nner '.$year.' bis M?rz '.$year,
                          2 => 'April '.$year.' bis Juni '.$year,
                          3 => 'Juli '.$year.' bis September '.$year,
                          4 => 'Oktober '.$year.' bis Dezember '.$year);
  
  $strHtmlOutput = $arrQuarterText[$quarter];
  if($blnReturn){
    return $strHtmlOutput;  
  }else{
    echo $strHtmlOutput;  
  }
}

/**
 * get_events_overview
 * @param integer $intQuarter
 * @param integer $intYear
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_events_overview($strImageFolder = '80x80', $intQuarter = 0, $intYear = 0){
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $arrDaysShort = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
  
  $timestamp = time();
  $intCurrQuarter = ($intQuarter > 0 && $intQuarter <= 4) ? $intQuarter : ceil(date('m', $timestamp) / 3);
  $intCurrYear = ($intYear > 0) ? $intYear : date('Y', $timestamp);
    
  $arrEvents = $objPage->getEventsContainer($intCurrQuarter, $intCurrYear);

  $strHtmlOutput = '
              <div id="divEventCalList_Q'.$intCurrQuarter.'_'.$intCurrYear.'" style="position:absolute; z-index:10; top:0; left:0; width:678px;">
                <div id="divLoader_Q'.$intCurrQuarter.'_'.$intCurrYear.'" style="display:none; position: absolute; top:0; left:0; width:678px; height:100%; background-color:#fff;"></div>';
  
  if(count($arrEvents) > 0){    
    foreach($arrEvents as $key => $objPageContainer){
      if(count($objPageContainer) > 0){
        
        $arrEventEntries = $objPageContainer->getEntries();
        
        foreach($arrEventEntries as $objEventEntry){
          $datetime = strtotime($objEventEntry->datetime);
          
          $strDescription = '';
          if($objEventEntry->shortdescription != ''){
            $strDescription = htmlentities($objEventEntry->shortdescription, ENT_COMPAT, $core->sysConfig->encoding->default); 
          }else if($objEventEntry->description != ''){
            if(strlen($objEventEntry->description) > 120){
              $strDescription = strip_tags(substr($objEventEntry->description, 0, strpos($objEventEntry->description, ' ', 120))).' ...'; 
            }else{
              $strDescription = strip_tags($objEventEntry->description); 
            }   
          }

          $strEventStatus = '';
          if($objEventEntry->event_status == $core->webConfig->eventstatus->full->id){
            $strEventStatus = '
                    <div class="divEventCalItemShortInfo smaller">Leider keine Pl&auml;tze mehr verf&uuml;gbar.</div>
            ';  
          }else if($objEventEntry->event_status == $core->webConfig->eventstatus->rest->id){
            $strEventStatus = '
                    <div class="divEventCalItemShortInfo smaller">Achtung: Nur noch wenige Restpl&auml;tze verf&uuml;gbar.</div>
                    <a href="'.$objEventEntry->url.'" class="red smaller">Jetzt Anmelden!</a>'; 
          }else{
            $strEventStatus = '
                    <a href="'.$objEventEntry->url.'" class="red smaller">Jetzt Anmelden!</a>'; 
          }
          
          $strHtmlOutput .= '
                <div class="divEventCalItem">
                  <div class="divEventCalItemLeft">
                    <div class="divEventCalItemDateBoxTop"></div>
                    <div class="divEventCalItemDateBoxMiddle">
                      <div class="divEventCalDate">'.$arrDaysShort[date('w', $datetime)].', '.date('d.m.', $datetime).'</div>
                      <div class="divEventCalTime">Beginn: '.date('H:i', $datetime).' Uhr</div>
                    </div>
                    <div class="divEventCalItemDateBoxBottom"></div>
                    <div class="clear"></div>
                  </div>
                  <div class="divEventCalItemCenter">
                    <div class="divEventCalItemText">                      
                      <h2 class="padding0"><a href="'.$objEventEntry->url.'">'.htmlentities($objEventEntry->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a></h2>
                      '.$strDescription.'
                      <div><a href="'.$objEventEntry->url.'">Mehr Informationen</a></div>
                    </div>';
          if($objEventEntry->filename != ''){
            $strHtmlOutput .= '
                      <div class="divEventCalItemImage">
                        <a href="'.$objEventEntry->url.'">
                          <img title="'.$objEventEntry->filetitle.'" alt="'.$objEventEntry->filetitle.'" src="'.$core->sysConfig->media->paths->imgbase.$strImageFolder.'/'.$objEventEntry->filename.'"/>
                        </a>
                      </div>';
          }        
          $strHtmlOutput .= '    
                    <div class="clear"></div>
                  </div>
                  <div class="divEventCalItemRight">
                    '.$strEventStatus.'
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </div>';  
          
        } 
      }
    }
  }else{
      $strHtmlOutput .= '<div class="divEventCalListEmpty">In diesem Zeitraum finden keine Veranstaltungen statt.</div>';  
  }
  
  $strHtmlOutput .= '
                <div class="clear"></div>
                <script type="text/javascript">//<![CDATA[
                  document.observe(\'dom:loaded\', function() {
                    myDefault.currQ = '.$intCurrQuarter.';
                    myDefault.currYear = '.$intCurrYear.';
                    myDefault.getCurrEventListHeight(\'divEventCalList_Q'.$intCurrQuarter.'_'.$intCurrYear.'\');
                  });
                  //]]>
                </script>
                <div id="divQuarterHeadline_Q'.$intCurrQuarter.'_'.$intCurrYear.'" style="display:none;">'.get_events_overview_header($intCurrQuarter, $intCurrYear, true).'</div>
              </div>';
  
  echo $strHtmlOutput;   
}

/**
 * get_event_datetime
 * @param boolean $blnDate
 * @param boolean $blnTime
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_event_datetime($blnDate = true, $blnTime = true){
  $timestamp = strtotime(getPageObject()->getFieldValue('datetime'));
  $strHtmlOutput = '';
  if($blnDate && !$blnTime){
    $strHtmlOutput = date('d.m.Y', $timestamp); 
  }else if($blnTime && !$blnDate){
    $strHtmlOutput = date('H:i', $timestamp); 
  }else if($blnTime && $blnDate){
    $strHtmlOutput = date('d.m.Y, H:i', $timestamp);  
  }  
  echo $strHtmlOutput;
}

/**
 * get_event_date
 * @param boolean $blnDay
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_event_date($blnDay = true, $strHeadline = ''){
  $arrDaysShort = array('So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa');
  
  $timestamp = strtotime(getPageObject()->getFieldValue('datetime'));
  $strHtmlOutput = '';
  if($timestamp != ''){
    $strHtmlOutput .= '
                  <h3 class="padding0">'.$strHeadline.'</h3>
                  <div class="divEventDate">'.$arrDaysShort[date('w', $timestamp)].', '.date('d.m.Y', $timestamp).'</div>';  
  }
  echo $strHtmlOutput;
}

/**
 * get_event_time
 * @param string $strHeadline
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_event_time($strHeadline = ''){
  $timestamp = strtotime(getPageObject()->getFieldValue('datetime'));
  $strHtmlOutput = '';
  if($timestamp != ''){
    $strHtmlOutput .= '
                <div class="divEventInfoItem">
                  <h3 class="padding0">'.$strHeadline.'</h3>
                  <div>'.date('H:i', $timestamp).' Uhr ('.getPageObject()->getFieldValue('event_duration').')</div>
                </div>';  
  }
  echo $strHtmlOutput;
}

/**
 * get_event_status
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_event_status(){  
  $core = getCoreObject();
  $intStatus = getPageObject()->getFieldValue('event_status');
  
  $strHtmlOutput = '';
  if(isset($intStatus) && $intStatus > 0){
    if($intStatus == $core->webConfig->eventstatus->rest->id){
      $strHtmlOutput .= '
                  <div class="bold smaller padding5">Achtung: Nur noch wenige Restpl&auml;tze verf&uuml;gbar.</div>
                  <a class="smaller red bold" href="#" onclick="myDefault.toggleElement(\'divEventForm\', 0.5); return false;">Jetzt Anmelden!</a>';  
    }else if($intStatus == $core->webConfig->eventstatus->full->id){
      $strHtmlOutput .= '
                  <div class="bold smaller padding5">Leider sind keine Pl&auml;tze mehr verf&uuml;gbar.</div>
                  <script type="text/javascript">//<![CDATA[ 
                    document.observe(\'dom:loaded\', function() {
                      if($(\'divEventRegisterButton\')) $(\'divEventRegisterButton\').hide();
                    }); 
                    //]]>
                  </script>'; 
    }
  }
  echo $strHtmlOutput;
}

/**
 * get_event_max_members
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_event_max_members($strHeadline = ''){
  $strFieldValue = getPageObject()->getFieldValue('event_max_members');
  $strHtmlOutput = '';
  if($strFieldValue != ''){
    $strHtmlOutput .= '
                <div class="divEventInfoItem">
                  <h3 class="padding0">'.$strHeadline.'</h3>
                  <div>'.$strFieldValue.' Person'.(($strFieldValue != '1' || $strFieldValue != '01') ? 'en' : '').'</div>
                </div>';  
  }
  echo $strHtmlOutput;  
}

/**
 * get_event_costs
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_event_costs($strHeadline = ''){
  $strFieldValue = getPageObject()->getFieldValue('event_costs');
  $strHtmlOutput = '';
  if($strFieldValue != ''){
    $strHtmlOutput .= '
                <div class="divEventInfoItem">
                  <h3 class="padding0">'.$strHeadline.'</h3>
                  <div>&euro; '.$strFieldValue.'</div>
                </div>';  
  }
  echo $strHtmlOutput;   
}

function get_event_address($strHeadline = ''){
  $strHtmlOutput = '
                <div class="divEventInfoItem">
                  <h3 class="padding0">'.$strHeadline.'</h3>
                  <div>'.getPageObject()->getFieldValue('event_location').'</div>
                  <div>'.getPageObject()->getFieldValue('event_street').' '.getPageObject()->getFieldValue('event_streetnr').'</div>
                  <div>'.getPageObject()->getFieldValue('event_plz').' '.getPageObject()->getFieldValue('event_city').'</div>
                  <div id="divEventLocationMap" style="width: 200px; height: 130px; overflow:hidden; margin:5px 0 5px 0;"></div>
                  <a class="smaller" target="_blank" href="http://maps.google.com/maps?q='.getPageObject()->getFieldValue('event_street').'+'.getPageObject()->getFieldValue('event_streetnr').'+'.getPageObject()->getFieldValue('event_plz').'+'.getPageObject()->getFieldValue('event_city').'">Detailierte Karte</a>
                  <script type="text/javascript">//<![CDATA[
                    var eventLocation = \''.getPageObject()->getFieldValue('event_street').' '.getPageObject()->getFieldValue('event_streetnr').','.getPageObject()->getFieldValue('event_plz').','.getPageObject()->getFieldValue('event_city').'\'; 
                    document.observe(\'dom:loaded\', function() {
                      myDefault.initGmap();
                      myDefault.showAddress(eventLocation);
                    });
                    //]]>
                  </script>
                </div>';
  echo $strHtmlOutput;  
}

/**
 * get_speakers
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_speakers($strThumbImageFolder = '80x80', $strZoomImageFolder = '420x', $strContainerClass = 'divPresenters'){
  $core = getCoreObject();
  $objPage = getPageObject();
  $objPageContacts = $objPage->getContactsValues('speakers');
  
  $strHtmlOutput = '';
  
  if(count($objPageContacts) > 0){
    $strHtmlOutput .= '
              <div class="'.$strContainerClass.'">
                <h3>Vortragende</h3>';
    
    foreach($objPageContacts as $objContact){
      $strHtmlOutput .= '
                <div class="divPresenterBlock">';
      if($objContact->filename != ''){
        $strHtmlOutput .= '
                  <div class="divPresenterImage">
                    <a title="'.$objContact->title.'" href="'.$core->sysConfig->media->paths->imgbase.$strZoomImageFolder.'/'.$objContact->filename.'" rel="lightbox[speakers]">
                      <img src="'.$core->sysConfig->media->paths->imgbase.$strThumbImageFolder.'/'.$objContact->filename.'" alt="'.$objContact->title.'" title="'.$objContact->title.'"/>  
                    </a>
                  </div>';  
      }
      $strHtmlOutput .= '
                  <div class="divPresenterText">
                    <h2 class="padding10">'.(($objContact->acTitle != '') ? $objContact->acTitle.' ' : '').$objContact->title.'</h2>
                    <div class="divPresenterDescription">';
      if($objContact->website != ''){
        $strHtmlOutput .= '<div><a href="'.((strpos($objContact->website, 'http://') > -1) ? $objContact->website : 'http://'.$objContact->website).'" target="_blank">'.$objContact->website.'</a></div>'; 
      }
      if($objContact->email != ''){
        $strHtmlOutput .= '<div><a href="mailto:'.$objContact->email.'">E-Mail senden</a></div>'; 
      }
      $strHtmlOutput .= '
                    </div>
                  </div>
                  <div class="clear"></div>
                </div>';
    }
    $strHtmlOutput .= '       
                <div class="clear"></div>
              </div>';  
  }
  echo $strHtmlOutput;
}

/**
 * get_contact
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_contacts($strThumbImageFolder = '40x40', $strContainerClass = 'divContactContainer', $strBigContactImageFolder = '180x'){
  $core = getCoreObject();
  $objPage = getPageObject();
  $objPageContacts = $objPage->getContactsValues('contact');  
  
  $arrContactOptions = array();
  if(is_array($objPage->getFieldValue('contact_option'))){
    $arrContactOptions = $objPage->getFieldValue('contact_option'); 
  }
  
  $strHtmlOutput = '';
    
  if(count($objPageContacts) > 0){
    foreach($objPageContacts as $objContact){
      if(in_array($core->webConfig->contactoptions->big_contact->id, $arrContactOptions) && ($objContact->filename != '')){
        $strHtmlOutput .= '
              <div class="'.$strContainerClass.'">
                <h3>Ihr Ansprechpartner</h3>
                <p>Haben Sie Fragen zu diesem Artikel? Wir freuen uns von ihnen zu h&ouml;ren. Ihre Kontaktperson ist:</p>';
        $strHtmlOutput .= '<div class="pbottom10"><img src="'.$core->sysConfig->media->paths->imgbase.$strBigContactImageFolder.'/'.$objContact->filename.'" alt="'.$objContact->title.'" title="'.$objContact->title.'"/></div>';
        $strHtmlOutput .= '         
                <div class="divContactPerson">
                  <div class="divContactName">'.(($objContact->acTitle != '') ? $objContact->acTitle.' ' : '').$objContact->title.'</div>
                  <div class="divContactFunc">'.$objContact->position.'</div>
                  <div class="divContactDetail">
                    <div class="divContactInfos">
                      '.$objContact->phone.'<br/>
                      <a href="mailto:'.$objContact->email.'">E-Mail senden</a>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
              </div>';  
      }else{
        $strHtmlOutput .= '
              <div class="'.$strContainerClass.'">
                <h3>Ihr Ansprechpartner</h3>
                <p>Haben Sie Fragen zu diesem Artikel? Wir freuen uns von ihnen zu h&ouml;ren. Ihre Kontaktperson ist:</p>
                <div class="divContactPerson">
                  <div class="divContactName">'.(($objContact->acTitle != '') ? $objContact->acTitle.' ' : '').$objContact->title.'</div>
                  <div class="divContactFunc">'.$objContact->position.'</div>
                  <div class="divContactDetail">
                    <div class="divContactImg">';
        if($objContact->filename != ''){
          $strHtmlOutput .= '<img src="'.$core->sysConfig->media->paths->imgbase.$strThumbImageFolder.'/'.$objContact->filename.'" alt="'.$objContact->title.'" title="'.$objContact->title.'"/>';
        }else{
          $strHtmlOutput .= '<img src="/website/themes/default/images/contact_default.gif" alt="'.$objContact->title.'" title="'.$objContact->title.'"/>';
        }
        $strHtmlOutput .= '
                    </div>
                    <div class="divContactInfos">
                      '.$objContact->phone.'<br/>
                      <a href="mailto:'.$objContact->email.'">E-Mail senden</a>
                    </div>
                    <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div class="clear"></div>
              </div>';  
      }
    }  
  }
  echo $strHtmlOutput;
}
  
/**
 * get_links_title
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_links_title(){
       
} 
  
/**
 * get_links
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_links(){
      
} 
  
/**
 * get_user_creator
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_user_creator(){
  echo getPageObject()->getCreatorName();      
}
  
/**
 * get_user_changer
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_user_changer(){
  echo getPageObject()->getChangeUserName();     
}
  
/**
 * get_user_publisher
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_user_publisher(){
  echo getPageObject()->getPublisherName();     
} 
  
/**
 * get_date_created
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_date_created(){
  echo getPageObject()->getCreateDate();     
}
  
/**
 * get_date_changed
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_date_changed(){
  echo getPageObject()->getChangeDate();     
}
  
/**
 * get_date_published
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_date_published(){
  echo getPageObject()->getPublishDate();     
}

/**
 * get_categories
 * @param string $strElement
 * @param boolean $blnLinked
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_categories($strElement = 'li', $blnLinked = true){
  $objPage = getPageObject();
  $objPageCategories = $objPage->getCategoriesValues('category');
  
  $strHtmlOutput = '';
  
  if(count($objPageCategories) > 0){
    foreach($objPageCategories as $objCategory){
      $strHtmlOutput .= '<'.$strElement.'>';
      if($blnLinked) $strHtmlOutput .= '<a href="#">';
      $strHtmlOutput .= htmlentities($objCategory->title, ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
      if($blnLinked) $strHtmlOutput .= '</a>';
      $strHtmlOutput .= '</'.$strElement.'>';
    }  
  }
  echo $strHtmlOutput;
}

/**
 * has_categories
 * @return boolean
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function has_categories(){
  $objPage = getPageObject();
  $objPageCategories = $objPage->getCategoriesValues('category');
  
  if(count($objPageCategories) > 0){
    return true;  
  }else{
    return false;
  }
}

/**
 * get_tags
 * @param string $strElement
 * @param boolean $blnLinked
 * @return string $strHtmlOutput
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function get_tags($strElement = 'li', $blnLinked = true){
  $objPage = getPageObject();
  $objPageTags = $objPage->getTagsValues('page_tags');  
  
  $strHtmlOutput = '';
  
  if(count($objPageTags) > 0){
    foreach($objPageTags as $objTag){
      $strHtmlOutput .= '<'.$strElement.'>';
      if($blnLinked) $strHtmlOutput .= '<a href="#">';
      $strHtmlOutput .= htmlentities($objTag->title, ENT_COMPAT, getCoreObject()->sysConfig->encoding->default);
      if($blnLinked) $strHtmlOutput .= '</a>';
      $strHtmlOutput .= '</'.$strElement.'>';
    }  
  } 
  echo $strHtmlOutput; 
}

/**
 * has_tags
 * @return boolean
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */
function has_tags(){
  $objPage = getPageObject();
  $objPageTags = $objPage->getTagsValues('page_tags');  
  
  if(count($objPageTags) > 0){
    return true;  
  }else{
    return false;
  }
}

/**
 * get_page_similar_page_links
 * @param integer $intNumber
 * @return string $strHtmlOutput
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */
function get_page_similar_page_links($intNumber = 5){
  $strHtmlOutput = '';
  
  $core = getCoreObject();
  $objPage = getPageObject();
  
  $strQuery = '';
  $objPageTags = $objPage->getTagsValues('page_tags');
  if(count($objPageTags) > 0){
    foreach($objPageTags as $objTag){
      $strQuery .= 'page_tags:"'.$objTag->title.'" OR ';  
    }
  }
  
  $objPageCategories = $objPage->getCategoriesValues('category');
  if(count($objPageCategories) > 0){
    foreach($objPageCategories as $objCategory){
      $strQuery .= 'category:"'.$objCategory->title.'" OR ';  
    }
  }
  
  $strQuery = rtrim($strQuery, ' OR ');
  
  if($strQuery != '' && count(scandir(GLOBAL_ROOT_PATH.$core->sysConfig->path->search_index->page)) > 2){
    
    Zend_Search_Lucene::setResultSetLimit($intNumber);  
    $objIndex = Zend_Search_Lucene::open(GLOBAL_ROOT_PATH.$core->sysConfig->path->search_index->page);  
    
    $objHits = $objIndex->find($strQuery);
    
    if(count($objHits) > 0){
      $strHtmlOutput .= '
                <div class="divLinks">
                  <h3>&Auml;hnliche Beitr&auml;ge</h3>';
      $counter = 1;
      foreach($objHits as $objHit){
        if($objHit->key != $objPage->getPageId()){
          $objDoc = $objHit->getDocument();
          $arrDocFields = $objDoc->getFieldNames();
          if(array_search('url', $arrDocFields) && array_search('title', $arrDocFields) && array_search('date', $arrDocFields)){
            $strHtmlOutput .= '
                    <div class="divLinkItem">
                      <a href="'.$objHit->url.'">'.htmlentities($objHit->title, ENT_COMPAT, $core->sysConfig->encoding->default).'</a><br/>
                      <span>Erstellt am</span> <span class="black">'.$objHit->date.'</span><!-- <span>unter</span> <span class="black">Sportservice News</span> -->
                    </div>';  
          }
        }
      }
      
      $strHtmlOutput .= '
                  <div class="clear"></div>
                </div>';
    }
  }
  
  echo $strHtmlOutput;
}

?>