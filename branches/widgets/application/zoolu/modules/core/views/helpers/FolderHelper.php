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
 * FolderHelper
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-23: Thomas Schedler
 * 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class FolderHelper {
  
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
   * getFolderTree 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getFolderTree($objRowset, $intFolderId) {
    $this->core->logger->debug('core->views->helpers->FolderHelper->getFolderTree()');
    
    $strOutput = '';
    
    if(count($objRowset) > 0){
      $blnFolderChilds = false;
      $intMainFolderDepth = 0;
      $strOutput .= '<div id="olnavitem'.$objRowset[0]->idRootLevels.'" class="olnavrootitem">
                       <div style="position:relative;">
                         <a href="#" onclick="myFolder.selectParentRootFolder('.$objRowset[0]->idRootLevels.'); return false;"><div class="icon img_folder_on"></div>'.htmlentities($objRowset[0]->rootLevelTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                       </div>
                     </div>';
     
      foreach ($objRowset as $objRow){           
        if($objRow->id == $intFolderId){
          $intMainFolderDepth = $objRow->depth;
          $blnFolderChilds = true;
        }else if($blnFolderChilds == false || $objRow->depth <= $intMainFolderDepth){
          $blnFolderChilds = false;
          $intFolderDepth = $objRow->depth + 1;
          $strOutput .= '<div id="olnavitem'.$objRow->id.'" class="olnavrootitem">
                           <div style="position:relative; padding-left:'.(20*$intFolderDepth).'px">
                             <a href="#" onclick="myFolder.selectParentFolder('.$objRow->id.'); return false;"><div class="icon img_folder_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
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
   * getFolderContentList
   * @param object $objRowset
   * @param integer $intFolderId 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFolderContentList($objRowset, $intFolderId){
    $this->core->logger->debug('core->views->helpers->FolderHelper->getFolderContentList()');
    
    $strOutput = '';
    
    if(count($objRowset) > 0){
      foreach($objRowset as $objRow){
        
      	$strStatus = ($objRow->pageStatus == $this->core->sysConfig->status->live) ? 'on' : 'off' ;
      	
        if($objRow->isStartPage){
          $strOutput .= '
                      <tr class="listrow" id="Row'.$objRow->idPage.'">
                        <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->idPage.'" name="listSelect'.$objRow->idPage.'" id="listSelect'.$objRow->idPage.'"/></td>
                        <td class="rowicon"><div class="img_startpage_'.$strStatus.'"></div></td>
                        <td class="rowtitle">'.htmlentities($objRow->pageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).' (Startseite)</td>
                        <td class="rowauthor" colspan="2"></td>
                      </tr>';	
        }else{
          $strOutput .= '
                      <tr class="listrow" id="Row'.$objRow->idPage.'">
                        <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->idPage.'" name="listSelect'.$objRow->idPage.'" id="listSelect'.$objRow->idPage.'"/></td>
                        <td class="rowicon"><div class="img_page_'.$strStatus.'"></div></td>
                        <td class="rowtitle">'.htmlentities($objRow->pageTitle, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</td>
                        <td class="rowauthor" colspan="2"></td>
                      </tr>';	
        }
      	  	
      }	
    }
    
    /**
     * return html output
     */
    return $strOutput;
  	
  	
  }
}

?>