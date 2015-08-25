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
 * @package    application.zoolu.modules.cms.models
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Model_Subwidgets
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2010-01-22: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 *
 */
class Model_Subwidgets {
	/**
   * @var Core
   */
  private $core;
  
  /**
   * @var number
   */
  private $intLanguageId;
  
  /**
   * @var Model_Table_Subwidgets
   */
  private $objSubwidgetTable;
  
  /**
   * @var Model_Table_Urls
   */
  private $objUrlTable;
  
  /**
   * @var Model_Table_WidgetTable
   */
  private $objWidgetTableTable;
  
  /**
   * @var array
   */
  protected $arrGenericTables = array();
  
  /**
   * Constructor
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function __construct() {
    $this->core = Zend_Registry::get('Core');
  }
  
  /**
   * load
   * @param number $intElementId 
   * @return
   */
  public function load($intElementId){
  	$this->core->logger->debug('cms->models->Model_Subwidgets->load('.$intElementId.')');
  	
  	$objSelect = $this->getSubwidgetTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	
  	$objSelect->from('subwidgets', array('id', 'subwidgetId', 'widgetInstanceId', 'created', 'idUsers', 'widgetTable.name AS table'));
  	$objSelect->join('widgetTable', 'widgetTable.id = subwidgets.idWidgetTable');
  	$objSelect->where('subwidgets.id = ?', $intElementId);
  	
  	$objSubwidgets = $this->getSubwidgetTable()->fetchAll($objSelect);
  	$objSubwidget = $objSubwidgets->current();
  	
  	$objSelectWidget = $this->getGenericTable($objSubwidget->table)->select();
  	$objSelectWidget->setIntegrityCheck(false);
  	
  	$objSelectWidget->from($objSubwidget->table, array('id', 'title', 'text', 'subwidgetId AS relationId', '(SELECT 0) AS showInNavigation',
  	                                                   '(SELECT CONCAT(users.fname, \' \', users.sname) AS changeUser FROM users WHERE users.id = subwidgets.idUsers) AS changeUser'));
  	$objSelectWidget->join('subwidgets', 'subwidgets.subwidgetId = '.$objSubwidget->table.'.subwidgetId');
  	$objSelectWidget->where('subwidgets.subwidgetId = ?', $objSubwidget->subwidgetId);
  	
  	return $this->getGenericTable($objSubwidget->table)->fetchAll($objSelectWidget);
  }
  
  /**
   * loadParentUrl
   * @param number $intSubwidgetId
   * @param boolean $blnIsStartElement
   * @return Zend_Db_Table_Rowset_Abstract
   */
  public function loadParentUrl($intSubwidgetId, $blnIsStartElement){
  	$this->core->logger->debug('cms->models->Model_Subwidgets->loadParentUrl('.$intSubwidgetId.','.$blnIsStartElement.')');
  	
  	$objSelect = $this->getSubwidgetTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	
  	$objSelect->from('subwidgets');
  	$objSelect->join('urls', 'subwidgets.widgetInstanceId = urls.relationId');
  	$objSelect->where('subwidgets.id = ?', $intSubwidgetId);
  	
  	return $this->getSubwidgetTable()->fetchAll($objSelect);
  }
  
  /**
   * delete
   * @param integer $intElementId
   * @version 1.0
   */
  public function delete($intElementId)
  {
  	$this->core->logger->debug('cms->models->Model_Subwidgets->delete('.$intElementId.')');
  	
  	$strWhere = $this->getSubwidgetTable()->getAdapter()->quoteInto('subwidgets.Id = ?', $intElementId);
  	return $this->getSubwidgetTable()->delete($strWhere);
  }
  
  /**
   * searchWidgetTable
   * @param number $intGenericFormId
   * @return Zend_Db_Table_Rowset_Abstract
   */
  public function searchWidgetTable($intGenericFormId){
  	$this->core->logger->debug('cms->models->Model_Subwidgets->searchWidgetTable('.$intGenericFormId.')');
  	
  	$objSelect = $this->getWidgetTableTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	
  	$objSelect->from('widgetTable', array('id', 'name'));
  	$objSelect->where('idGenericForms = ?', $intGenericFormId);
  	
  	return $this->getWidgetTableTable()->fetchRow($objSelect);
  }
  
  /**
   * setLanguageId
   * @param integer $intLanguageId
   */
  public function setLanguageId($intLanguageId){
    $this->intLanguageId = $intLanguageId;
  }

  /**
   * getLanguageId
   * @param integer $intLanguageId
   */
  public function getLanguageId(){
    return $this->intLanguageId;
  }
  
  /**
   * getSubwidgetTable
   * @return Zend_Db_Table_Abstract
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function getSubwidgetTable(){

    if($this->objSubwidgetTable === null) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/tables/Subwidgets.php';
      $this->objSubwidgetTable = new Model_Table_Subwidgets();
    }

    return $this->objSubwidgetTable;
  }
  
  /**
   * getWidgetTableTable
   * @return Zend_Db_Table_Abstract
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function getWidgetTableTable(){
  	if($this->objWidgetTableTable === null) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/tables/WidgetTable.php';
      $this->objWidgetTableTable = new Model_Table_WidgetTable();
    }

    return $this->objWidgetTableTable;
  }
  
    /**
   * getGenericTable
   * @return Model_Table_Generics 
   * @author Thomas Schedler <cha@massiveart.com>
   * @version 1.0
   */
  public function getGenericTable($strTableName){
    try{ 
          
      if(!array_key_exists($strTableName, $this->arrGenericTables)) {
        require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Generics.php';
        $this->arrGenericTables[$strTableName] = new Model_Table_Generics($strTableName);
      }
      
      return $this->arrGenericTables[$strTableName];
      
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  } 
  
  /**
   * getUrlTable
   * @return Zend_Db_Table_Abstract
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getUrlTable(){

    if($this->objUrlTable === null) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Urls.php';
      $this->objUrlTable = new Model_Table_Urls();
    }

    return $this->objUrlTable;
  }
}
?>