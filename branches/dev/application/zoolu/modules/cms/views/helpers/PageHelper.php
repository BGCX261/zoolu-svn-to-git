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
 * 1.0, 2009-01-09: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

require_once (dirname(__FILE__).'/../../../media/views/helpers/ViewHelper.php');

class PageHelper {
  
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
   * getFilesOutput 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFilesOutput($rowset, $strFieldName, $strViewType){
    $this->core->logger->debug('cms->views->helpers->PageHelper->getFilesOutput()');
    
    $this->objViewHelper = new ViewHelper();
      
    $strOutput = '';
    foreach ($rowset as $row){
      if($strViewType != '' && $strViewType == $this->core->sysConfig->viewtypes->thumb){    
    	  if($row->isImage){
	        if($row->xDim < $row->yDim){
	          $strMediaSize = 'height="100"';
	        }else{
	          $strMediaSize = 'width="100"';  
	        }  
          $strOutput .= '<div style="position: relative;" class="mediaitem" fileid="'.$row->id.'" id="'.$strFieldName.'_mediaitem_'.$row->id.'">
	                         <table>
	                           <tbody>
	                             <tr>
	                               <td>
	                                 <img src="'.sprintf($this->core->sysConfig->media->paths->thumb, $row->path).$row->filename.'?v='.$row->version.'" id="Img'.$row->id.'" '.$strMediaSize.'/>
	                               </td>
	                             </tr>
	                           </tbody>
	                         </table>                      
	                         <div class="itemremovethumb" id="'.$strFieldName.'_remove'.$row->id.'" onclick="myForm.removeItem(\''.$strFieldName.'\', \''.$strFieldName.'_mediaitem_'.$row->id.'\', '.$row->id.'); return false;"></div>
	                       </div>';
        }
      }else{
      	if($row->isImage){
	      	$strOutput .= '<div class="fileitem" fileid="'.$row->id.'" id="'.$strFieldName.'_fileitem_'.$row->id.'">
						               <div class="olfileleft"></div>
	      	                 <div class="itemremovelist" id="'.$strFieldName.'_remove'.$row->id.'" onclick="myForm.removeItem(\''.$strFieldName.'\', \''.$strFieldName.'_fileitem_'.$row->id.'\', '.$row->id.'); return false;"></div>  
						               <div class="olfileitemicon"><img width="32" height="32" src="'.sprintf($this->core->sysConfig->media->paths->icon32, $row->path).$row->filename.'?v='.$row->version.'" id="File'.$row->id.'" alt="'.htmlentities($row->description, ENT_COMPAT, $this->core->sysConfig->encoding->default).'"/></div>
						               <div class="olfileitemtitle">'.htmlentities((($row->title == '' && (isset($row->alternativTitle) || isset($row->fallbackTitle))) ? ((isset($row->alternativTitle) && $row->alternativTitle != '') ? $row->alternativTitle : $row->fallbackTitle) : $row->title), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
						               <div class="clear"></div>
						             </div>';
      	}else{
      	  $strOutput .= '<div class="fileitem" fileid="'.$row->id.'" id="'.$strFieldName.'_fileitem_'.$row->id.'">
                           <div class="olfileleft"></div>
      	                   <div class="itemremovelist" id="'.$strFieldName.'_remove'.$row->id.'" onclick="myForm.removeItem(\''.$strFieldName.'\', \''.$strFieldName.'_fileitem_'.$row->id.'\', '.$row->id.'); return false;"></div>  
                           <div class="olfileitemicon"><img width="32" height="32" src="'.$this->objViewHelper->getDocIcon($row->extension, 32).'" id="File'.$row->id.'" alt="'.htmlentities($row->description, ENT_COMPAT, $this->core->sysConfig->encoding->default).'"/></div>
                           <div class="olfileitemtitle">'.htmlentities((($row->title == '' && (isset($row->alternativTitle) || isset($row->fallbackTitle))) ? ((isset($row->alternativTitle) && $row->alternativTitle != '') ? $row->alternativTitle : $row->fallbackTitle) : $row->title), ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>              
                           <div class="clear"></div>
                         </div>';		
      	}
      }
    }    
    return $strOutput.'<div id="divClear_'.$strFieldName.'" class="clear"></div>';
  }
  
  /**
   * getContactOutput 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getContactOutput($rowset, $strFieldName){
    $this->core->logger->debug('cms->views->helpers->PageHelper->getContactOutput()');
  
    $strOutput = '';
    foreach ($rowset as $row){ 
      $strOutput .= '<div class="contactitem" fileid="'.$row->id.'" id="'.$strFieldName.'_contactitem_'.$row->id.'">
                       <div class="olcontactleft"></div>
                       <div class="itemremovelist" id="'.$strFieldName.'_remove_'.$row->id.'" onclick="myForm.removeItem(\''.$strFieldName.'\', \''.$strFieldName.'_contactitem_'.$row->id.'\', '.$row->id.'); return false;"></div>  
                       <div class="olcontactitemicon">';
      if($row->filename != ''){
        $strOutput .= '<img width="32" height="32" src="'.sprintf($this->core->sysConfig->media->paths->icon32, $row->filepath).$row->filename.'?v='.$row->fileversion.'" id="Contact'.$row->id.'" alt="'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'"/>';
      }
      $strOutput .= '  </div>
                       <div class="olcontactitemtitle">'.htmlentities($row->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
                       <div class="clear"></div>
                     </div>';
    }    
    
    /**
     * add the scriptaculous sortable funcionality
     */
     $strOutput .= '<script type="text/javascript" language="javascript">/* <![CDATA[ */
     myForm.initSortable(\''.$strFieldName.'\', \'divContactContainer_'.$strFieldName.'\', \'contactitem\', \'div\', \'itemid\', \'vertical\');
     /* ]]> */</script>';
     
    return $strOutput.'<div id="divClear_'.$strFieldName.'" class="clear"></div>';
  }

  /**
   * getDashboardListOutput 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getDashboardListOutput($objRowset){
    $this->core->logger->debug('cms->views->helpers->PageHelper->getDashboardListOutput()');

    $strOutput = '';
    foreach ($objRowset as $objRow){
      $changed = new DateTime($objRow->changed);
      $created = new DateTime($objRow->created);
      
    	$strOutput .= '
                      <tr class="listrow" id="Row'.$objRow->idPage.'">
                        <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->idPage.'" name="listSelect'.$objRow->idPage.'" id="listSelect'.$objRow->idPage.'"/></td>
                        <td class="rowtitle"><a href="#" onclick="myNavigation.loadNavigationTree('.$objRow->idPage.', \'page\'); return false;">'.htmlentities($objRow->pageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a></td>
                        <td class="rowauthor">'.$objRow->changeUser.'</td>
                        <td class="rowchanged">'.$changed->format('d.m.y, H:i').'</td>
                        <td class="rowcreated" colspan="2">'.$created->format('d.m.y, H:i').'</td>
                      </tr>';	
    }    
    return $strOutput;
    
  }
}

?>