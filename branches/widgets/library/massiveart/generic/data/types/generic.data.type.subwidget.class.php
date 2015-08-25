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
 * @package    library.massiveart.generic.data.types
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * GenericDataTypeSubwidget
 *
 * Version history (please keep backward compatible):
 * 1.0, 2010-01-04: Daniel Rotter
 *
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 * @package generic.data.type.interface.php
 * @subpackage GenericDataTypeSubwidget
 */

require_once(dirname(__FILE__).'/generic.data.type.abstract.class.php');

class GenericDataTypeSubwidget extends GenericDataTypeAbstract {
	
	/**
	 * @var Model_Subwidgets
	 */
	private $objModelSubwidgets;
	
	/**
	 * save
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function save() {
		$this->core->logger->debug('massiveart->generic->data->GenericDataTypeSubwidget->save()');
		
		$intSubwidgetVersion = 1;
		$strSubwidgetId = $this->Setup()->getSubwidgetId();
		
		switch($this->setup->getActionType()) {
			case $this->core->sysConfig->generic->actions->add:
				//Search idWidgetTable
				$objWidgetTable = $this->getModelSubwidgets()->searchWidgetTable($this->Setup()->getGenFormId());
				
				$arrMainData = array( 'subwidgetId'       => $strSubwidgetId,
				                      'widgetInstanceId'  => $this->setup->getParentId(),
				                      'created'           => date('Y-m-d H:i:s'),
				                      'idUsers'           => Zend_Auth::getInstance()->getIdentity()->id,
				                      'idWidgetTable'     => $objWidgetTable->id,
				                      'idParentTypes'     => $this->core->sysConfig->parent_types->widget,
				                      'version'           => $intSubwidgetVersion,
				                      'idStatus'          => $this->setup->getStatusId(),
				                      'creator'           => Zend_Auth::getInstance()->getIdentity()->id,
				                      'changed'           => date('Y-m-d H:i:s')
				                    );
				                    
				$this->setup->setElementId($this->getModelSubwidgets()->getSubwidgetTable()->insert($arrMainData));
				
				$this->insertCoreData('subwidget', $strSubwidgetId, $intSubwidgetVersion);
        //NOT TESTED!
        $this->insertFileData('subwidget', array('Id' => $strSubwidgetId, 'Version' => $intSubwidgetVersion));
//        $this->insertMultiFieldData('subwidget', array('Id' => $strSubwidgetId, 'Version' => $intSubwidgetVersion));
//        $this->insertInstanceData('subwidget', array('Id' => $strSubwidgetId, 'Version' => $intSubwidgetVersion));
        $this->insertMultiplyRegionData('subwidget', $strSubwidgetId, $intSubwidgetVersion);
				break;
			case $this->core->sysConfig->generic->actions->edit:
			  $objSelect = $this->getModelSubwidgets()->getSubwidgetTable()->select();
        $objSelect->from('subwidgets', array('subwidgetId', 'version', 'created'));
        $objSelect->where('id = ?', $this->setup->getElementId());
          
        $objRowSet = $this->getModelSubwidgets()->getSubwidgetTable()->fetchAll($objSelect);
          
        if(count($objRowSet) > 0) {
          $objSubwidgets = $objRowSet->current();
            
          $strSubwidgetId = $objSubwidgets->subwidgetId;
          $strSubwidgetVersion = $objSubwidgets->version;
            
          $strWhere = $this->getModelSubwidgets()->getSubwidgetTable()->getAdapter()->quoteInto('subwidgetId = ?', $objSubwidgets->subwidgetId);
          $strWhere .= $this->getModelSubwidgets()->getSubwidgetTable()->getAdapter()->quoteInto(' AND version = ?', $objSubwidgets->version);

          $this->getModelSubwidgets()->getSubwidgetTable()->update(array( 'idUsers'          => Zend_Auth::getInstance()->getIdentity()->id,
                                                                          'idStatus'         => $this->setup->getStatusId(),
                                                                          'changed'          => date('Y-m-d H:i:s'),
                                                                          'created'          => $objSubwidgets->created
                                                                        ),
                                                                   $strWhere);
        }
				$this->updateCoreData('subwidget', $strSubwidgetId, $intSubwidgetVersion);
				//NOT TESTED!
        $this->updateFileData('subwidget', array('Id' => $strSubwidgetId, 'Version' => $intSubwidgetVersion));
//        $this->updateMultiFieldData('subwidget', $strSubwidgetId, $intSubwidgetVersion);
//        $this->updateInstanceData('subwidget', $strSubwidgetId, $intSubwidgetVersion);
        $this->updateMultiplyRegionData('subwidget', $strSubwidgetId, $intSubwidgetVersion);
				break;
		}
		
		if(count($this->setup->SpecialFields()) > 0) {
			foreach($this->setup->SpecialFields() as $objField) {
				$objField->setGenericSetup($this->setup);
				$objField->save($this->setup->getElementId(), 'subwidget', $strSubwidgetId, $intSubwidgetVersion);
			}
		}
	}
	
	/**
	 * load
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function load() {
	  $this->core->logger->debug('massiveart->generic->data->GenericDataTypeSubwidget->load()');
		
	  $intSubwidgetVersion = 1;
	  $objSubwidgetsData = $this->getModelSubwidgets()->load($this->Setup()->getElementId());
	  
	  if(count($objSubwidgetsData) > 0){
	  	$objSubwidgetData = $objSubwidgetsData->current();
			
	  	$this->setup->setMetaInformation($objSubwidgetData);
//      $this->setup->setElementTypeId($objSubwidgetData->idPageTypes);
//      $this->setup->setIsStartElement($objSubwidgetData->isStartPage);
//      $this->setup->setParentTypeId($objSubwidgetData->idParentTypes);

			if(count($this->setup->SpecialFields()) > 0){
		    foreach($this->setup->SpecialFields() as $objField){
		      $objField->setGenericSetup($this->setup);
		      $objField->load($this->setup->getElementId(), 'subwidget', $this->Setup()->getSubwidgetId(), $intSubwidgetVersion);
		    }
		  }
		    
		  if(count($this->setup->CoreFields()) > 0){
			  /**
			   * for each core field, try to select the secondary table
			   */
			  foreach($this->setup->CoreFields() as $strField => $objField){
			
	      $objGenTable = $this->getModelGenericData()->getGenericTable('subwidget'.((substr($strField, strlen($strField) - 1) == 'y') ? ucfirst(rtrim($strField, 'y')).'ies' : ucfirst($strField).'s'));
		    $objSelect = $objGenTable->select();
		
		    $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array($strField));
		    $objSelect->where('subwidgetId = ?', $this->Setup()->getSubwidgetId());
		    $objSelect->where('version = ?', $intSubwidgetVersion);
		    $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());
			
		    $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();
			
			  if(count($arrGenFormsData) > 0){
			    $objField->blnHasLoadedData = true;
			    if(count($arrGenFormsData) > 1){
			      $arrFieldData = array();
			      foreach ($arrGenFormsData as $arrRowGenFormData) {
			        foreach ($arrRowGenFormData as $column => $value) {
			          array_push($arrFieldData, $value);
			        }
			      }
			      if($column == $strField){
			        $objField->setValue($arrFieldData);
			      }else{
			        $objField->$column = $arrFieldData;
			      }
			    }else{
			      foreach ($arrGenFormsData as $arrRowGenFormData) {
			        foreach ($arrRowGenFormData as $column => $value) {
			          if($column == $strField){
			            $objField->setValue($value);
			          }else{
			            $objField->$column = $value;
			            }
			          }
			        }
			      }
			    }
		    }
		  }
		  
      /**
       * generic form file fields
       */
      if(count($this->setup->FileFields()) > 0){

        $objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-InstanceFiles');
        $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

        $objSelect = $objGenTable->select();
        $objSelect->setIntegrityCheck(false);

        $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idFiles'));
        $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
        $objSelect->where('subwidgetId = ?', $this->Setup()->getSubwidgetId());
        $objSelect->where('version = ?', $intSubwidgetVersion);
        $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

        $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();

        if(count($arrGenFormsData) > 0){
          $this->blnHasLoadedFileData = true;
          foreach($arrGenFormsData as $arrGenRowFormsData){
            $strFileIds = $this->setup->getFileField($arrGenRowFormsData['name'])->getValue().'['.$arrGenRowFormsData['idFiles'].']';
            $this->setup->getFileField($arrGenRowFormsData['name'])->setValue($strFileIds);
          }
        }
      }
	  }
	  
	/**
     * if the generic form, has multiply regions
     */
    if(count($this->setup->MultiplyRegionIds()) > 0){

      /**
       * for each multiply region, load region data
       */
      foreach($this->setup->MultiplyRegionIds() as $intRegionId){
        $objRegion = $this->setup->getRegion($intRegionId);

        $arrRegionInstanceIds = array();
        $intRegionInstanceCounter = 0;

        $objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Region'.$objRegion->getRegionId().'-Instances');
        $objSelect = $objGenTable->select();

        $arrSelectFields = array('id');
        /**
         * for each instance field, add to select array data array
         */
        foreach($objRegion->InstanceFieldNames() as $strField){
          $arrSelectFields[] = $strField;
        }

        $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), $arrSelectFields);
        $objSelect->where('subwidgetId = ?', $this->Setup()->getSubwidgetId());
        $objSelect->where('version = ?', $intSubwidgetVersion);
        $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());
        $objSelect->order(array('sortPosition'));

        $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();

        if(count($arrGenFormsData) > 0){
          //$this->blnHasLoadedInstanceData = true;

          foreach ($arrGenFormsData as $arrRowGenFormData) {
            $intRegionInstanceCounter++;
            $intRegionInstanceId = $arrRowGenFormData['id'];
            $arrRegionInstanceIds[$intRegionInstanceCounter] = $intRegionInstanceId;

            $objRegion->addRegionInstanceId($intRegionInstanceCounter);
            foreach ($arrRowGenFormData as $column => $value) {
              if($column != 'id'){
                if(is_array(json_decode($value))){
                  $objRegion->getField($column)->setInstanceValue($intRegionInstanceCounter, json_decode($value));
                }else{
                  $objRegion->getField($column)->setInstanceValue($intRegionInstanceCounter, $value);
                }
              }
            }
          }
        }

        /**
         * generic multipy region file fields
         */
        if(count($objRegion->FileFieldNames()) > 0){

          $objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Region'.$objRegion->getRegionId().'-InstanceFiles');
          $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

          $objSelect = $objGenTable->select();
          $objSelect->setIntegrityCheck(false);

          $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idFiles', 'idRegionInstances'));
          $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
          $objSelect->where('subwidgetId = ?', $this->Setup()->getSubwidgetId());
          $objSelect->where('version = ?', $intSubwidgetVersion);
          $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

          $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();

          if(count($arrGenFormsData) > 0){
            //$this->blnHasLoadedFileData = true;
            foreach($arrGenFormsData as $arrGenRowFormsData){
              $intRegionInstanceId = $arrGenRowFormsData['idRegionInstances'];
              $intRegionPos = array_search($intRegionInstanceId, $arrRegionInstanceIds);
              if($intRegionPos !== false){
                $strFileIds = $objRegion->getField($arrGenRowFormsData['name'])->getInstanceValue($intRegionPos).'['.$arrGenRowFormsData['idFiles'].']';
                $objRegion->getField($arrGenRowFormsData['name'])->setInstanceValue($intRegionPos, $strFileIds);
              }
            }
          }
        }

        /**
         * generic multipy region multi fields
         */
        if(count($objRegion->MultiFieldNames()) > 0){

          $objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Region'.$objRegion->getRegionId().'-InstanceMultiFields');
          $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

          $objSelect = $objGenTable->select();
          $objSelect->setIntegrityCheck(false);

          $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idRelation', 'value', 'idRegionInstances'));
          $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
          $objSelect->where('subwidgetId = ?', $this->Setup()->getSubwidgetId());
          $objSelect->where('version = ?', $objPageData->version);
          $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

          $arrGenFormsData = $objGenTable->fetchAll($objSelect);

          if(count($arrGenFormsData) > 0){
            //$this->blnHasLoadedMultiFieldData = true;
            foreach($arrGenFormsData as $arrGenRowFormsData){
              $intRegionInstanceId = $arrGenRowFormsData->idRegionInstances;
              $intRegionPos = array_search($intRegionInstanceId, $arrRegionInstanceIds);

              $arrTmpRelationIds = $objRegion->getField($arrGenRowFormsData->name)->getInstanceValue($intRegionPos);
              if(is_array($arrTmpRelationIds)){
                array_push($arrTmpRelationIds, $arrGenRowFormsData->idRelation);
              }else{
                $arrTmpRelationIds = array($arrGenRowFormsData->idRelation);
              }
              $objRegion->getField($arrGenRowFormsData->name)->setInstanceValue($intRegionPos, $arrTmpRelationIds);
            }
          }
        }
      }
    }
	}
	
  /**
   * getModelSubwidgets
   * @return Model_Subwidgets
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  protected function getModelSubwidgets(){
    if (null === $this->objModelSubwidgets) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Subwidgets.php';
      $this->objModelSubwidgets = new Model_Subwidgets();
    }

    return $this->objModelSubwidgets;
  }
}
?>