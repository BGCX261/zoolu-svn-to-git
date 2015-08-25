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
 * Model_Members
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2011-01-19: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Model_Members {
  
  private $intLanguageId;
  
  /**
   * @var Model_Table_Members 
   */
  protected $objMembersTable;
  
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
   * loadNavigation
   * @param integer $intRootLevelId 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function loadNavigation($intRootLevelId){
    $this->core->logger->debug('core->models->Members->loadNavigation('.$intRootLevelId.')');    

    $objSelect = $this->getMembersTable()->select();
    $objSelect->setIntegrityCheck(false);
    
    $objSelect->from('members', array('id', 'title' => new Zend_Db_Expr("CONCAT(members.fname, ' ', members.sname)"), 'type' => new Zend_Db_Expr("'member'")))
              ->join('genericForms', 'genericForms.id = members.idGenericForms', array('genericFormId', 'version'))
              ->order('title');
    
    return $this->objMembersTable->fetchAll($objSelect); 
  }   
  
  /**
   * loadMember
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @param integer $intElementId
   * @version 1.0
   */
  public function loadMember($intElementId){
    $this->core->logger->debug('core->models->Locations->loadMember('.$intElementId.')');
    
    $objSelect = $this->getMembersTable()->select();   
    $objSelect->setIntegrityCheck(false);
    
    $objSelect->from('members');
    $objSelect->where('members.id = ?', $intElementId);
        
    return $this->getMembersTable()->fetchAll($objSelect);    
  }
  
  /**
   * addMember   
   * @param array $arrData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0 
   */
  public function addMember($arrData){
   try{ 
      return $this->getMembersTable()->insert($arrData);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * editMember
   * @param integer $intMemberId   
   * @param array $arrData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0 
   */
  public function editMember($intMemberId, $arrData){
    try{
      $this->getMembersTable();
      
      $strWhere = $this->objMembersTable->getAdapter()->quoteInto('id = ?', $intMemberId);
      
      return $this->objMembersTable->update($arrData, $strWhere);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
    
  /**
   * deleteMember 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @param integer $intMemberId
   * @version 1.0
   */
  public function deleteMember($intMemberId){
    $this->core->logger->debug('core->models->Members->deleteMember('.$intMemberId.')');
    
    $this->getMembersTable();
    
    /**
     * delete member
     */
    $strWhere = $this->objMembersTable->getAdapter()->quoteInto('id = ?', $intMemberId);  
    
    return $this->objMembersTable->delete($strWhere);
  }
  
  /**
   * deleteMembers
   * @param array $arrMemberIds
   * @return integer the number of rows deleted
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function deleteMembers($arrMemberIds){
    try{  
      $strWhere = '';
      $intCounter = 0;
      if(count($arrMemberIds) > 0){
        foreach($arrMemberIds as $intMemberId){
          if($intMemberId != ''){
            if($intCounter == 0){
              $strWhere .= $this->getMembersTable()->getAdapter()->quoteInto('id = ?', $intMemberId);
            }else{
              $strWhere .= $this->getMembersTable()->getAdapter()->quoteInto(' OR id = ?', $intMemberId);
            }
            $intCounter++;
          }
        }
      }   
      return $this->objMembersTable->delete($strWhere);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * getMembersTable
   * @return Model_Table_Members $objMemberTable
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0 
   */
  public function getMembersTable(){    
    if($this->objMembersTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Members.php';
      $this->objMembersTable = new Model_Table_Members();
    }
    
    return $this->objMembersTable;
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