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
 * Model_RootLevels
 *
 * Version history (please keep backward compatible):
 * 1.0, 2010-12-15: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Model_RootLevels {

	private $intLanguageId;

  /**
   * @var Model_Table_RootLevels
   */
  protected $objRootLevelTable;
  
  /**
   * @var Model_Table_RootLevelMaintenances
   */
  protected $objRootLevelMaintenanceTable;

  /**
   * @var Model_Table_RootLevelUrls
   */
  protected $objRootLevelUrlTable;

  /**
   * @var Model_Table_RootLevelPermissions
   */
  protected $objRootLevelPermissionTable;
    
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
   * getMaintenanceByDomain
   * @param string $strDomain
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getMaintenanceByDomain($strDomain){
    $this->core->logger->debug('core->models->RootLevels->getMaintenanceByDomain('.$strDomain.')');

    $objSelect = $this->getRootLevelMaintenanceTable()->select();
    $objSelect->setIntegrityCheck(false);
    
    if(strpos($strDomain, 'www.') !== false){
      $strDomain = str_replace('www.', '', $strDomain);
    }
    
    $objSelect->from('rootLevelMaintenances', array('id', 'idLanguages', 'isMaintenance', 'maintenance_startdate', 'maintenance_enddate', 'maintenance_url'));
    $objSelect->join('rootLevelUrls', 'rootLevelUrls.url = \''.$strDomain.'\'', array('idRootLevels'));
    $objSelect->where('rootLevelMaintenances.idRootLevels = rootLevelUrls.idRootLevels');
    
    return $this->getRootLevelMaintenanceTable()->fetchAll($objSelect);
  }
  
  /**
   * loadMaintenance
   * @param integer $intRootLevelId
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function loadMaintenance($intRootLevelId, $blnCheckDate = false){
    $this->core->logger->debug('core->models->RootLevels->loadMaintenance('.$intRootLevelId.','.$blnCheckDate.')');
    
    $objSelect = $this->getRootLevelMaintenanceTable()->select();
    $objSelect->from('rootLevelMaintenances', array('id', 'idLanguages', 'isMaintenance', 'maintenance_startdate', 'maintenance_enddate', 'maintenance_url'));
    $objSelect->where('idRootLevels = ?', $intRootLevelId);
    
    $mxReturn = '';
    
    /**
     * check if maintennce is active or not if $blnCheckDate = true 
     */
    if($blnCheckDate){
      $objMaintenanceData = $this->getRootLevelMaintenanceTable()->fetchAll($objSelect);
      
      $blnIsMaintenanceActive = false;      
      if(count($objMaintenanceData) > 0){
        $objMaintenanceData = $objMaintenanceData->current();

        if($objMaintenanceData->isMaintenance == true){          
          if($objMaintenanceData->maintenance_startdate != '' && $objMaintenanceData->maintenance_enddate != ''){          
            if(time() >= strtotime($objMaintenanceData->maintenance_startdate) && time() <= strtotime($objMaintenanceData->maintenance_enddate)){
              $blnIsMaintenanceActive = true;  
            }else{
              $blnIsMaintenanceActive = false;
            } 
          }else if($objMaintenanceData->maintenance_startdate != '' && $objMaintenanceData->maintenance_enddate == ''){            
            if(time() >= strtotime($objMaintenanceData->maintenance_startdate)){
              $blnIsMaintenanceActive = true;  
            }else{
              $blnIsMaintenanceActive = false;
            }  
          }else if($objMaintenanceData->maintenance_startdate == '' && $objMaintenanceData->maintenance_enddate != ''){            
            if(time() <= strtotime($objMaintenanceData->maintenance_enddate)){
              $blnIsMaintenanceActive = true;  
            }else{
              $blnIsMaintenanceActive = false;
            }  
          }else{
            $blnIsMaintenanceActive = true;
          }
        }        
      }      
      $mxReturn = $blnIsMaintenanceActive;
    }else{
      $mxReturn = $this->getRootLevelMaintenanceTable()->fetchAll($objSelect);  
    }    
    return $mxReturn;
  }
  
  /**
   * loadActiveMaintenances
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function loadActiveMaintenances(){
    $this->core->logger->debug('core->models->RootLevels->loadActiveMaintenances()');
    
    $objSelect = $this->getRootLevelMaintenanceTable()->select();
    $objSelect->from('rootLevelMaintenances', array('id', 'idRootLevels', 'maintenance_startdate', 'maintenance_enddate', 'maintenance_url'));
    $objSelect->where('isMaintenance = ?', 1);

    return $this->getRootLevelMaintenanceTable()->fetchAll($objSelect);
  }
  
  /**
   * saveMaintenance
   * @param integer $intRootLevelId
   * @param array $arrFormData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function saveMaintenance($intRootLevelId, $arrFormData){
    $this->core->logger->debug('core->models->RootLevels->saveMaintenance('.$intRootLevelId.', '.var_export($arrFormData, true).')');	  
    
    $arrData = array(//'idLanguages'					 => 'NULL',
                     'isMaintenance'         => $arrFormData['isMaintenance'],
                     'maintenance_startdate' => $arrFormData['maintenance_startdate'],
                     'maintenance_enddate'   => $arrFormData['maintenance_enddate'],
                     'maintenance_url'       => $arrFormData['maintenance_url'],
                     'changed'               => date('Y-m-d H:i:s'));
    
    $strWhere = $this->getRootLevelMaintenanceTable()->getAdapter()->quoteInto('idRootLevels = ?', $intRootLevelId);
    //$strWhere .= $this->getRootLevelMaintenanceTable()->getAdapter()->quoteInto(' AND idLanguages = ?', $this->intLanguageId);
  
    $intNumOfEffectedRows = $this->getRootLevelMaintenanceTable()->update($arrData, $strWhere);
    
    if($intNumOfEffectedRows == 0){
      $arrData = array('idRootLevels'          => $intRootLevelId,
                       //'idLanguages'				 => 'NULL',
                       'isMaintenance'         => $arrFormData['isMaintenance'],
                       'maintenance_startdate' => $arrFormData['maintenance_startdate'],
                       'maintenance_enddate'   => $arrFormData['maintenance_enddate'],
                       'maintenance_url'       => $arrFormData['maintenance_url'],
                       'changed'               => date('Y-m-d H:i:s'));
      
      $intNumOfEffectedRows = $this->getRootLevelMaintenanceTable()->insert($arrData);  
    }
    
    return $intNumOfEffectedRows;
  }

  /**
   * getRootLevelTable
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getRootLevelTable(){
    if($this->objRootLevelTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/RootLevels.php';
      $this->objRootLevelTable = new Model_Table_RootLevels();
    }
    return $this->objRootLevelTable;
  }
  
  /**
   * getRootLevelMaintenanceTable
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getRootLevelMaintenanceTable(){
    if($this->objRootLevelMaintenanceTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/RootLevelMaintenances.php';
      $this->objRootLevelMaintenanceTable = new Model_Table_RootLevelMaintenances();
    }
    return $this->objRootLevelMaintenanceTable;
  }

  /**
   * getRootLevelUrlTable
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getRootLevelUrlTable(){
    if($this->objRootLevelUrlTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/RootLevelUrls.php';
      $this->objRootLevelUrlTable = new Model_Table_RootLevelUrls();
    }
    return $this->objRootLevelUrlTable;
  }
  
  /**
   * getRootLevelPermissionTable
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getRootLevelPermissionTable(){
    if($this->objRootLevelPermissionTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/RootLevelPermissions.php';
      $this->objRootLevelPermissionTable = new Model_Table_RootLevelPermissions();
    }
    return $this->objRootLevelPermissionTable;
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