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
   * getFolderContentList
   * @param object $objRowset
   * @param integer $intFolderId
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFolderContentList($objPaginator, $intFolderId, $strOrderColumn = '', $strOrderSort = ''){
    $this->core->logger->debug('core->views->helpers->FolderHelper->getFolderContentList()');
    $strTbody = '';
    $strThead = '';

    /**
     * Tbody
     */
    $strTbody .= '<tbody>';
    if(count($objPaginator) > 0){
      foreach($objPaginator as $objRow){

      	$strStatus = ($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off' ;
      	
      	if($objRow->elementType == 'global') //FIXME: Replace Hardcode
      	{
      		if($objRow->isStartGlobal) {
      			$strTbody .= '
                      <tr class="listrow" id="Row'.$objRow->id.'">
                        <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->id.'" name="listSelect'.$objRow->id.'" id="listSelect'.$objRow->id.'"/></td>
                        <td class="rowicon"><div class="img_start_'.$strStatus.'"></div></td>
                        <td class="rowtitle">
                          <a onclick="myNavigation.getEditForm('.$objRow->id.', \''.$objRow->elementType.'\', \''.$objRow->genericFormId.'\', '.$objRow->version.', '.$objRow->idTemplates.'); return false;" href="#">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                        </td>
                        <td class="rowauthor">'.$objRow->author.'</td>
                        <td class="rowchanged" colspan="2">'.$objRow->changed.'</td>
                      </tr>';
      		} else {
	          $strTbody .= '
	                    <tr class="listrow" id="Row'.$objRow->id.'">
	                      <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->id.'" name="listSelect'.$objRow->id.'" id="listSelect'.$objRow->id.'"/></td>
	                      <td class="rowicon"><div class="img_'.$objRow->elementType.'_'.$strStatus.'"></div></td>
                        <td class="rowtitle">
                          <a onclick="myNavigation.getEditForm('.$objRow->id.', \''.$objRow->elementType.'\', \''.$objRow->genericFormId.'\', '.$objRow->version.', '.$objRow->idTemplates.'); return false;" href="#">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                        </td>
	                      <td class="rowauthor">'.$objRow->author.'</td>
	                      <td class="rowchanged" colspan="2">'.$objRow->changed.'</td>
	                    </tr>';
      		}
      	} elseif($objRow->elementType == 'folder') { //FIXME: Replace Hardcode
      		  $strTbody .= '
                        <tr class="listrow" id="Row'.$objRow->id.'">
                          <td class="rowcheckbox" colspan="2"><input type="checkbox" class="listSelectRow" value="'.$objRow->id.'" name="listSelect'.$objRow->id.'" id="listSelect'.$objRow->id.'"/></td>
                          <td class="rowicon"><div class="img_folder_'.$strStatus.'"></div></td>
                          <td class="rowtitle">
                            <a onclick="myNavigation.getEditForm('.$objRow->id.', \''.$objRow->elementType.'\', \''.$objRow->genericFormId.'\', '.$objRow->version.'); return false;" href="#">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</a>
                          </td>
                          <td class="rowauthor">'.$objRow->author.'</td>
                          <td class="rowchanged" colspan="2">'.$objRow->changed.'</td>
                        </tr>';
      	}

      }
    }
    $strTbody .= '</tbody>';

    /**
     * Thead
     */
    $strThead .= '<thead>
                     <tr>
                       <th class="topcornerleft"></th>
                       <th class="topcheckbox"></th>
                       <th class="topicon"></th>
                       <th class="toptitle'.(('title' == $strOrderColumn) ? ' sort' : '').'" onclick="myList.sort(\'title\''.(('title' == $strOrderColumn && $strOrderSort == 'asc') ? ', \'desc\'' : ', \'asc\'').')">
                         <div'.(('title' == $strOrderColumn) ? ' class="'.$strOrderSort.'"' : '').'>'.$this->core->translate->_('title').'</div>
                      </th>
                       <th class="topauthor'.(('author' == $strOrderColumn) ? ' sort' : '').'" onclick="myList.sort(\'author\''.(('author' == $strOrderColumn && $strOrderSort == 'asc') ? ', \'desc\'' : ', \'asc\'').')">
                         <div'.(('author' == $strOrderColumn) ? ' class="'.$strOrderSort.'"' : '').'>'.$this->core->translate->_('Author').'</div>
                       </th>
                       <th class="topchanged'.(('changed' == $strOrderColumn) ? ' sort' : '').'" onclick="myList.sort(\'changed\''.(('changed' == $strOrderColumn && $strOrderSort == 'asc') ? ', \'desc\'' : ', \'asc\'').')">
                         <div'.(('changed' == $strOrderColumn) ? ' class="'.$strOrderSort.'"' : '').'>'.$this->core->translate->_('changed').'</div>
                       </th>
                       <th class="topcornerright"></th>
                     </tr>
                   </thead>';

    /**
     * return html output
     */
    $strOutput = $strThead.$strTbody;
    return $strOutput;
  }
  
  /**
   * getListTitle
   * @param string $strSearchValue
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function getFolderContentListTitle($objPaginator, $strSearchValue = '') {
    $strOutput = '';
    if($strSearchValue != '') {
      if(count($objPaginator) > 0){
        $strOutput = '
            <div class="formsubtitle searchtitle">'.sprintf($this->core->translate->_('Search_for_'), $strSearchValue).'</div>'; 
      }else{
        $strOutput = '
            <div class="formsubtitle searchtitle">'.sprintf($this->core->translate->_('No_search_results_for_'), $strSearchValue).'</div>';   
      }
      $strOutput .= '
            <div class="bttnSearchReset" onclick="myList.resetSearch();">
              <div class="button17leftOff"></div>
              <div class="button17centerOff">
                <div>'.$this->core->translate->_('Reset').'</div>
                <div class="clear"></div>
              </div>
              <div class="button17rightOff"></div>
              <div class="clear"></div>
            </div>
            <div class="clear"></div>';
    }
    return $strOutput;
  }
}

?>