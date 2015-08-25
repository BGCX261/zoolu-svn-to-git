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
 * @package    application.zoolu.modules.core.media.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * ViewHelper
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-19: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class ViewHelper {
  
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
   * getThumbView
   * @param object $rowset 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getThumbView($objRowset, $intSliderValue){
    $this->core->logger->debug('media->views->helpers->ViewHelper->getThumbView()');
  	
    $strOutput = '';
    if(count($objRowset) > 0){
	    foreach ($objRowset as $objRow) {
	      
	      $strStartWidth = 100;
	      $strStyleOutput = 'width:'.$intSliderValue.'px;';     
	      $strDivThumbPosImgStyle = ''; //position:absolute; bottom:0; ';
	      if($objRow->isImage && ($objRow->xDim < $objRow->yDim)){
	        /**
	         * calculate width of upright format images
	         */
	        $dblPercentHeight = ($intSliderValue * 100)/$objRow->yDim;
	        $intRoundedWidth = round(($objRow->xDim * $dblPercentHeight)/100);
	        
	        /**
	         * calculate width of upright format images
	         */
	        $dblPercentStartHeight = ($strStartWidth * 100)/$objRow->yDim;
	        $intRoundedStartWidth = round(($objRow->xDim * $dblPercentStartHeight)/100);
	        
	        /**
	         * set values to variables
	         */
	        $strStyleOutput = 'width:'.$intRoundedWidth.'px;';
	        $strStartWidth = $intRoundedStartWidth;
	        $strDivThumbPosImgStyle = '';
	      }
	      
	      if(strpos($objRow->mimeType, 'image/') !== false){
	        
	        /**
	         * image output
	         */
	        $strOutput .= '<div id="divThumbContainerImg'.$objRow->id.'" class="thumbcontainer" style="height:'.($intSliderValue + 10).'px; width:'.($intSliderValue + 10).'px;">
	                        <table>
	                          <tr>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_top_left.png" width="5" height="5"/></td>
	                            <td height="5" style="background-color:#e4e4e4;font-size:0;line-height:0;">&nbsp;</td>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_top_right.png" width="5" height="5"/></td>
	                          </tr>
	                          <tr>
	                            <td width="5" style="background-color:#e4e4e4;">&nbsp;</td>
	                            <td id="tdThumbImg'.$objRow->id.'" fileid="'.$objRow->id.'" class="tdthumbcontainer" valign="middle" align="center" style="width:'.$intSliderValue.'px; height:'.$intSliderValue.'px;">
	                              <div id="divThumbPosImg'.$objRow->id.'" class="thumbimgcontainer" style="'.$strDivThumbPosImgStyle.$strStyleOutput.'" ondblclick="myMedia.getSingleFileEditForm('.$objRow->id.');">
	                                <table>
	                                  <tr>
	                                    <td><img id="Img'.$objRow->id.'" src="'.$this->core->sysConfig->media->paths->thumb.$objRow->filename.'" style="'.$strStyleOutput.'" class="thumb" startWidth="'.$strStartWidth.'"/></td>
	                                    <!--<td class="thumbshadowright">&nbsp;</td>-->
	                                  </tr>
	                                  <!--<tr>
	                                    <td class="thumbshadowbottom">&nbsp;</td>
	                                    <td class="thumbshadowcorner">&nbsp;</td>
	                                  </tr>-->
	                                </table>
	                              </div>
	                            </td>
	                            <td width="5" style="background-color:#e4e4e4;">&nbsp;</td>
	                          </tr>
	                          <tr>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_bttm_left.png" width="5" height="5"/></td>
	                            <td height="5" style="background-color:#e4e4e4;font-size:0;line-height:0;">&nbsp;</td>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_bttm_right.png" width="5" height="5"/></td>
	                          </tr>
	                        </table>                                   
	                      </div>';
	
	      }else{
	        
	        /**
	         * document output with icon
	         */
	        $strOutput .= '<div id="divThumbContainerDoc'.$objRow->id.'" class="thumbcontainer" style="height:'.($intSliderValue + 10).'px; width:'.($intSliderValue + 10).'px;">
	                        <table>
	                          <tr>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_top_left.png" width="5" height="5"/></td>
	                            <td height="5" style="background-color:#e4e4e4;font-size:0;line-height:0;">&nbsp;</td>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_top_right.png" width="5" height="5"/></td>
	                          </tr>
	                          <tr>
	                            <td width="5" style="background-color:#e4e4e4;">&nbsp;</td>
	                            <td id="tdThumbDoc'.$objRow->id.'" fileid="'.$objRow->id.'" class="tdthumbcontainer" valign="middle" align="center" style="width:'.$intSliderValue.'px; height:'.$intSliderValue.'px;">
	                              <div id="divThumbPosDoc'.$objRow->id.'" class="thumbimgcontainer" style="'.$strDivThumbPosImgStyle.'width:'.$strStartWidth.'px;" ondblclick="myMedia.getSingleFileEditForm('.$objRow->id.');">
	                                <table>
	                                  <tr>
	                                    <td><img id="Doc'.$objRow->id.'" src="'.$this->getDocIcon($objRow->extension, 32).'" style="width:'.$strStartWidth.'px;" class="thumb" startWidth="'.$strStartWidth.'"/></td>
	                                    <!--<td class="thumbshadowright">&nbsp;</td>-->
	                                  </tr>
	                                  <!--<tr>
	                                    <td class="thumbshadowbottom">&nbsp;</td>
	                                    <td class="thumbshadowcorner">&nbsp;</td>
	                                  </tr>-->
	                                </table>
	                              </div>
	                            </td>
	                            <td width="5" style="background-color:#e4e4e4;">&nbsp;</td>
	                          </tr>
	                          <tr>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_bttm_left.png" width="5" height="5"/></td>
	                            <td height="5" style="background-color:#e4e4e4;font-size:0;line-height:0;">&nbsp;</td>
	                            <td width="5" height="5" style="font-size:0;line-height:0;"><img src="/zoolu/images/main/corner_thumbhov_bttm_right.png" width="5" height="5"/></td>
	                          </tr>
	                        </table>                                   
	                      </div>';
	      }     
	    }	
    }else{
      $strOutput = 'Noch keine Medien vorhanden.';	
    }
    
    return $strOutput;
  }
  
  /**
   * getListView
   * @param object $rowset  
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getListView($objRowset){
    $this->core->logger->debug('media->views->helpers->ViewHelper->getListView()');
    
    $strOutput = '';
    if(count($objRowset) > 0){
	    foreach ($objRowset as $objRow) {
	      
	      $created = new DateTime($objRow->created);
	      
	      if(strpos($objRow->mimeType, 'image/') !== false){
	        $strFileIconSrc = $this->core->sysConfig->media->paths->icon32.$objRow->filename; 
	      }else{        
	        $strFileIconSrc = $this->getDocIcon($objRow->extension, 32);
	      }
	      
	      /**
	       * list row entry
	       */
	      $strOutput .= '<tr id="Row'.$objRow->id.'" class="listrow" fileid="'.$objRow->id.'">
	                      <td colspan="2" class="rowcheckbox"><input type="checkbox" id="listSelect'.$objRow->id.'" name="listSelect'.$objRow->id.'" value="'.$objRow->id.'" class="listSelectRow"/></td>
	                      <td class="rowicon"><img width="32" height="32" src="'.$strFileIconSrc.'" alt="'.htmlentities($objRow->description, ENT_COMPAT, $this->core->sysConfig->encoding->default).'" ondblclick="myMedia.getSingleFileEditForm('.$objRow->id.');"/></td>
	                      <td class="rowtitle">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</td>
	                      <td class="rowauthor">'.$objRow->creator.'</td>
	                      <td colspan="2" class="rowcreated">'.$created->format('d.m.y, H:i').'</td>
	                    </tr>';
	    } 
    }
    
    return $strOutput;
  }
  
  /**
   * getDashboardListOutput 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getDashboardListOutput($objRowset){
    $this->core->logger->debug('media->views->helpers->ViewHelper->getDashboardListOutput()');

    $strOutput = '';
    if(count($objRowset) > 0){
      foreach ($objRowset as $objRow){
	      $created = new DateTime($objRow->created);
	      
	      if(strpos($objRow->mimeType, 'image/') !== false){
	        $strFileIconSrc = $this->core->sysConfig->media->paths->icon32.$objRow->filename;  
	      }else{        
	        $strFileIconSrc = $this->getDocIcon($objRow->extension, 32);
	      }
	      
	      $strOutput .= '
	                      <tr class="listrow" id="Row'.$objRow->id.'">
	                        <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->id.'" name="listSelect'.$objRow->id.'" id="listSelect'.$objRow->id.'"/></td>
	                        <td class="rowicon"><img width="32" height="32" src="'.$strFileIconSrc.'" alt="'.htmlentities($objRow->description, ENT_COMPAT, $this->core->sysConfig->encoding->default).'" ondblclick="myMedia.getSingleFileEditForm('.$objRow->id.');"/></td>
	                        <td class="rowtitle"><a href="#" onclick="myNavigation.loadNavigationTree('.$objRow->idParent.', \'folder\'); return false;">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></td>
	                        <td class="rowauthor">'.$objRow->creator.'</td>
	                        <td class="rowcreated" colspan="2">'.$created->format('d.m.y, H:i').'</td>
	                      </tr>'; 
	    }	
    }  
    return $strOutput;    
  }
  
  /**
   * getEditForm
   * @param object $rowset 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getEditForm($rowset){
  	$this->core->logger->debug('media->views->helpers->ViewHelper->getEditForm()');
  	
  	$strOutput = '';
  	
  	foreach ($rowset as $row) {
      
  	  if(strpos($row->mimeType, 'image/') !== false){
        $strFileIconSrc = $this->core->sysConfig->media->paths->icon32.$row->filename;  
      }else{        
        $strFileIconSrc = $this->getDocIcon($row->extension, 32);
      }

      if($row->description != ''){
        $strDescription = $row->description;
        $strTextareaCss = ' class="textarea"';      
      }else{
        $strDescription = 'Beschreibung hinzufügen...';
        $strTextareaCss = '';
      }
      
  	  if($row->idLanguages != ''){
        $intLanguageId = $row->idLanguages; 
      }else{
        $intLanguageId = $this->core->sysConfig->languages->default->id;
      }
       
  		$strOutput .= '<div class="mediacontainer">
                       <div class="mediaicon"><img width="32" height="32" src="'.$strFileIconSrc.'"/></div>
                       <div class="mediainfos">
                         <div class="mediainfotitle"><input type="text" value="'.$row->title.'" id="FileTitle'.$row->id.'|||'.$intLanguageId.'" name="FileTitle'.$row->id.'|||'.$intLanguageId.'"/></div>
                         <div class="mediainfodescription"><textarea onfocus="myMedia.setFocusTextarea(this.id); return false;" id="FileDescription'.$row->id.'|||'.$intLanguageId.'" name="FileDescription'.$row->id.'|||'.$intLanguageId.'"'.$strTextareaCss.'>'.$strDescription.'</textarea></div>
                         <div class="clear"></div>  
                       </div>
                       <div class="clear"></div> 
                     </div>';  		
  	}
  	return $strOutput;
  }
  
  /**
   * getDocIcon
   * @param string $strDocumentExtension, integer $intSize 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getDocIcon($strDocumentExtension, $intSize){
    
  	switch(strtolower($strDocumentExtension)){
      case 'docx' :
      case 'doc' :
      case 'rtf' :
        //$strDocIcon = '/zoolu/images/icons/docs/icon_word_'.$intSize.'.png';
        $strDocIcon = '/zoolu/images/icons/docs/icon_word.png';
        break;
      case 'xlsx' :
      case 'xls' :
        //$strDocIcon = '/zoolu/images/icons/docs/icon_excel_'.$intSize.'.png';
        $strDocIcon = '/zoolu/images/icons/docs/icon_excel.png';
        break;
      case 'pdf' :
        //$strDocIcon = '/zoolu/images/icons/docs/icon_pdf_'.$intSize.'.png';
        $strDocIcon = '/zoolu/images/icons/docs/icon_pdf.png';
        break;
      case 'ppt' :
      case 'pps' :
      case 'pptx' :
      case 'ppsx' :
      case 'ppz' :
      case 'pot' :
        //$strDocIcon = '/zoolu/images/icons/docs/icon_ppt_'.$intSize.'.png';
        $strDocIcon = '/zoolu/images/icons/docs/icon_ppt.png';
        break;
      case 'zip' :
      case 'rar' :
      case 'tar' :
      case 'ace' :
        //$strDocIcon = '/zoolu/images/icons/docs/icon_zip_'.$intSize.'.png';
        $strDocIcon = '/zoolu/images/icons/docs/icon_compressed.png';
        break;
      case 'avi' :
      case 'mov' :
      case 'swf' :
      case 'mpg' :
      case 'mpeg' :
      case 'wmv' :
      case 'f4v' :
      	$strDocIcon = '/zoolu/images/icons/docs/icon_movie.png';
      	break;
      case 'mp3' :
      case 'wav' :
      case 'f4a' :
      case 'wma' :
      case 'aif' :
        $strDocIcon = '/zoolu/images/icons/docs/icon_audio.png';
      	break;
      default :
        //$strDocIcon = '/zoolu/images/icons/docs/icon_default_'.$intSize.'.png';
        $strDocIcon = '/zoolu/images/icons/docs/icon_unknown.png';
        break;
    }
    return $strDocIcon;
    
  }

}

?>