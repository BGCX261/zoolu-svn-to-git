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
 * Model_Companies
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2011-01-20: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Model_Companies {
  
  private $intLanguageId;
  
  /**
   * @var Model_Table_Companies 
   */
  protected $objCompaniesTable;
  
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
    $this->core->logger->debug('core->models->Companies->loadNavigation('.$intRootLevelId.')');
    
    $objSelect = $this->getCompaniesTable()->select();
    $objSelect->setIntegrityCheck(false);
    
    $objSelect->from('companies', array('id', 'title' => new Zend_Db_Expr("companies.name"), 'type' => new Zend_Db_Expr("'company'")))
              ->join('genericForms', 'genericForms.id = companies.idGenericForms', array('genericFormId', 'version')) 
              ->order('title');
    
    return $this->objCompaniesTable->fetchAll($objSelect); 
  }  

  /**
   * loadCompany
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @param integer $intElementId
   * @version 1.0
   */
  public function loadCompany($intElementId){
    $this->core->logger->debug('core->models->Locations->loadCompany('.$intElementId.')');
    
    $objSelect = $this->getCompaniesTable()->select();   
    $objSelect->setIntegrityCheck(false);
    
    $objSelect->from('companies');
    $objSelect->where('companies.id = ?', $intElementId);
        
    return $this->getCompaniesTable()->fetchAll($objSelect);    
  }
  
  /**
   * addCompany   
   * @param array $arrData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0 
   */
  public function addCompany($arrData){
   try{ 
      return $this->getCompaniesTable()->insert($arrData);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * editCompany
   * @param integer $intCompanyId   
   * @param array $arrData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0 
   */
  public function editCompany($intCompanyId, $arrData){
    try{
      $this->getCompaniesTable();
      
      $strWhere = $this->objCompaniesTable->getAdapter()->quoteInto('id = ?', $intCompanyId);
      
      return $this->objCompaniesTable->update($arrData, $strWhere);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
    
  /**
   * deleteMember 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @param integer $intCompanyId
   * @version 1.0
   */
  public function deleteCompany($intCompanyId){
    $this->core->logger->debug('core->models->Companies->deleteCompany('.$intCompanyId.')');
    
    $this->getCompaniesTable();
    
    /**
     * delete member
     */
    $strWhere = $this->objCompaniesTable->getAdapter()->quoteInto('id = ?', $intCompanyId);  
    
    return $this->objCompaniesTable->delete($strWhere);
  }
  
  /**
   * deleteCompanies
   * @param array $arrCompanyIds
   * @return integer the number of rows deleted
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function deleteCompanies($arrCompanyIds){
    try{  
      $strWhere = '';
      $intCounter = 0;
      if(count($arrCompanyIds) > 0){
        foreach($arrCompanyIds as $intCompanyId){
          if($intCompanyId != ''){
            if($intCounter == 0){
              $strWhere .= $this->getCompaniesTable()->getAdapter()->quoteInto('id = ?', $intCompanyId);
            }else{
              $strWhere .= $this->getCompaniesTable()->getAdapter()->quoteInto(' OR id = ?', $intCompanyId);
            }
            $intCounter++;
          }
        }
      }   
      return $this->objCompaniesTable->delete($strWhere);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * getCompaniesTable
   * @return Model_Table_Companies $objMemberTable
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0 
   */
  public function getCompaniesTable(){    
    if($this->objCompaniesTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Companies.php';
      $this->objCompaniesTable = new Model_Table_Companies();
    }
    
    return $this->objCompaniesTable;
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