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
 * @package    application.zoolu.modules.core.models
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Model_Contacts
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-04-06: Thomas Schedler
 * 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class Model_Contacts {
  
  private $intLanguageId;
  
  /**
   * @var Model_Table_Contacts 
   */
  protected $objContactsTable;
  
  /**
   * @var Model_Table_Units 
   */
  protected $objUnitTable;
  
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
   * loadNavigation
   * @param integer $intItemId
   * @param boolean $blnOnlyUnits
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function loadNavigation($intItemId, $blnOnlyUnits = false){
    $this->core->logger->debug('core->models->Contacts->loadNavigation('.$intItemId.')');	
  	
    if($blnOnlyUnits == false){
      $sqlStmt = $this->core->dbh->query("SELECT id, title, genericFormId, version, type
                                        FROM (SELECT units.id, unitTitles.title, genericForms.genericFormId, genericForms.version, 'unit' AS type
                                                FROM units
                                              LEFT JOIN unitTitles ON 
                                                unitTitles.idUnits = units.id AND 
                                                unitTitles.idLanguages = ?  
                                              INNER JOIN genericForms ON genericForms.id = units.idGenericForms
                                              WHERE units.idParentUnit = ?
                                              UNION
                                              SELECT contacts.id, CONCAT(contacts.fname, ' ', contacts.sname) AS title, genericForms.genericFormId, genericForms.version, 'contact'  AS type
                                                FROM contacts  
                                              INNER JOIN genericForms ON genericForms.id = contacts.idGenericForms
                                              WHERE contacts.idUnits = ?) 
                                        AS tbl", array($this->intLanguageId, $intItemId, $intItemId));  
    }else{
      $sqlStmt = $this->core->dbh->query("SELECT units.id, unitTitles.title, genericForms.genericFormId, genericForms.version, 'unit' AS type
                                                FROM units
                                              LEFT JOIN unitTitles ON 
                                                unitTitles.idUnits = units.id AND 
                                                unitTitles.idLanguages = ?  
                                              INNER JOIN genericForms ON genericForms.id = units.idGenericForms
                                              WHERE units.idParentUnit = ?", array($this->intLanguageId, $intItemId)); 
    }
    
    return $sqlStmt->fetchAll(Zend_Db::FETCH_OBJ);
  }
    
  /**
   * loadContactsByUnitId
   * @param integer $intUnitId
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function loadContactsByUnitId($intUnitId){
    $this->core->logger->debug('core->models->Contacts->loadContactsByUnitId('.$intUnitId.')'); 

    //FIXME Subselect of `contact-DEFAULT_CONTACT-1-InstanceFiles` for contactPics should be changed!
    $sqlStmt = $this->core->dbh->query("SELECT contacts.id, CONCAT(contacts.fname, ' ', contacts.sname) AS title, genericForms.genericFormId, genericForms.version, 'contact'  AS type, (SELECT files.filename FROM files INNER JOIN `contact-DEFAULT_CONTACT-1-InstanceFiles` AS contactPics ON files.id = contactPics.idFiles WHERE contactPics.idContacts = contacts.id LIMIT 1) AS filename
                                                FROM contacts  
                                              INNER JOIN genericForms ON genericForms.id = contacts.idGenericForms
                                              WHERE contacts.idUnits = ?", array($intUnitId)); 
    
    return $sqlStmt->fetchAll(Zend_Db::FETCH_OBJ);
  }
  
  /**
   * loadContact 
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Thomas Schedler <tsh@massiveart.com>
   * @param integer $intElementId
   * @version 1.0
   */
  public function loadContact($intElementId){
    $this->core->logger->debug('core->models->Contacts->loadContact('.$intElementId.')');
    
    $objSelect = $this->getContactsTable()->select();   
    $objSelect->setIntegrityCheck(false);
    
    /**
     * SELECT contacts.* 
     * FROM contacts
     * WHERE contacts.id = ?   
     */
    $objSelect->from('contacts');
    $objSelect->where('contacts.id = ?', $intElementId);
        
    return $this->getContactsTable()->fetchAll($objSelect);    
  }
  
  /**
   * loadContactsById 
   * @param string|array $mixedContactIds
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function loadContactsById($mixedContactIds){
    $this->core->logger->debug('core->models->Contacts->loadContactsById('.$mixedContactIds.')');
    try{
      $this->getContactsTable();
      
      $arrContactIds = array();
      if(is_array($mixedContactIds)){
        $arrContactIds = $mixedContactIds;
      }else if(isset($mixedContactIds) && $mixedContactIds != ''){
	      $strTmpContactIds = trim($mixedContactIds, '[]');
	      $arrContactIds = split('\]\[', $strTmpContactIds);
      }
      
      $objSelect = $this->objContactsTable->select();   
      $objSelect->setIntegrityCheck(false);
      
      if(count($arrContactIds) > 0){
        $strIds = '';
        foreach($arrContactIds as $intContactId){
          $strIds .= $intContactId.',';
        }
        
        //FIXME Subselect of `contact-DEFAULT_CONTACT-1-InstanceFiles` for contactPics should be changed!
        $objSelect->from('contacts', array('id', 'title AS acTitle', 'CONCAT(fname, \' \', sname) AS title', 'position', 'phone', 'mobile', 'fax', 'email', 'website', '(SELECT files.filename FROM files INNER JOIN `contact-DEFAULT_CONTACT-1-InstanceFiles` AS contactPics ON files.id = contactPics.idFiles WHERE contactPics.idContacts = contacts.id LIMIT 1) AS filename'));
        $objSelect->join('genericForms', 'genericForms.id = contacts.idGenericForms', array('genericFormId', 'version'));   
        $objSelect->where('contacts.id IN ('.trim($strIds, ',').')');      
        
        return $this->objContactsTable->fetchAll($objSelect);
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }  
  }
  
  /**
   * loadUnit
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Thomas Schedler <tsh@massiveart.com>
   * @param integer $intElementId
   * @version 1.0
   */
  public function loadUnit($intElementId){
    $this->core->logger->debug('core->models->Folders->loadUnit('.$intElementId.')');
    
    $objSelect = $this->getUnitTable()->select();   
    $objSelect->setIntegrityCheck(false);
    
    /**
     * SELECT units.*, unitTitles.title 
     * FROM units 
     * INNER JOIN unitTitles ON unitTitles.idUnits = units.id AND 
     *   unitTitles.idLanguages = ?
     * WHERE units.id = ?   
     */
    $objSelect->from('units');
    $objSelect->join('unitTitles', 'unitTitles.idUnits = units.id AND unitTitles.idLanguages = '.$this->intLanguageId, array('title'));
    $objSelect->where('units.id = ?', $intElementId);
        
    return $this->getUnitTable()->fetchAll($objSelect);     
  }    
  
  /**
   * addContact   
   * @param array $arrData
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0 
   */
  public function addContact($arrData){
   try{ 
      return $this->getContactsTable()->insert($arrData);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * editContact
   * @param integer $intContactId   
   * @param array $arrData
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0 
   */
  public function editContact($intContactId, $arrData){
    try{
      $this->getContactsTable();
      $strWhere = $this->objContactsTable->getAdapter()->quoteInto('id = ?', $intContactId);
      return $this->objContactsTable->update($arrData, $strWhere);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
    
  /**
   * deleteContact 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @param integer $intElementId
   * @version 1.0
   */
  public function deleteContact($intElementId){
    $this->core->logger->debug('core->models->Contacts->deleteContact('.$intElementId.')');
    
    $this->getContactsTable();
    
    /**
     * delete contacts
     */
    $strWhere = $this->objContactsTable->getAdapter()->quoteInto('id = ?', $intElementId);  
    
    return $this->objContactsTable->delete($strWhere);
  }
  
  /**
   * addUnitNode   
   * @param integer $intParentId
   * @param array $arrData
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0 
   */
  public function addUnitNode($intParentId, $arrData = array()){
   try{ 
      $intNodeId = null;
            
      $this->getUnitTable();
      
      $objNestedSet = new NestedSet($this->objUnitTable);
      $objNestedSet->setDBFParent('idParentUnit');
      $objNestedSet->setDBFRoot('idRootUnit');
      
      /**
       * if $intParentId == 0, this is a root unit node
       */
      if($intParentId == 0){
        $intNodeId = $objNestedSet->newRootNode($arrData);
      }else{
        $intNodeId = $objNestedSet->newLastChild($intParentId, $arrData);
      }
      
      return $intNodeId;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * deleteUnitNode
   * @author Thomas Schedler <tsh@massiveart.com>
   * @param integer $intElementId
   * @version 1.0
   */
  public function deleteUnitNode($intUnitId){
    $this->core->logger->debug('core->models->Contacts->deleteUnitNode('.$intUnitId.')');
    
    $this->getUnitTable();
    
    $objNestedSet = new NestedSet($this->objUnitTable);
    $objNestedSet->setDBFParent('idParentUnit');
    $objNestedSet->setDBFRoot('idRootUnit');
      
    $objNestedSet->deleteNode($intUnitId);
    
    //FIXME:: delete contacts?
  }
  
  /**
   * getContactsTable
   * @return Model_Table_Contacts $objContactsTable
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0 
   */
  public function getContactsTable(){
    
    if($this->objContactsTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Contacts.php';
      $this->objContactsTable = new Model_Table_Contacts();
    }
    
    return $this->objContactsTable;
  }
  
  /**
   * getUnitTable 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getUnitTable(){
    
    if($this->objUnitTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Units.php';
      $this->objUnitTable = new Model_Table_Units();
    }
    
    return $this->objUnitTable;
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
}

?>