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
 * PageHelper
 *
 * Version history (please keep backward compatible):
 * 1.0, 2010-02-04: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class PageHelper {
  
  /**
   * @var Core
   */
  protected $core;
  
  /**
   * @var Page
   */
  protected $objPage;

  /**
   * @var Zend_Translate
   */
  protected $objTranslate;
  
  /**
   * @var string
   */
  protected $strTheme;
  public function setTheme($strTheme) {
    $this->strTheme = $strTheme;
  }
  public function Theme(){
    return $this->strTheme;
  }
  
  /**
   * @var string
   */
  protected $strBottomContent = '';
  public function BottomContent(){
    return $this->strBottomContent;
  }
  
  /**
   * @var string
   */
  protected $strDomLoadedJs = '';
  public function DomLoadedJs(){
    return $this->strDomLoadedJs;
  }
  
  /**
   * constructor
   * @author Thomas Schedler <tsh@massiveart.com>   
   */
  public function __construct($blnRequireFrunctionWrapper = true){
    $this->core = Zend_Registry::get('Core');
    
    /**
     * function call wrapper for PageHelper
     */
    if($blnRequireFrunctionWrapper == true){
      require_once(dirname(__FILE__).'/page.inc.php');
    }
  }
  
  /**
   * getElementId
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getElementId(){
    $strReturn = '';
    if($this->objPage->getElementId() != ''){
      $strReturn = $this->objPage->getElementId();
    }
    return $strReturn;
  }
  
  /**
   * getTitle
   * @param string $strTag
   * @param boolean $blnTitleFallback
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getTitle($strTag, $blnTitleFallback){
    $strReturn = '';
    
    if($this->objPage->getFieldValue('articletitle') != ''){
      if($strTag != '') $strReturn .= '<'.$strTag.'>';
      $strReturn .= htmlentities($this->objPage->getFieldValue('articletitle'), ENT_COMPAT, $this->core->sysConfig->encoding->default);
      if($strTag != '') $strReturn .= '</'.$strTag.'>';
    }
    else if($this->objPage->getFieldValue('title') != '' && $blnTitleFallback){
      if($strTag != '') $strReturn .= '<'.$strTag.'>';
      $strReturn .= htmlentities($this->objPage->getFieldValue('title'), ENT_COMPAT, $this->core->sysConfig->encoding->default);
      if($strTag != '') $strReturn .= '</'.$strTag.'>';
    }
    return $strReturn;
  }
  
  /**
   * getParentTitle
   * @param string $strTag
   * @param boolean $blnTitleFallback
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getParentTitle($strTag, $blnTitleFallback){
    $strReturn = '';
    if($this->objPage->ParentPage() instanceof Page){
      if($this->objPage->ParentPage()->getFieldValue('articletitle') != ''){
        if($strTag != '') $strReturn .= '<'.$strTag.'>';
        $strReturn .= htmlentities($this->objPage->ParentPage()->getFieldValue('articletitle'), ENT_COMPAT, $this->core->sysConfig->encoding->default);
        if($strTag != '') $strReturn .= '</'.$strTag.'>';
      }
      else if($this->objPage->ParentPage()->getFieldValue('title') != '' && $blnTitleFallback){
        if($strTag != '') $strReturn .= '<'.$strTag.'>';
        $strReturn .= htmlentities($this->objPage->ParentPage()->getFieldValue('title'), ENT_COMPAT, $this->core->sysConfig->encoding->default);
        if($strTag != '') $strReturn .= '</'.$strTag.'>';
      }
    }
    return $strReturn;
  }
  
  /**
   * getZooluHeader
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getZooluHeader(){
    $strReturn = '';
    if(Zend_Auth::getInstance()->hasIdentity() && isset($_SESSION['sesZooluLogin']) && $_SESSION['sesZooluLogin'] == true){
      $strReturn .= '<div class="divModusContainer">
        <div class="divModusLogo">
          <a href="/zoolu/cms" target="_blank">
            <img src="'.$this->core->config->domains->static->components.'/zoolu-statics/images/modus/logo_zoolu_modus.gif" alt="ZOOLU" />
          </a>
        </div>
        <div class="divModusAdvice">Hinweis: Im Moment werden auch Seiten mit dem <strong>Status "Test"</strong> dargestellt.</div>
        <div class="divModusLogout"><a href="/zoolu/users/user/logout">Abmelden</a></div>
        <div class="divModusStatus">Test/Live-Modus:
          <select id="selTestMode" name="selTestMode" onchange="myDefault.changeTestMode(this.options[this.selectedIndex].value);">
            <option value="on" '.((isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == true) ? ' selected="selected"' : '').'>Aktiv</option>
            <option value="off" '.((isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == true) ? '' : ' selected="selected"').'>Inaktiv</option>
          </select>
        </div>
         <div class="divModusCache">
          <div onclick="myDefault.expireCache(this.id); return false;" id="expireCache">
            <div class="button25leftOn"></div>
            <div class="button25centerOn">
              <div>Expire Cache</div>
            </div>
            <div class="button25rightOn"></div>
            <div class="clear"></div>
          </div>          
        </div>
        <div class="clear"></div>
      </div>';
    }  
    return $strReturn;
  }
  
  /**
   * getTemplateFile
   * @return string 
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function getTemplateFile(){
    return $this->objPage->getTemplateFile();
  }
  
  /**
   * getRootLevelTitle
   * @return string $strRootLevelTitle
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function getRootLevelTitle(){
    return $this->objPage->getRootLevelTitle();
  }
  
  /**
   * getMetaDescription
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getMetaKeywords(){
    $objPageTags = $this->objPage->getTagsValues('page_tags');
  
    $strReturn = '';
    $strKeywords = '';
    if(count($objPageTags) > 0){
      $strReturn .= '<meta name="keywords" content="';
      foreach($objPageTags as $objTag){
        $strKeywords .= htmlentities($objTag->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).', ';
      }
      $strReturn .= trim($strKeywords, ', ').'"/>';
    }
    return $strReturn;
  }
    
  /**
   * getMetaDescription
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getMetaDescription(){
    $strReturn = '';
    if($this->objPage->getFieldValue('shortdescription') != ''){
      $strReturn .= '<meta name="description" content="'.htmlentities($this->objPage->getFieldValue('shortdescription'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'"/>';
    }
    return $strReturn;  
  }
  
  /**
   * getDescription
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getDescription($strContainerClass, $blnContainer){
    $strReturn = '';
    if(strip_tags($this->objPage->getFieldValue('description')) != ''){
      if($blnContainer) $strReturn .= '<div class="'.$strContainerClass.'">';
      $strReturn .= $this->objPage->getFieldValue('description');
      if($blnContainer) $strReturn .= '</div>';
    }
    return $strReturn;
  }
  
  /**
   * getAbstract
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getAbstract(){
    $strReturn = '';
    if($this->objPage->getFieldValue('shortdescription') != ''){
      $strReturn .= nl2br(htmlentities($this->objPage->getFieldValue('shortdescription'), ENT_COMPAT, $this->core->sysConfig->encoding->default));
    }
    return $strReturn;
  }
  
  /**
   * getImageMain
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getImageMain($strImageFolder, $blnZoom, $blnUseLightbox, $strImageFolderZoom, $strContainerClass){
    $strReturn = '';
    
    $objFiles = $this->objPage->getFileFieldValue('mainpics');
  
    if($objFiles != '' && count($objFiles) > 0){
      $strReturn .= '<div class="'.$strContainerClass.'">';
      
      $arrFiles = array();
      foreach($objFiles as $objFile){
        $arrFiles[] = $objFile;
      }    
      shuffle($arrFiles);   
      
      foreach($arrFiles as $objFile){
        if($blnZoom){
          $strReturn .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageFolderZoom.'/'.$objFile->filename.'?v='.$objFile->version.'"';
          if($blnUseLightbox){
            $strReturn .= ' rel="lightbox[mainpics]"';
          }
          $strReturn .= '>';
        }
        $strReturn .= '<img src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageFolder.'/'.$objFile->filename.'?v='.$objFile->version.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';
        if($blnZoom){
          $strReturn .= '</a>';
        }
        $strReturn .= '<br/>';
      }
      $strReturn .= '</div>';
    }
    return $strReturn;
  }
  
  /**
   * getImageGalleryTitle
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getImageGalleryTitle($strElement, $blnShowDefaultTitle){
    $strReturn = '';
    if($this->objPage->getFieldValue('pics_title') != ''){
      $strReturn = '<'.$strElement.'>'.htmlentities($this->objPage->getFieldValue('pics_title'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</'.$strElement.'>';  
    }else if($blnShowDefaultTitle){
      $strReturn = '<'.$strElement.'>'.$this->objTranslate->_('Image_gallery').'</'.$strElement.'>';
    }else{
      $strReturn = '';
    }
    return $strReturn;
  }
  
  /**
   * getImageGallery
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getImageGallery($intLimitNumber, $strImageGalleryFolder, $blnZoom, $blnUseLightbox, $strImageFolderZoom, $strContainerClass, $strThumbContainerClass, $intColNumber){
    $strReturn = '';
    
    $objFiles = $this->objPage->getFileFieldValue('pics');

    if($objFiles != '' && count($objFiles) > 0){
      $counter = 0;
      $strReturn .= '<div class="'.$strContainerClass.'">';
      
      $strReturn .= $this->getImageGalleryTitle('h3', false);
      
      foreach($objFiles as $objFile){
        if($intLimitNumber > 0 && $counter == $intLimitNumber){
          $strReturn .= '
            <div id="showAll"><a onclick="myDefault.galleryShowAll(this.id); return false;" href="#">'.$this->objTranslate->_('Show_all_images').'</a></div>
            <div id="imageGallery" style="display:none;">';
        }
        
        if($intColNumber > 0 && ($counter % $intColNumber == ($intColNumber-1))) {
          $strReturn .= '<div class="'.$strThumbContainerClass.' pBottom20">';
        }else{
          $strReturn .= '<div class="'.$strThumbContainerClass.' pBottom20 pRight20">';
        }
        
        if($blnZoom){
          $strReturn .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageFolderZoom.'/'.$objFile->filename.'?v='.$objFile->version.'"';
          if($blnUseLightbox){
            $strReturn .= ' rel="lightbox[pics]"';
          }
          $strReturn .= '>';
        }
        $strReturn .= '<img src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageGalleryFolder.'/'.$objFile->filename.'?v='.$objFile->version.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';
        if($blnZoom){
          $strReturn .= '</a>';
        }
        
        $strReturn .= '</div>';
        
        if($counter >= $intLimitNumber && $counter == count($objFiles)-1){
          $strReturn .= '
              <div class="clear"></div>
            </div>';
        }
        $counter++;
      }
      $strReturn .= '
            <div class="clear"></div>
          </div>';
    }
    return $strReturn;
  }
  
  /**
   * hasImageGallery
   * @return boolean
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function hasImageGallery(){
    $objFiles = $this->objPage->getFileFieldValue('pics');
    if($objFiles != '' && count($objFiles) > 0){
      return true;
    }else{
      return false;
    }
  }
  
  /**
   * getImageMainSlogan
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getImageMainSlogan($strImageFolder, $blnZoom, $blnUseLightbox, $strImageFolderZoom, $strContainerClass, $strImageContainerClass){
    // TODO : getImageMainSlogan   
  }
  
  /**
   * getVideoTitle
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getVideoTitle($strElement, $blnShowDefaultTitle){
    $strReturn = '';
    if($this->objPage->getFieldValue('video_title') != ''){
      $strReturn = '<'.$strElement.'>'.htmlentities($this->objPage->getFieldValue('video_title'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</'.$strElement.'>';
    }else if($blnShowDefaultTitle){
      $strReturn = '<'.$strElement.'>'.$this->objTranslate->_('Video').'</'.$strElement.'>';
    }
    return $strReturn;  
  }
  
  /**
   * getVideo
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getVideo($intVideoWidth, $intVideoHeight, $blnShowVideoTitle){
    $strReturn = '';
    
    $objVideosIntern = $this->objPage->getFileFieldValue('videoIntern');    
    $mixedVideoId = $this->objPage->getFieldValue('video');
    $arrFieldProperties = $this->objPage->getField('video')->getProperties();
    
    if(count($objVideosIntern) > 0 || $mixedVideoId != '' || $this->objPage->getFieldValue('video_embed_code') != ''){      
      $strReturn .= '
        <div class="movies">';
      
      if(count($objVideosIntern) > 0){
        foreach($objVideosIntern as $objFileData){
          $strReturn .= '
            <div class="item">
              <a href="/zoolu-website/media/video/'.$objFileData->id.'/" style="width:'.$intVideoWidth.'px; height:'.$intVideoHeight.'px; display:block;" id="vplayer_'.$objFileData->id.'"></a>
              <script language="JavaScript" type="text/javascript">
                flowplayer(\'vplayer_'.$objFileData->id.'\', \'/website/themes/default/flowplayer/flowplayer-3.2.2.swf\');
              </script>
            </div>';
        }  
      }
    
      if($mixedVideoId != ''){
        /*
         * Vimeo Service
         */
        if($arrFieldProperties['intVideoTypeId'] == $this->core->sysConfig->video_channels->vimeo->id) {
          $strReturn .= '
                   <div class="item">
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
        else if($arrFieldProperties['intVideoTypeId'] == $this->core->sysConfig->video_channels->youtube->id) {
          $strReturn .= '
            <div class="item">
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
      }else if($this->objPage->getFieldValue('video_embed_code') != ''){
        $strReturn .= '<div class="item">'.$this->objPage->getFieldValue('video_embed_code').'</div>';
      }
      $strReturn .= '
        </div>';
    }
      
    if($blnShowVideoTitle && $strReturn != '') {
      $strReturn = $this->getVideoTitle('h3', false).$strReturn;  
    }
  
    return $strReturn;  
  }
  
  /**
   * getDocumentsTitle
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getDocumentsTitle($strElement, $blnShowDefaultTitle){
    $strReturn = '';
    if($this->objPage->getFieldValue('docs_title') != ''){
      $strReturn = '<'.$strElement.'>'.htmlentities($this->objPage->getFieldValue('docs_title'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</'.$strElement.'>';
    }else if($blnShowDefaultTitle){
      $strReturn = '<'.$strElement.'>'.$this->objTranslate->_('Brochures').'</'.$strElement.'>';
    }
    return $strReturn;  
  }
  
  /**
   * getDocuments
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getDocuments($strContainerCss, $strItemCss, $strIconCss, $strTitleCss, $strTheme){
    $strReturn = '';

    $objFiles = $this->objPage->getFileFieldValue('docs');

    if($objFiles != '' && count($objFiles) > 0){
      $strReturn .= '<div class="'.$strContainerCss.'">';
      
      $strReturn .= $this->getDocumentsTitle('h3', false);
      
      foreach($objFiles as $objFile){
        $strIcon = (strpos($objFile->mimeType, 'image') !== false) ? 'icon_img.gif' : 'icon_'.$objFile->extension.'.gif';
        $strReturn .= '<div class="'.$strItemCss.'">
                <div class="'.$strIconCss.'"><img src="/website/themes/'.$strTheme.'/images/icons/'.$strIcon.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/></div>
                <div class="'.$strTitleCss.'">
                  <a href="/zoolu-website/media/document/'.$objFile->id.'/'.urlencode(str_replace('.', '-', $objFile->title)).'" onmousedown="clickTracker(\'/zoolu-website/media/document/'.$objFile->id.'/'.urlencode(str_replace('.', '-', $objFile->title)).'\');" target="_blank">'.$objFile->title.'</a>                
                </div>
                <div class="clear"></div>
              </div>';
      }
      $strReturn .= '
          <div class="clear"></div>
        </div>';
    }
    return $strReturn;
  }
  
  /**
   * hasDocuments
   * @return boolean
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function hasDocuments(){
    $objFiles = $this->objPage->getFileFieldValue('docs');
    if($objFiles != '' && count($objFiles) > 0){
      return true;
    }else{
      return false;
    }
  }
  
  /**
   * getBlockDocuments
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getBlockDocuments($intRegionId, $blnFileFilterDocs){
    $strReturn = '';
    
    $objMyMultiRegion = $this->objPage->getRegion($intRegionId); //47 is the default block document region
  
    if($objMyMultiRegion instanceof GenericElementRegion){
      foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
  
        $strBlockTitle = htmlentities($objMyMultiRegion->getField('docs_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $this->core->sysConfig->encoding->default);
        if($strBlockTitle != ''){
          
          $intFilterLanguageId = ($this->objPage->FallbackPage() instanceof Page) ? $this->objPage->FallbackPage()->getLanguageId() : $this->objPage->getLanguageId();
          $objFiles = ($blnFileFilterDocs == true) ? $this->objPage->getFileFilterFieldValue($objMyMultiRegion->getField('docs')->getInstanceValue($intRegionInstanceId), $intFilterLanguageId) : $this->objPage->getFileFieldValueById($objMyMultiRegion->getField('docs')->getInstanceValue($intRegionInstanceId));
            
          if($objFiles != '' && count($objFiles) > 0){
            $strFiles = '';
            $arrDestinationSpecifics = array();
            
            foreach($objFiles as $objFile){
              $strIcon = (strpos($objFile->mimeType, 'image') !== false) ? 'icon_img.gif' : 'icon_'.$objFile->extension.'.gif';
              $strItem = '
                    <div class="item">
                      <div class="icon"><img src="'.$this->core->config->domains->static->components.'/website/themes/default/images/icons/'.$strIcon.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/></div>
                      <div class="text">
                        <a href="/zoolu-website/media/document/'.$objFile->id.'/'.urlencode(str_replace('.', '-', $objFile->title)).'" onmousedown="clickTracker(\'/zoolu-website/media/document/'.$objFile->id.'/'.urlencode(str_replace('.', '-', $objFile->title)).'\');" target="_blank">'.htmlentities((($objFile->title == '' && (isset($objFile->alternativTitle) || isset($objFile->fallbackTitle))) ? ((isset($objFile->alternativTitle) && $objFile->alternativTitle != '') ? $objFile->alternativTitle : $objFile->fallbackTitle) : $objFile->title), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                      </div>
                      <div class="clear"></div>
                    </div>';
              
              if($objFile->idDestination == 0){
                $strFiles .= $strItem;
              }else{
                $objTmpEntry = new stdClass();
                $objTmpEntry->destinationId = $objFile->idDestination;
                $objTmpEntry->output = $strItem;                
                $arrDestinationSpecifics[] = $objTmpEntry;  
              }
            }
            
            $strDisplayTitle = ($strFiles == '') ? ' style="display:none;"' : '';
            
            $strReturn .= '
                <div id="blockDocuments_'.$intRegionInstanceId.'">
                  <h2 id="blockDocuments_'.$intRegionInstanceId.'_title"'.$strDisplayTitle.'>'.$strBlockTitle.'</h2>
                  <div class="documents" id="blockDocuments_'.$intRegionInstanceId.'_container">'
                  .$strFiles;
              
            if(count($arrDestinationSpecifics) > 0){
              $strTmpCacheId = str_replace('.', '', uniqid(uniqid(), true));
              $this->core->TmpCache()->save($arrDestinationSpecifics, $strTmpCacheId);
              $strReturn .= '
                <div class="loader" id="blockDocuments_'.$intRegionInstanceId.'_addon">
                  <script type="text/javascript">
                    new Ajax.Updater(\'blockDocuments_'.$intRegionInstanceId.'_addon\', \'/zoolu-website/content/destination-filter\', {
                      method: \'get\',
                      parameters: { 
                        tmpId: \''.$strTmpCacheId.'\'
                      },
                      evalScripts: true,
                      onComplete: function() {         
                        $(\'blockDocuments_'.$intRegionInstanceId.'_addon\').removeClassName(\'loader\');
                        if($(\'blockDocuments_'.$intRegionInstanceId.'_addon\').innerHTML.blank() && '.(($strFiles == '') ? 'true' : 'false').'){
                          $(\'blockDocuments_'.$intRegionInstanceId.'\').remove();
                        }else{
                          $(\'blockDocuments_'.$intRegionInstanceId.'_title\').show();
                        }
                      }
                    });
                  </script>
                </div>';
            }
    
            $strReturn .= '
                  </div>
                </div>';
          }
  
        }
      }
    }
    return $strReturn;  
  }
  
  /**
   * getInternalLinksTitle
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getInternalLinksTitle($strElement, $blnShowDefaultTitle){
    $strReturn = '';
    
    $objPage = ($this->objPage->FallbackPage() instanceof Page) ? $this->objPage->FallbackPage() : $this->objPage;    

    if($objPage->getFieldValue('internal_links_title') != ''){
      $strReturn = '<'.$strElement.'>'.htmlentities($objPage->getFieldValue('internal_links_title'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</'.$strElement.'>';
    }else if($blnShowDefaultTitle){
      $strReturn = '<'.$strElement.'>'.$this->objTranslate->_('Internal_links').'</'.$strElement.'>';
    }
    
    return $strReturn;  
  }
  
  /**
   * getInternalLinks
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getInternalLinks($strContainerCss, $strItemCss, $strIconCss, $strTitleCss, $strHeadlineElement, $blnShowDefaultTitle){
    $strReturn = '';
    $strHeadline = '';
    
    $objPage = ($this->objPage->FallbackPage() instanceof Page) ? $this->objPage->FallbackPage() : $this->objPage;
  
    if(count($objPage->getField('internal_links')->objItemInternalLinks) > 0){
      $strReturn .= '<div class="'.$strContainerCss.'">';
      
      if($objPage->getFieldValue('internal_links_title') != ''){
        $strReturn .= '<'.$strHeadlineElement.'>'.htmlentities($objPage->getFieldValue('internal_links_title'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</'.$strHeadlineElement.'>';
      }else if($blnShowDefaultTitle){
        $strReturn .= '<'.$strHeadlineElement.'>'.$this->objTranslate->_('Internal_links').'</'.$strHeadlineElement.'>';
      }
      
      foreach($objPage->getField('internal_links')->objItemInternalLinks as $objPageInternalLink){
        
        if($objPage->ParentPage() instanceof Page && ($objPage->ParentPage()->getTypeId() == $this->core->sysConfig->page_types->product_tree->id || $objPage->ParentPage()->getTypeId() == $this->core->sysConfig->page_types->press_area->id || $objPage->ParentPage()->getTypeId() == $this->core->sysConfig->page_types->courses->id || $objPage->ParentPage()->getTypeId() == $this->core->sysConfig->page_types->events->id)){
          $strUrl = $objPage->ParentPage()->getFieldValue('url').$objPageInternalLink->url;  
        }else{
          $strUrl = '/'.strtolower($objPageInternalLink->languageCode).'/'.$objPageInternalLink->url;  
        }
        
        $strReturn .= '<div class="'.$strItemCss.'">
                <div class="'.$strIconCss.'">&raquo;</div>
                <div class="'.$strTitleCss.'">
                  <a href="'.$strUrl.'">'.$objPageInternalLink->title.'</a>
                </div>
                <div class="clear"></div>
              </div>';
      }
      $strReturn .= '
          <div class="clear"></div>
        </div>';
    }
    return $strReturn;  
  }
  
  /**
   * hasInternalLinks
   * @return boolean
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function hasInternalLinks(){
    $objPage = ($this->objPage->FallbackPage() instanceof Page) ? $this->objPage->FallbackPage() : $this->objPage;
    $objFiles = $objPage->getFieldValue('internal_links');
  
    if($objFiles != '' && count($objFiles) > 0){
      return true;
    }else{
      return false;
    }
  }
  
  /**
   * getTextBlocks
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getTextBlocks($strImageFolder, $blnZoom, $blnUseLightbox, $strImageFolderZoom, $strContainerClass, $strImageContainerClass){
    $strReturn = '';
    
    $objMyMultiRegion = $this->objPage->getRegion(11); //11 is the default text block region
  
    if($objMyMultiRegion instanceof GenericElementRegion){
      foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
  
        $strBlockTitle = htmlentities($objMyMultiRegion->getField('block_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $this->core->sysConfig->encoding->default);
        $strBlockDescription = $objMyMultiRegion->getField('block_description')->getInstanceValue($intRegionInstanceId);
        if($strBlockTitle != '' || $strBlockDescription != ''){
          $strReturn .= '<div class="'.$strContainerClass.'">';
          if($strBlockTitle != '') $strReturn .= '<h3>'.$strBlockTitle.'</h3>';
          
          $objFiles = $this->objPage->getFileFieldValueById($objMyMultiRegion->getField('block_pics')->getInstanceValue($intRegionInstanceId));
          $objDisplayOption = json_decode(str_replace("'", '"', $objMyMultiRegion->getField('block_pics')->getInstanceProperty($intRegionInstanceId, 'display_option')));
          
          if(!isset($objDisplayOption->position) || $objDisplayOption->position == null) $objDisplayOption->position = 'LEFT_MIDDLE';
          if(!isset($objDisplayOption->size) || $objDisplayOption->size == null) $objDisplayOption->size = $strImageFolder;
          
          $strImageAddonClasses = '';
          $strImageModAddonClasses = '';
          $strImageContainerAddonClasses = '';
          switch($objDisplayOption->position){
            case Image::POSITION_RIGHT_MIDDLE:
              $strImageContainerAddonClasses = 'pLeft20 pBottom10 right';
              break;
            case Image::POSITION_CENTER_BOTTOM:
              $strImageContainerAddonClasses = 'pTop10';
              break;
            case Image::POSITION_CENTER_TOP:
              $strImageContainerAddonClasses = 'pBottom10';
              break;
            case Image::POSITION_LEFT_MIDDLE:
            default:
              $strImageContainerAddonClasses = 'pRight20 pBottom10 left';
              break;
          }
          
          $strHtmlOutputImage = '';
          if($objFiles != '' && count($objFiles) > 0){
            $strHtmlOutputImage .= '<div class="'.$strImageContainerAddonClasses.'">';
            $intImgCounter = 0;
            foreach($objFiles as $objFile){
              $intImgCounter++;
              if($blnZoom && $strImageFolderZoom != ''){
                $strHtmlOutputImage .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageFolderZoom.'/'.$objFile->filename.'?v='.$objFile->version.'"';
                if($blnUseLightbox) $strHtmlOutputImage .= ' rel="lightbox[textblocks]"';
                $strHtmlOutputImage .= '>';
              }
              $strHtmlOutputImage .= '<img class="img'.$objDisplayOption->size.($intImgCounter % 4 == 0 ? $strImageModAddonClasses : $strImageAddonClasses).'" src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$objDisplayOption->size.'/'.$objFile->filename.'?v='.$objFile->version.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';
              if($blnZoom && $strImageFolderZoom != '') $strHtmlOutputImage .= '</a>';
            }
            $strHtmlOutputImage .= '</div>';
          }          
          
          $strHtmlOutputContent = '';          
          $strHtmlOutputContent .= '<div class="description">'.$strBlockDescription.'</div>';
          
          if($objDisplayOption->position == Image::POSITION_CENTER_BOTTOM){
            $strReturn .= $strHtmlOutputContent.$strHtmlOutputImage;
          }else{
            $strReturn .= $strHtmlOutputImage.$strHtmlOutputContent;
          }          
          $strReturn .= '
                <div class="clear"></div>
              </div>';
        }
      }
    }
    return $strReturn;  
  }
  
  /**
   * getTextBlocksExtended
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getTextBlocksExtended($strImageFolder, $blnZoom, $blnUseLightbox, $strImageFolderZoom, $strContainerClass, $strImageContainerClass, $bln2Columned){
    $strReturn = '';
    
    $objMyMultiRegion = $this->objPage->getRegion(45); //45 is the default text block extended region
  
    $arrHtmlOuput = array();
    $intItemCounter = 0;
     
    if($objMyMultiRegion instanceof GenericElementRegion){
      foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
  
        $strBlockTitle = htmlentities($objMyMultiRegion->getField('block_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $this->core->sysConfig->encoding->default);
        $strBlockDescription = $objMyMultiRegion->getField('block_description')->getInstanceValue($intRegionInstanceId);
        if($strBlockTitle != '' || $strBlockDescription != ''){
          
          $strReturn .= '<div class="'.$strContainerClass.' mBottom10">';
  
          $objFiles = $this->objPage->getFileFieldValueById($objMyMultiRegion->getField('block_pics')->getInstanceValue($intRegionInstanceId));
          
          $objDisplayOption = json_decode(str_replace("'", '"', $objMyMultiRegion->getField('block_pics')->getInstanceProperty($intRegionInstanceId, 'display_option')));
          
          if(!isset($objDisplayOption->position) || $objDisplayOption->position == null) $objDisplayOption->position = 'LEFT_MIDDLE';
          if(!isset($objDisplayOption->size) || $objDisplayOption->size == null) $objDisplayOption->size = $strImageFolder;
          
          $strImageAddonClasses = '';
          switch($objDisplayOption->position){
            case Image::POSITION_RIGHT_MIDDLE:
              $strImageAddonClasses = ' mLeft10 right';
              break;
            case Image::POSITION_LEFT_MIDDLE:
            default:
              $strImageAddonClasses = ' mRight10 left';
              break;
          }
          
          $strHtmlOutputImage = '';
          if($objFiles != '' && count($objFiles) > 0){
            $strHtmlOutputImage .= '<div class="'.$strImageContainerClass.$strImageAddonClasses.'">';
            foreach($objFiles as $objFile){
              if($blnZoom && $strImageFolderZoom != ''){
                $strHtmlOutputImage .= '<a title="'.(($objFile->description != '') ? $objFile->description : $objFile->title).'" href="'.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageFolderZoom.'/'.$objFile->filename.'?v='.$objFile->version.'"';
                if($blnUseLightbox) $strHtmlOutputImage .= ' rel="lightbox[textblocks]"';
                $strHtmlOutputImage .= '>';
              }
              $strHtmlOutputImage .= '<img class="img'.$objDisplayOption->size.'" src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$objDisplayOption->size.'/'.$objFile->filename.'?v='.$objFile->version.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';
              if($blnZoom && $strImageFolderZoom != '') $strHtmlOutputImage .= '</a>';
            }
            $strHtmlOutputImage .= '</div>';
          }
  
          $strHtmlOutputContent = '<div>';
          if($strBlockTitle != '') $strHtmlOutputContent .= '<h3>'.$strBlockTitle.'</h3>';
          $strHtmlOutputContent .= $strBlockDescription;
  
          if($objMyMultiRegion->getField('block_docs')){
            $objFiles = $this->objPage->getFileFieldValueById($objMyMultiRegion->getField('block_docs')->getInstanceValue($intRegionInstanceId));
            if($objFiles != '' && count($objFiles) > 0){
              $strHtmlOutputContent .= '<div class="documents left">';
              foreach($objFiles as $objFile){
                $strHtmlOutputContent .= '<div class="item">
                        <div class="icon"><img src="'.$this->core->config->domains->static->components.'/website/themes/default/images/icons/icon_document.gif" alt="'.$objFile->title.'" title="'.$objFile->title.'"/></div>
                        <div class="text">
                          <a href="/zoolu-website/media/document/'.$objFile->id.'/'.urlencode(str_replace('.', '-', $objFile->title)).'" onmousedown="clickTracker(\'/zoolu-website/media/document/'.$objFile->id.'/'.urlencode(str_replace('.', '-', $objFile->title)).'\');" target="_blank">'.$objFile->title.'</a>
                        </div>
                        <div class="clear"></div>
                      </div>';
              }
              $strHtmlOutputContent .= '</div>';
            }
          }
  
          $strHtmlOutputContent .= '</div>';
          $strHtmlOutputContent .= '<div class="clear"></div>';
          
          if($objDisplayOption->position == Image::POSITION_CENTER_BOTTOM){
            $strReturn .= $strHtmlOutputContent.$strHtmlOutputImage;
          }else{
            $strReturn .= $strHtmlOutputImage.$strHtmlOutputContent;
          }
          
          $strReturn .= '</div>';
          
          if($bln2Columned){
            if(!array_key_exists('col0'.($intItemCounter % 2 + 1), $arrHtmlOuput)){
              $arrHtmlOuput['col0'.($intItemCounter % 2 + 1)] = $strReturn;
            }else{
              $arrHtmlOuput['col0'.($intItemCounter % 2 + 1)] .= $strReturn;
            }
            $strReturn = '';
          }
          $intItemCounter++;
        }
      }
    }
    
    if($bln2Columned && count($arrHtmlOuput) > 0){
      foreach($arrHtmlOuput as $strKey => $strColOutput){
        $strReturn .= '
            <div class="'.$strKey.'">
              '.$strColOutput.'      
            </div>';
      }
    }
    
    return $strReturn; 
  }
  
  /**
   * getSidebar
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getSidebar($strContainerClass, $strBlockClass, $strImageFolder, $intRegionId){
    $strReturn = '';
    if($intRegionId > 0){
      $objMyMultiRegion = $this->objPage->getRegion($intRegionId);
    
      if($objMyMultiRegion instanceof GenericElementRegion){
        if(count($objMyMultiRegion->RegionInstanceIds()) > 0){
          $counter = 0;
          $strReturn .= '
                <div class="'.$strContainerClass.'">';
          
          foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
            $strBlockTitle = htmlentities($objMyMultiRegion->getField('sidebar_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $this->core->sysConfig->encoding->default);
            $strBlockDescription = $objMyMultiRegion->getField('sidebar_description')->getInstanceValue($intRegionInstanceId);
            $objFiles = $this->objPage->getFileFieldValueById($objMyMultiRegion->getField('sidebar_pics')->getInstanceValue($intRegionInstanceId));
            $counter++;
    
            if($strBlockTitle != '' || $strBlockDescription != ''){
              $strReturn .= '
                  <div class="'.$strBlockClass.'">';
              if($strBlockTitle != ''){
                $strReturn .= '
                    <h3>'.$strBlockTitle.'</h3>';  
              }
              if($objFiles != '' && count($objFiles) > 0){
                foreach($objFiles as $objFile){
                  $strReturn .= '
                    <img src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objFile->path.$strImageFolder.'/'.$objFile->filename.'?v='.$objFile->version.'" alt="'.$objFile->title.'" title="'.$objFile->title.'"/>';
                }
              }                            
              $strReturn .= $strBlockDescription.'
                   </div>';
            }
          }
          $strReturn .= '
                 </div>';
        }
      }  
    }
    return $strReturn;
  }
  
  /**
   * getCollection
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getCollection($strImageFolder){
    $strReturn = '';

    $objPageContainer = $this->objPage->getCollectionContainer();
  
    if(count($objPageContainer) > 0){
      $strReturn .= '
            <h3>'.htmlentities($objPageContainer->getContainerTitle(), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</h3>';
  
      foreach($objPageContainer->getEntries() as $objPageEntry){
        $strDescription = '';
        if($objPageEntry->shortdescription != ''){
          $strDescription = strip_tags($objPageEntry->shortdescription);
        }else if($objPageEntry->description != ''){
          if(strlen($objPageEntry->description) > 200){
            $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 200))).' ...';
          }else{
            $strDescription = strip_tags($objPageEntry->description);
          }
        }
  
        $strReturn .= '
          <div class="divContentItem">
            <h2><a href="'.$objPageEntry->url.'">'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></h2>';
        if($objPageEntry->filename != ''){
          $strReturn .= '
            <div class="divImgLeft">
              <a href="'.$objPageEntry->url.'">
                <img src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objPageEntry->filepath.$strImageFolder.'/'.$objPageEntry->filename.'?v='.$objPageEntry->fileversion.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/>
              </a>
            </div>';
        }
        if($strDescription != ''){
          $strReturn .= '<p>'.$strDescription.'</p>';
        }
        $strReturn .= '
            <a href="'.$objPageEntry->url.'">Weiter lesen...</a>
            <div class="clear"></div>
          </div>';
      }
  
    }
    return $strReturn;
  }
  
  /**
   * hasCategories
   * @return boolean
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function hasCategories(){
    $objPageCategories = $this->objPage->getCategoriesValues('category');
  
    if(count($objPageCategories) > 0){
      return true;
    }else{
      return false;
    }
  }
  
  /**
   * hasTags
   * @return boolean
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function hasTags(){
    $objPageTags = $this->objPage->getTagsValues('page_tags');
  
    if(count($objPageTags) > 0){
      return true;
    }else{
      return false;
    }
  }
  
  /**
   * getPageSimilarPageLinks
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPageSimilarPageLinks($intNumber = 5, $strContainerClass = 'links', $strItemClass = 'item'){
    $strReturn = '';
    
    $strQuery = '';
    $objPageTags = $this->objPage->getTagsValues('page_tags');
    if(count($objPageTags) > 0){
      foreach($objPageTags as $objTag){
        $strQuery .= 'page_tags:"'.$objTag->title.'" OR ';
      }
    }
  
    $objPageCategories = $this->objPage->getCategoriesValues('category');
    if(count($objPageCategories) > 0){
      foreach($objPageCategories as $objCategory){
        $strQuery .= 'category:"'.$objCategory->title.'" OR ';
      }
    }
  
    $strQuery = rtrim($strQuery, ' OR ');
  
    if($strQuery != '' && count(scandir(GLOBAL_ROOT_PATH.$this->core->sysConfig->path->search_index->page)) > 2){
  
      Zend_Search_Lucene::setResultSetLimit($intNumber);
      $objIndex = Zend_Search_Lucene::open(GLOBAL_ROOT_PATH.$this->core->sysConfig->path->search_index->page);
  
      $objHits = $objIndex->find($strQuery);
  
      if(count($objHits) > 0){
        $strReturn .= '
                  <div class="'.$strContainerClass.'">
                    <h3>'.$this->objTranslate->_('Similar_pages').'</h3>';
        $counter = 1;
        foreach($objHits as $objHit){
          if($objHit->key != $this->objPage->getPageId()){
            $objDoc = $objHit->getDocument();
            $arrDocFields = $objDoc->getFieldNames();
            if(array_search('url', $arrDocFields) && array_search('title', $arrDocFields) && array_search('date', $arrDocFields)){
              $strReturn .= '
                      <div class="item">
                        <a href="'.$objHit->url.'">'.htmlentities($objHit->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a><br/>
                        <span>'.$this->objTranslate->_('Created_at').'</span> <span class="black">'.$objHit->date.'</span>
                      </div>';
            }
          }
        }
  
        $strReturn .= '
                    <div class="clear"></div>
                  </div>';
      }
    }
    return $strReturn;
  }
  
  /**
   * getPagesOverview
   * @param string $strImageFolder
   * @param string $strThumbImageFolder
   * @return string $strReturn
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPagesOverview($strImageFolder = '80x80', $strThumbImageFolder = '40x40'){
      
    $arrPagesOverview = $this->objPage->getPagesContainer();
  
    $strReturn = '';
    if(count($arrPagesOverview) > 0){
      foreach($arrPagesOverview as $key => $this->objPageContainer){
        if(count($this->objPageContainer) > 0){
  
          $strCssClassPostfix = '';
          if($key < 2){
            $strCssClassPostfix = ' pright20';
          }
  
          if($key < 3){
  
            $strReturn .= '
                 <div class="col3'.$strCssClassPostfix.'">
                    <h3>'.htmlentities($this->objPageContainer->getContainerTitle(), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</h3>';
  
            $arrPageEntries = $this->objPageContainer->getEntries();
  
            $strTopPostHtmlOutput = '';
            $strLinkItemsHtmlOutput = '';
  
            if(count($arrPageEntries) > 0){
              $counter = 0;
              foreach($arrPageEntries as $this->objPageEntry){
                if($counter == 0){
  
                  $strTopPostHtmlOutput .= '
                    <div class="divTopPost">
                      <h2><a href="'.$this->objPageEntry->url.'"'.(($this->objPageEntry->target !== false) ? ' target="'.$this->objPageEntry->target.'"' : '').'>'.htmlentities($this->objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></h2>';
                  if($this->objPageEntry->filename != ''){
                    $strTopPostHtmlOutput .= '
                     <div class="divImgLeft">
                       <img alt="'.$this->objPageEntry->filetitle.'" title="'.$this->objPageEntry->filetitle.'" src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$this->objPageEntry->filepath.$strImageFolder.'/'.$this->objPageEntry->filename.'?v='.$this->objPageEntry->fileversion.'"/>
                     </div>';
                  }
                  $strTopPostHtmlOutput .= '
                      '.(($this->objPageEntry->shortdescription != '') ? '<p>'.htmlentities($this->objPageEntry->shortdescription, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</p>' : $this->objPageEntry->description).'
                      <a href="'.$this->objPageEntry->url.'"'.(($this->objPageEntry->target !== false) ? ' target="'.$this->objPageEntry->target.'"' : '').'>Weiter lesen...</a>
                    </div>';
  
                }else{
                  $this->objPage->setCreateDate($this->objPageEntry->created);
  
                  $strLinkItemsHtmlOutput .= '
                      <div class="divListItemImg">';
                  if($this->objPageEntry->filename != ''){
                    $strLinkItemsHtmlOutput .= '
                        <div class="divListItemImgLeft">
                          <a href="'.$this->objPageEntry->url.'"'.(($this->objPageEntry->target !== false) ? ' target="'.$this->objPageEntry->target.'"' : '').'><img title="'.$this->objPageEntry->filetitle.'" alt="'.$this->objPageEntry->filetitle.'" src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$this->objPageEntry->filepath.$strThumbImageFolder.'/'.$this->objPageEntry->filename.'?v='.$this->objPageEntry->fileversion.'"/></a>
                        </div>';
                  }
                  $strLinkItemsHtmlOutput .= '
                        <div class="divListItemImgRight">
                          <a href="'.$this->objPageEntry->url.'"'.(($this->objPageEntry->target !== false) ? ' target="'.$this->objPageEntry->target.'"' : '').'>'.htmlentities($this->objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a><br/>
                          <span>Erstellt am</span> <span class="black">'.$this->objPage->getCreateDate().'</span>
                        </div>
                        <div class="clear"></div>
                      </div>';
                }
                $counter++;
              }
            }
  
            $strReturn .= $strTopPostHtmlOutput;
            if($strLinkItemsHtmlOutput != ''){
              $strReturn .= '
                  <div class="divListContainer">
                    <h3>Weitere Themen</h3>';
              $strReturn .= $strLinkItemsHtmlOutput;
              $strReturn .= '
                    <div class="clear"></div>
                  </div>';
            }
            $strReturn .= '
                  <div class="clear"></div>
                </div>';
          }
        }
      }
    }
  
    return $strReturn;
  }
  
  /**
   * getCategoryIcons
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getCategoryIcons(){
    //TODO default category icons
  }
  
  /**
   * getOverview
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getOverview($strImageFolderCol1 = '220x', $strImageFolderCol2 = '180x', $strImageFolderList = '80x80'){
    $strReturn = '';
      
    $arrOverview = $this->objPage->getOverviewContainer();
  
    $strReturn = '';
    if(count($arrOverview) > 0){
      $strReturn .= '
        <div class="overview">';
      foreach($arrOverview as $key => $objPageContainer){
        if(count($objPageContainer) > 0){
          $arrDestinationSpecifics = array();
          
          if($objPageContainer->getContainerTitle() != ''){
            $strReturn .= '
                <h3>'.htmlentities($objPageContainer->getContainerTitle(), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</h3>';
          }
  
          $arrPageEntries = $objPageContainer->getEntries();
  
          switch($objPageContainer->getEntryViewType()){
            case $this->core->config->viewtypes->col1->id:
              foreach($arrPageEntries as $objPageEntry){
                $strDescription = '';
                if($objPageEntry->shortdescription != ''){
                  $strDescription = nl2br(htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $this->core->sysConfig->encoding->default));
                }else if($objPageEntry->description != ''){
                  if(strlen($objPageEntry->description) > 300){
                    $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 300))).' ...';
                  }else{
                    $strDescription = strip_tags($objPageEntry->description);
                  }
                }
  
                $strItem = '
                  <div class="item pBottom20">
                    <div class="headline"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></div>
                    <div class="text">';
                if($strDescription != ''){
                  $strItem .= '<p>'.$strDescription.'</p>';
                }                    
                $strItem .= '
                      &raquo; <a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.$this->objTranslate->_('more_information').'</a>                
                    </div>
                    <div class="clear"></div>
                  </div>';
                $strReturn .= $strItem;
              }              
              break;
  
            case $this->core->config->viewtypes->col1_img->id:
              foreach($arrPageEntries as $objPageEntry){
                $strDescription = '';
                if($objPageEntry->shortdescription != ''){
                  $strDescription = nl2br(htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $this->core->sysConfig->encoding->default));
                }else if($objPageEntry->description != ''){
                  if(strlen($objPageEntry->description) > 300){
                    $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 300))).' ...';
                  }else{
                    $strDescription = strip_tags($objPageEntry->description);
                  }
                }
                
                $strItem = '
                  <div class="item pBottom20">
                    <div class="headline"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></div>';
                if($objPageEntry->filename != ''){
                  $strItem .= '
                    <div class="icon"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'><img src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objPageEntry->filepath.$strImageFolderCol1.'/'.$objPageEntry->filename.'?v='.$objPageEntry->fileversion.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/></a></div>';
                }
                $strItem .= '
                    <div class="text">';
                if($strDescription != ''){
                  $strItem .= '<p>'.$strDescription.'</p>';
                }                    
                $strItem .= '
                      &raquo; <a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.$this->objTranslate->_('more_information').'</a>
                    </div>
                    <div class="clear"></div>
                  </div>';
                $strReturn .= $strItem;
              }
              break;
  
            case $this->core->config->viewtypes->col2->id:
  
              $strReturn .= '
                  <div class="col2">';
  
              $counter = 0;
              foreach($arrPageEntries as $objPageEntry){
                $strDescription = '';
                if($objPageEntry->shortdescription != ''){
                  $strDescription = nl2br(htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $this->core->sysConfig->encoding->default));
                }else if($objPageEntry->description != ''){
                  if(strlen($objPageEntry->description) > 300){
                    $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 300))).' ...';
                  }else{
                    $strDescription = strip_tags($objPageEntry->description);
                  }
                }
                
                $strItem = '
                  <div class="item pBottom20'.(($counter % 2 == 0) ? ' pRight100' : '').'">
                    <div class="headline"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></div>
                    <div class="text">';
                if($strDescription != ''){
                  $strItem .= '<p>'.$strDescription.'</p>';
                }                    
                $strItem .= '
                      &raquo; <a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.$this->objTranslate->_('more_information').'</a>
                    </div>
                    <div class="clear"></div>
                  </div>';
                if($counter % 2 == 1){
                  $strItem .= '
                    <div class="clear"></div>';
                }
                $strReturn .= $strItem;
                $counter++;
              }              
              $strReturn .= '
                    <div class="clear"></div>
                  </div>';            
              break;
  
            case $this->core->config->viewtypes->col2_img->id:
              $strReturn .= '
                  <div class="col2">';
  
              $counter = 0;
              foreach($arrPageEntries as $objPageEntry){
                $strDescription = '';
                if($objPageEntry->shortdescription != ''){
                  $strDescription = nl2br(htmlentities($objPageEntry->shortdescription, ENT_COMPAT, $this->core->sysConfig->encoding->default));
                }else if($objPageEntry->description != ''){
                  if(strlen($objPageEntry->description) > 300){
                    $strDescription = strip_tags(substr($objPageEntry->description, 0, strpos($objPageEntry->description, ' ', 300))).' ...';
                  }else{
                    $strDescription = strip_tags($objPageEntry->description);
                  }
                }
                
                $strItem = '
                  <div class="item pBottom20'.(($counter % 2 == 0) ? ' pRight100' : '').'">
                    <div class="headline"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></div>';
                if($objPageEntry->filename != ''){
                  $strItem .= '
                    <div class="icon"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'><img src="'.$this->core->config->domains->static->components.$this->core->sysConfig->media->paths->imgbase.$objPageEntry->filepath.$strImageFolderCol2.'/'.$objPageEntry->filename.'?v='.$objPageEntry->fileversion.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/></a></div>';
                }
                $strItem .= '
                    <div class="text">';
                if($strDescription != ''){
                  $strItem .= '<p>'.$strDescription.'</p>';
                }                    
                $strItem .= '
                      &raquo; <a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.$this->objTranslate->_('more_information').'</a>
                    </div>
                    <div class="clear"></div>
                  </div>';
                if($counter % 2 == 1){
                  $strItem .= '
                    <div class="clear"></div>';
                }
                $strReturn .= $strItem;                
                $counter++;
              }
              $strReturn .= '
                    <div class="clear"></div>
                  </div>';
              break;
  
            case $this->core->config->viewtypes->list->id:
  
              $strReturn .= '
                  <div class="list">';
              foreach($arrPageEntries as $objPageEntry){
                $strItem = '
                    <div class="item">                    
                      &raquo; <a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                    </div>';
                $strReturn .= $strItem;
              }              
              
              $strReturn .= '
                  </div>';
              break;
  
            case $this->core->config->viewtypes->list_img->id:
  
              $strReturn .= '
                  <div class="list">';
              foreach($arrPageEntries as $objPageEntry){
                $strItem = '
                    <div class="item">';
                if($objPageEntry->filename != ''){
                  $strItem .= '
                      <div class="icon"><a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'><img src="'.$this->core->sysConfig->media->paths->imgbase.$objPageEntry->filepath.$strImageFolderList.'/'.$objPageEntry->filename.'?v='.$objPageEntry->fileversion.'" alt="'.$objPageEntry->filetitle.'" title="'.$objPageEntry->filetitle.'"/></a></div>';
                }
                $strItem .= '
                      <div class="text">
                        <a href="'.$objPageEntry->url.'"'.(($objPageEntry->target !== false) ? ' target="'.$objPageEntry->target.'"' : '').'>'.htmlentities($objPageEntry->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                      </div>
                      <div class="clear"></div>
                    </div>';
                $strReturn .= $strItem;
              }            
              $strReturn .= '
                    <div class="clear"></div>
                  </div>';
              break;
          }
        }
      }
      $strReturn .= '
          <div class="clear"></div>
        </div>';      
    }
    return $strReturn;
  }
    
  /**
   * getProductOverview
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getProductOverview(){
    //TODO default product overview
  }
  
  /**
   * getProductCarousel
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getProductCarousel(){
    //TODO default product overview
  }
  
  /**
   * getStarpageProducts
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getStarpageProducts(){
    //TODO default product overview
  }
  
  /**
   * getCourseOverview
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getCourseOverview(){
    //TODO default product overview
  }
  
  /**
   * getCourseDetail
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getCourseDetail(){
    //TODO default product overview
  }
  
  /**
   * getSimilarCourses
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getSimilarCourses(){
    //TODO default product overview
  }  
  
  /**
   * getEventOverview
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getEventOverview(){
    //TODO default product overview
  } 
    
  /**
   * getSubPagesOverview
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getSubPagesOverview(){
    //TODO default product overview
  }
        
  /**
   * getPressOverview
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getPressOverview(){
    //TODO default product overview
  }
  
  /**
   * getDownloadCenter
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getDownloadCenter(){
    //TODO default product overview
  }
  
  /**
   * getLanguageChooser
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getLanguageChooser(){
    //TODO default product overview
  }
  
  /**
   * getVideos
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @return string
   */
  public function getVideos(){
    //TODO default videos
  }
  
  /**
   * getIframe
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getIframe($strQueryString, $strWidth, $strHeight){
    $strReturn = '';
    
    $strIframeUrl = htmlentities($this->objPage->getFieldValue('iframe'), ENT_COMPAT, $this->core->sysConfig->encoding->default);
    if($strQueryString != ''){
      if(strpos($strIframeUrl, '?') !== false){
        $strIframeUrl = $strIframeUrl.'&'.$strQueryString; 
      }else{
        $strIframeUrl = $strIframeUrl.'?'.$strQueryString;  
      }
    }
    
    $strIframeWidth = ($this->objPage->getFieldValue('iframeWidth') != '') ? htmlentities($this->objPage->getFieldValue('iframeWidth'), ENT_COMPAT, $this->core->sysConfig->encoding->default) : $strWidth;
    $strIframeHeight = ($this->objPage->getFieldValue('iframeHeight') != '') ? htmlentities($this->objPage->getFieldValue('iframeHeight'), ENT_COMPAT, $this->core->sysConfig->encoding->default) : $strHeight;
    
    if($strIframeUrl != ''){
      $strReturn .= '<iframe src="'.$strIframeUrl.'" width="'.$strIframeWidth.'" height="'.$strIframeHeight.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>';
    }
    return $strReturn;  
  }
  
/**
   * getForm
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @return string
   */
  public function getForm($strFormId, $intRootLevelId, $intPageId){
    $strReturn = '';
    $strFields = '';
    $blnHasFileUpload = false;
    
    $arrFormFields = $this->objPage->getCategoriesValues('form_fields');
    $arrMandatoryFields = $this->objPage->getCategoriesValues('form_mandatory_fields');
    
    if(count($arrFormFields) > 0){
      
      $arrMandatory = array();
      if(count($arrMandatoryFields) > 0){
        foreach($arrMandatoryFields as $objMandatoryField){
          $arrMandatory[] = $objMandatoryField->code;   
        }  
      }
      
      $counter = 0;
      foreach($arrFormFields as $objField){
        
        $strFieldCssClass = 'fieldLeft';
        if($counter % 2 == 1){
          $strFieldCssClass = 'fieldRight';  
        }
        
        $strMandatory = '';
        $strMandatoryCssClass = '';
        if(array_search($objField->code, $arrMandatory) !== false){
          $strMandatory = ' *';
          $strMandatoryCssClass = ' class="mandatory"';  
        }
        
        $strFields .= '    
            <div class="'.$strFieldCssClass.'">
              <label id="lbl_'.$objField->code.'" for="'.$objField->code.'">'.htmlentities($objField->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).$strMandatory.'</label><br/>';
        
        switch($objField->code){
          case 'salutation' :
            $strFields .= '              
              <select id="'.$objField->code.'" name="'.$objField->code.'" size="1"'.$strMandatoryCssClass.'>
                <option value="">'.$this->objTranslate->_('Please_choose', false).'</option>
                <option value="'.$this->objTranslate->_('Mr.').'">'.$this->objTranslate->_('Mr.').'</option>
                <option value="'.$this->objTranslate->_('Ms.').'">'.$this->objTranslate->_('Ms.').'</option>
              </select>';
            break;
            
          case 'type' :
            $strFields .= '              
              <select id="'.$objField->code.'" name="'.$objField->code.'" size="1"'.$strMandatoryCssClass.'>
                <option value="">'.$this->objTranslate->_('Please_choose', false).'</option>                
              </select>';
            break;
            
          case 'country' :
            $strFields .= '
              <select id="'.$objField->code.'" name="'.$objField->code.'" size="1"'.$strMandatoryCssClass.'>
                <option value="">'.$this->objTranslate->_('Please_choose', false).'</option>                
              </select>';
            // '.HtmlOutput::getOptionsOfSQL($this->core, 'SELECT tbl.id AS VALUE, categoryTitles.title AS DISPLAY FROM categories AS tbl INNER JOIN categoryTitles ON categoryTitles.idCategories = tbl.id AND categoryTitles.idLanguages = '.$this->core->intLanguageId.' WHERE tbl.idRootCategory = 268 AND (tbl.depth-1) != 0 ORDER BY categoryTitles.title ASC').'
            break;
            
          case 'message' :
            $strFields .= '
              <textarea id="'.$objField->code.'" name="'.$objField->code.'"></textarea>';
            break;
            
          case 'attachment' :
            $blnHasFileUpload = true;
            $strFields .= '
              <input type="file" id="'.$objField->code.'" name="'.$objField->code.'"/>';
            break;
            
          default :
            $strFields .= '
              <input type="text" id="'.$objField->code.'" name="'.$objField->code.'" value=""'.$strMandatoryCssClass.'/>';             
            break;
        }
        
        $strFields .= '
           </div>';
        
        if($counter % 2 == 1){
          $strFields .= '
            <div class="clear"></div>';  
        }
        $counter++;
      }
      
      $strReturn .= '
        <form id="'.$strFormId.'" name="'.$strFormId.'"  action="/zoolu-website/datareceiver" method="post" onsubmit="return false;"'.(($blnHasFileUpload) ? ' enctype="multipart/form-data"' : '').'>
          '.$strFields.'
          <div class="fieldLeft required">
            <span>'.$this->objTranslate->_('Fields_with_*_are_mandatory').'</span>  
          </div>          
          <div class="fieldRight buttonContainer">
            <div class="buttonBox" onclick="myDefault.submitForm(\''.$strFormId.'\');">
              <div class="left">&nbsp;</div>
              <div class="center">'.$this->objTranslate->_('Send').'</div>
              <div class="right">&nbsp;</div>
              <div class="clear"></div>
            </div>
          </div>
          <div class="clear"></div>
          <div class="legalNotes"><input type="checkbox" id="checkLegalnotes" name="checkLegalnotes"> <label for="checkLegalnotes">'.$this->objTranslate->_('LegalNotes', false).'</label></div>
          <input type="hidden" id="idRootLevels" name="idRootLevels" value="'.$intRootLevelId.'"/>
          <input type="hidden" id="idPage" name="idPage" value="'.$intPageId.'"/>
          <input type="hidden" id="redirectUrl" name="redirectUrl" value="http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'"/>
          <input type="hidden" id="subject" name="subject" value="'.$this->objPage->getFieldValue('subject').'"/>
          <input type="hidden" id="sender_name" name="sender_name" value="'.Crypt::encrypt($this->core, $this->core->config->crypt->key, $this->objPage->getFieldValue('sender_name')).'"/>
          <input type="hidden" id="sender_mail" name="sender_mail" value="'.Crypt::encrypt($this->core, $this->core->config->crypt->key, $this->objPage->getFieldValue('sender_mail')).'"/>
          <input type="hidden" id="receiver_name" name="receiver_name" value="'.Crypt::encrypt($this->core, $this->core->config->crypt->key, $this->objPage->getFieldValue('receiver_name')).'"/>
          <input type="hidden" id="receiver_mail" name="receiver_mail" value="'.Crypt::encrypt($this->core, $this->core->config->crypt->key, $this->objPage->getFieldValue('receiver_mail')).'"/>
        </form>';  
    }
    
    return $strReturn;
  }
  
  /**
   * getFormSuccess
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @return string
   */
  public function getFormSuccess(){
    $strReturn = '';    
    $strReturn .= $this->objPage->getFieldValue('success_message');    
    return $strReturn;
  }
  
  /**
   * getPressContact
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getPressContact(){
    //TODO default product overview
  }
  
  /**
   * getContact
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @return string
   */
  public function getContact($strTitle = ''){
    $strReturn = '';
    
    $objPageContacts = $this->objPage->getContactsValues('contact');
 
    if(count($objPageContacts) > 0){
      $strReturn .= '
            <div class="contact">
              <h3>'.(($strTitle != '') ? $strTitle : $this->objTranslate->_('Contact')).'</h3>';
      foreach($objPageContacts as $objContact){
        $strReturn .= '
              <div class="item">
                <div class="name">'.htmlentities((($objContact->acTitle != '') ? $objContact->acTitle.' ' : '').$objContact->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
                <div class="position">'.htmlentities($objContact->position, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
                <!--<div class="address">
                  '.strip_tags($objContact->street).'</br>
                  '.$objContact->zip.' '.$objContact->city.'</br>
                  '.htmlentities($objContact->countryTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'
                </div>-->
                <div class="numbers">
                  '.($objContact->phone != '' ? $this->objTranslate->_('Tel').'. '.$objContact->phone.'<br/>' : '').'
                  '.($objContact->fax != '' ? $this->objTranslate->_('Fax').' '.$objContact->fax : '').'                
                </div>
                <div class="mail">
                  <a href="mailto:'.$objContact->email.'">'.$this->objTranslate->_('Email').'</a>
                </div>
              </div>';
      }
      $strReturn .= '
            </div">';
    }
    return $strReturn;
  }
  
  /**
   * getSpeakers
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getSpeakers($strTitle = ''){
    //TODO default product overview
  }
  
  /**
   * getLocationContacts
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @return string
   */
  public function getLocationContacts($strCountry = '', $strProvince = ''){
    //TODO default product overview
  }
  
  /**
   * getPressPics
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getPressPics(){
    //TODO default product overview
  }
  
  /**
   * getExternalLinks
   * @author Thomas Schedler <tsh@massiveart.com>
   * @return string
   */
  public function getExternalLinks(){
    $strReturn = '';
    
    $objMyMultiRegion = $this->objPage->getRegion(50); //50 is the default external linkk region
  
    if($objMyMultiRegion instanceof GenericElementRegion){
      $strReturn .= '<div class="links">';
      
      if($this->objPage->getField('title_externe_links') && $this->objPage->getFieldValue('title_externe_links') != ''){
        $strReturn .= '<h2>'.htmlentities($this->objPage->getFieldValue('title_externe_links'), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</h2>';
      }
      
      foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){
        $strTitle = htmlentities($objMyMultiRegion->getField('link_title')->getInstanceValue($intRegionInstanceId), ENT_COMPAT, $this->core->sysConfig->encoding->default);
        $strUrl = $objMyMultiRegion->getField('link_url')->getInstanceValue($intRegionInstanceId);
        if(filter_var($strUrl, FILTER_VALIDATE_URL)){
          $strReturn .= '<div class="item"><a href="'.$strUrl.'">'.$strTitle.'</a></div>';
        }else if(filter_var('http://'.$strUrl, FILTER_VALIDATE_URL)){
          $strReturn .= '<div class="item"><a href="http://'.$strUrl.'">'.$strTitle.'</a></div>';
        }
      }
      $strReturn .= '</div>';
    }
    
    return $strReturn;
  }
  
  public function getGoogleMapLat(){
    return $this->objPage->getFieldValue('gmaps')->latitude;  
  }
  
  public function getGoogleMapLng(){
    return $this->objPage->getFieldValue('gmaps')->longitude;  
  }
  
  /**
   * getCreatorName
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getCreatorName(){
    return $this->objPage->getCreatorName();
  }
  
  /**
   * get_user_changer
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getChangeUserName(){
    return $this->objPage->getChangeUserName();
  }
  
  /**
   * get_user_publisher
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPublisherName(){
    return $this->objPage->getPublisherName();
  }
  
  /**
   * get_date_created
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getCreateDate(){
    return $this->objPage->getCreateDate();
  }
  
  /**
   * get_date_changed
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getChangeDate(){
    return $this->objPage->getChangeDate();
  }
  
  /**
   * get_date_published
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPublishDate(){
    return $this->objPage->getPublishDate();
  }
    
  /**
   * setPage    
   * @param Page $objPage   
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function setPage(Page $objPage){
    $this->objPage = $objPage;
  }
  
  /**
   * setTranslate    
   * @param Zend_Translate $objTranslate   
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function setTranslate(Zend_Translate $objTranslate){
    $this->objTranslate = $objTranslate;
  }
  
  /**
   * getTranslate    
   * @return Zend_Translate $objTranslate   
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function getTranslate(){
    return $this->objTranslate;
  }
}