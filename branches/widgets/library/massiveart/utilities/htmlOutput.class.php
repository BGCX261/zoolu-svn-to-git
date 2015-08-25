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
 * @package    library.massiveart.utilities
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * HtmlOutput Class - static function container
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-17: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 * @package massiveart.utilities
 * @subpackage HtmlOutput
 */

class HtmlOutput {
  
  /**
   * getOptionsOfSQL
	 * returns the result of a SQL-Statement in the valid output form
	 * <option value=[VALUE] >[DISPLAY]</option>
	 *
	 * Version history (please keep backward compatible):
   * 1.0, 2008-11-17: Cornelius Hansjakob
   *
   * @param Core $core
	 * @param string $strSQL SQL statment
	 * @param string $strSelectedValue
	 * @return string $strHtmlOutput
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
	 */
	public static function getOptionsOfSQL(Core &$core, $strSQL, $strSelectedValue = ''){
    $core->logger->debug('massiveart->utilities->HtmlOutput->getOptionsOfSQL: '.$strSQL);
    $strHtmlOutput = '';

    try {
    	
    	foreach($core->dbh->query($strSQL)->fetchAll() as $arrSQLRow) {
      	
      	if($arrSQLRow['VALUE'] == $strSelectedValue){
          $strSelected = ' selected';
        }else{
          $strSelected = '';
        }
        $strHtmlOutput .= '<option value="'.$arrSQLRow['VALUE'].'"'.$strSelected.'>'.htmlentities($arrSQLRow['DISPLAY'], ENT_COMPAT, $core->sysConfig->encoding->default).'</option>'.chr(13);
      }

    } catch (Exception $exc) {
      $core->logger->err($exc);
    }

    return $strHtmlOutput;
	}
	
/**
   * getOptionsOfSQLByGuiTexts
   * returns the result of a SQL-Statement in the valid output form
   * <option value=[VALUE] >$core->guiTexts->getText([DISPLAY])</option>
   *
   * Version history (please keep backward compatible):
   * 1.0, 2008-11-17: Cornelius Hansjakob
   *
   * @param Core $core
   * @param string $strSQL SQL statment
   * @param string $strSelectedValue
   * @return string $strHtmlOutput
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public static function getOptionsOfSQLByGuiTexts(Core &$core, $strSQL, $strSelectedValue = ''){
    $core->logger->debug('massiveart->utilities->HtmlOutput->getOptionsOfSQLByGuiTexts: '.$strSQL);
    $strHtmlOutput = '';

    try{
      foreach($core->dbh->query($strSQL)->fetchAll() as $arrSQLRow) {
        if($arrSQLRow['VALUE'] == $strSelectedValue){
          $strSelected = ' selected';
        }else{
          $strSelected = '';
        }
        
        $strDisplayTitle = $core->guiTexts->getText(trim($arrSQLRow['DISPLAY']));
        
        if($strDisplayTitle != ''){
          $strHtmlOutput .= '<option value="'.$arrSQLRow['VALUE'].'"'.$strSelected.'>'.$strDisplayTitle.'</option>'.chr(13); 
        }          
      }

    }catch (Exception $exc) {
      $core->logger->err($exc);
    }

    return $strHtmlOutput;
  }
	
}

?>