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
 * GenericDataTypePage
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-01-16: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 * @package generic.data.type.interface.php
 * @subpackage GenericFormTypePage
 */

require_once(dirname(__FILE__).'/generic.data.type.abstract.class.php');

class GenericDataTypePage extends GenericDataTypeAbstract {

  /**
   * @var Model_Pages
   */
  protected $objModelPages;

  /**
   * @var Model_Folders
   */
  protected $objModelFolders;

  protected $blnHasLoadedFileData = false;
  protected $blnHasLoadedMultiFieldData = false;
  protected $blnHasLoadedInstanceData = false;

  /**
   * save
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function save(){
    $this->core->logger->debug('massiveart->generic->data->GenericDataTypePage->save()');
    try{

      $this->getModelPages()->setLanguageId($this->setup->getLanguageId());

      $intUserId = Zend_Auth::getInstance()->getIdentity()->id;

      /**
       * add|edit|newVersion core and instance data
       */
      switch($this->setup->getActionType()){
        case $this->core->sysConfig->generic->actions->add :

          $strPageId = uniqid();
          $intPageVersion = 1;
          $intSortPosition = GenericSetup::DEFAULT_SORT_POSITION;

          /**
           * check if parent element is rootlevel or folder
           */
          if($this->setup->getParentId() != '' && $this->setup->getParentId() > 0){
            $this->setup->setParentTypeId($this->core->sysConfig->parent_types->folder);
            $objNaviData = $this->getModelFolders()->loadChildNavigation($this->setup->getParentId());
          }else{
            if($this->setup->getRootLevelId() != '' && $this->setup->getRootLevelId() > 0){
              $this->setup->setParentId($this->setup->getRootLevelId());
            }else{
              $this->core->logger->err('massiveart->generic->data->GenericDataTypePage->save(): intRootLevelId is empty!');
            }
            $this->setup->setParentTypeId($this->core->sysConfig->parent_types->rootlevel);
            $objNaviData = $this->getModelFolders()->loadRootNavigation($this->setup->getRootLevelId());
          }
          $intSortPosition = count($objNaviData);

          $arrMainData = array('idGenericForms'   => $this->setup->getGenFormId(),
                               'idTemplates'      => $this->setup->getTemplateId(),
                               'idPageTypes'      => $this->setup->getElementTypeId(),
                               'isStartPage'      => $this->setup->getIsStartElement(),
                               'showInNavigation' => $this->setup->getShowInNavigation(),
                               'idParent'         => $this->setup->getParentId(),
                               'idParentTypes'    => $this->setup->getParentTypeId(),
                               'pageId'           => $strPageId,
                               'version'          => $intPageVersion,
                               'sortPosition'     => $intSortPosition,
                               'sortTimestamp'    => date('Y-m-d H:i:s'),
                               'idUsers'          => $intUserId,
                               'creator'          => $this->setup->getCreatorId(),
                               'created'          => date('Y-m-d H:i:s'),
                               'idStatus'         => $this->setup->getStatusId());

          $this->setup->setElementId($this->objModelPages->getPageTable()->insert($arrMainData));
					
          $this->insertCoreData('page', $strPageId, $intPageVersion);
          $this->insertFileData('page', array('Id' => $strPageId, 'Version' => $intPageVersion));
          $this->insertMultiFieldData('page', array('Id' => $strPageId, 'Version' => $intPageVersion));
          $this->insertInstanceData('page', array('Id' => $strPageId, 'Version' => $intPageVersion));
          $this->insertMultiplyRegionData('page', $strPageId, $intPageVersion);
          break;

        case $this->core->sysConfig->generic->actions->edit :

          $objSelect = $this->objModelPages->getPageTable()->select();
          $objSelect->from('pages', array('pageId', 'version'));
          $objSelect->where('id = ?', $this->setup->getElementId());

          $objRowSet = $this->objModelPages->getPageTable()->fetchAll($objSelect);

          if(count($objRowSet) == 1){
            $objPages = $objRowSet->current();

            $strPageId = $objPages->pageId;
            $intPageVersion = $objPages->version;

            $strWhere = $this->objModelPages->getPageTable()->getAdapter()->quoteInto('pageId = ?', $objPages->pageId);
            $strWhere .= $this->objModelPages->getPageTable()->getAdapter()->quoteInto(' AND version = ?', $objPages->version);

            $this->objModelPages->getPageTable()->update(array('idGenericForms'   => $this->setup->getGenFormId(),
                                                               'idTemplates'      => $this->setup->getTemplateId(),
                                                               'idUsers'          => $intUserId,
                                                               'creator'          => $this->setup->getCreatorId(),
                                                               'idStatus'         => $this->setup->getStatusId(),
                                                               'published'        => $this->setup->getPublishDate(),
                                                               'idPageTypes'      => $this->setup->getElementTypeId(),
                                                               'showInNavigation' => $this->setup->getShowInNavigation(),
                                                               'changed'          => date('Y-m-d H:i:s')), $strWhere);

            $this->updateCoreData('page', $objPages->pageId, $objPages->version);
            $this->updateFileData('page', array('Id' => $objPages->pageId, 'Version' => $objPages->version));
            $this->updateMultiFieldData('page', $objPages->pageId, $objPages->version);
            $this->updateInstanceData('page', $objPages->pageId, $objPages->version);
            $this->updateMultiplyRegionData('page', $objPages->pageId, $objPages->version);
          }
          break;

        case $this->core->sysConfig->generic->actions->change_template :

          $objSelect = $this->objModelPages->getPageTable()->select();
          $objSelect->from('pages', array('pageId', 'version'));
          $objSelect->where('id = ?', $this->setup->getElementId());

          $objRowSet = $this->objModelPages->getPageTable()->fetchAll($objSelect);

          if(count($objRowSet) == 1){
            $objPages = $objRowSet->current();

            $strPageId = $objPages->pageId;
            $intPageVersion = $objPages->version;

            $strWhere = $this->objModelPages->getPageTable()->getAdapter()->quoteInto('pageId = ?', $objPages->pageId);
            $strWhere .= $this->objModelPages->getPageTable()->getAdapter()->quoteInto(' AND version = ?', $objPages->version);

            $this->objModelPages->getPageTable()->update(array('idGenericForms' => $this->setup->getGenFormId(),
                                                               'idTemplates'    => $this->setup->getTemplateId(),
                                                               'idUsers'        => $intUserId), $strWhere);

            $this->insertCoreData('page', $objPages->pageId, $objPages->version);

            if($this->blnHasLoadedFileData){
              $this->updateFileData('page', array('Id' => $objPages->pageId, 'Version' => $objPages->version));
            }else{
              $this->insertFileData('page', array('Id' => $objPages->pageId, 'Version' => $objPages->version));
            }

            if($this->blnHasLoadedMultiFieldData){
              $this->updateMultiFieldData('page', $objPages->pageId, $objPages->version);
            }else{
              $this->insertMultiFieldData('page', array('Id' => $objPages->pageId, 'Version' => $objPages->version));
            }

            if($this->blnHasLoadedInstanceData){
              $this->updateInstanceData('page', $objPages->pageId, $objPages->version);
            }else{
              $this->insertInstanceData('page', array('Id' => $objPages->pageId, 'Version' => $objPages->version));
            }
          }
          break;
        case $this->core->sysConfig->generic->actions->change_template_id :

          $objSelect = $this->objModelPages->getPageTable()->select();
          $objSelect->from('pages', array('pageId', 'version'));
          $objSelect->where('id = ?', $this->setup->getElementId());

          $objRowSet = $this->objModelPages->getPageTable()->fetchAll($objSelect);

          if(count($objRowSet) == 1){
            $objPages = $objRowSet->current();

            $strPageId = $objPages->pageId;
            $intPageVersion = $objPages->version;

            $strWhere = $this->objModelPages->getPageTable()->getAdapter()->quoteInto('pageId = ?', $objPages->pageId);
            $strWhere .= $this->objModelPages->getPageTable()->getAdapter()->quoteInto(' AND version = ?', $objPages->version);

            $this->objModelPages->getPageTable()->update(array('idGenericForms' => $this->setup->getGenFormId(),
                                                               'idTemplates'    => $this->setup->getTemplateId(),
                                                               'idUsers'        => $intUserId), $strWhere);
          }
          break;
      }

      /**
       * now save all the special fields
       */
      if(count($this->setup->SpecialFields()) > 0){
        foreach($this->setup->SpecialFields() as $objField){
          $objField->setGenericSetup($this->setup);
          $objField->save($this->setup->getElementId(), 'page', $strPageId, $intPageVersion);
        }
      }

      //page index
      if($this->setup->getElementTypeId() != $this->core->sysConfig->page_types->link->id && $this->setup->getStatusId() == $this->core->sysConfig->status->live){
        if(substr(PHP_OS, 0, 3) === 'WIN') {
          $this->core->logger->warning('slow page index on windows based OS!');
          $this->updateIndex(GLOBAL_ROOT_PATH.$this->core->sysConfig->path->search_index->page, $strPageId);
        }else{
          $strIndexPageFilePath = GLOBAL_ROOT_PATH.'cli/IndexPage.php';
          //run page index in background
          exec("php $strIndexPageFilePath --pageId='".$strPageId."' --version=".$intPageVersion." --languageId=".$this->setup->getLanguageId()." > /dev/null &#038;");
        }
      }else{
        $this->removeFromIndex(GLOBAL_ROOT_PATH.$this->core->sysConfig->path->search_index->page, $strPageId);
      }

      //cache expiring
      if($this->Setup()->getField('url')){
        $strUrl = $this->Setup()->getField('url')->getValue();

        $arrFrontendOptions = array(
          'lifetime' => null, // cache lifetime (in seconds), if set to null, the cache is valid forever.
          'automatic_serialization' => true
        );

        $arrBackendOptions = array(
          'cache_dir' => GLOBAL_ROOT_PATH.'tmp/cache/pages/' // Directory where to put the cache files
        );

        // getting a Zend_Cache_Core object
        $objCache = Zend_Cache::factory('Output',
                                              'File',
                                              $arrFrontendOptions,
                                              $arrBackendOptions);

        $strCacheId = 'page'.preg_replace('/[^a-zA-Z0-9_]/', '_', $strUrl);

        $objCache->remove($strCacheId);

        $objCache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array('StartPage', 'PageType'.$this->core->sysConfig->page_types->overview->id));
      }
      return $this->setup->getElementId();
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * load
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function load(){
    $this->core->logger->debug('massiveart->generic->data->GenericDataTypePage->load()');
    try {

      $objPagesData = $this->getModelPages()->load($this->setup->getElementId());

      if(count($objPagesData) > 0){
        $objPageData = $objPagesData->current();

        /**
         * set some metainformations of current page to get them in the output
         */
        $this->setup->setMetaInformation($objPageData);
        $this->setup->setElementTypeId($objPageData->idPageTypes);
        $this->setup->setIsStartElement($objPageData->isStartPage);
        $this->setup->setParentTypeId($objPageData->idParentTypes);

        /**
         * generic form core fields
         */
        if(count($this->setup->CoreFields()) > 0){
          /**
           * for each core field, try to select the secondary table
           */
          foreach($this->setup->CoreFields() as $strField => $objField){

            $objGenTable = $this->getModelGenericData()->getGenericTable('page'.((substr($strField, strlen($strField) - 1) == 'y') ? ucfirst(rtrim($strField, 'y')).'ies' : ucfirst($strField).'s'));
            $objSelect = $objGenTable->select();

            $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array($strField));
            $objSelect->where('pageId = ?', $objPageData->pageId);
            $objSelect->where('version = ?', $objPageData->version);
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

          $objGenTable = $this->getModelGenericData()->getGenericTable('page-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-InstanceFiles');
          $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

          $objSelect = $objGenTable->select();
          $objSelect->setIntegrityCheck(false);

          $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idFiles'));
          $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
          $objSelect->where('pageId = ?', $objPageData->pageId);
          $objSelect->where('version = ?', $objPageData->version);
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

        /**
         * generic form multi fields
         */
        if(count($this->setup->MultiFields()) > 0){

          $objGenTable = $this->getModelGenericData()->getGenericTable('page-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-InstanceMultiFields');
          $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

          $objSelect = $objGenTable->select();
          $objSelect->setIntegrityCheck(false);

          $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idRelation', 'value'));
          $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
          $objSelect->where('pageId = ?', $objPageData->pageId);
          $objSelect->where('version = ?', $objPageData->version);
          $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

          $arrGenFormsData = $objGenTable->fetchAll($objSelect);

          if(count($arrGenFormsData) > 0){
            $this->blnHasLoadedMultiFieldData = true;
            foreach($arrGenFormsData as $arrGenRowFormsData){
              $arrTmpRelationIds = $this->setup->getMultiField($arrGenRowFormsData->name)->getValue();
              if(is_array($arrTmpRelationIds)){
                array_push($arrTmpRelationIds, $arrGenRowFormsData->idRelation);
              }else{
                $arrTmpRelationIds = array($arrGenRowFormsData->idRelation);
              }
              $this->setup->getMultiField($arrGenRowFormsData->name)->setValue($arrTmpRelationIds);
            }
          }
        }

        /**
         * generic form instance fields
         */
        if(count($this->setup->InstanceFields()) > 0){
          $objGenTable = $this->getModelGenericData()->getGenericTable('page-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Instances');
          $objSelect = $objGenTable->select();

          $arrSelectFields = array();

          /**
           * for each instance field, add to select array data array
           */
          foreach($this->setup->InstanceFields() as $strField => $objField){
            $arrSelectFields[] = $strField;
          }

          $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), $arrSelectFields);
          $objSelect->where('pageId = ?', $objPageData->pageId);
          $objSelect->where('version = ?', $objPageData->version);
          $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

          $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();

          if(count($arrGenFormsData) > 0){
            $this->blnHasLoadedInstanceData = true;
            foreach ($arrGenFormsData as $arrRowGenFormData) {
              foreach ($arrRowGenFormData as $column => $value) {
                if(is_array(json_decode($value))){
                  $this->setup->getInstanceField($column)->setValue(json_decode($value));
                }else{
                  $this->setup->getInstanceField($column)->setValue($value);
                }
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

            $objGenTable = $this->getModelGenericData()->getGenericTable('page-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Region'.$objRegion->getRegionId().'-Instances');
            $objSelect = $objGenTable->select();

            $arrSelectFields = array('id');
            /**
             * for each instance field, add to select array data array
             */
            foreach($objRegion->InstanceFieldNames() as $strField){
              $arrSelectFields[] = $strField;
            }

            $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), $arrSelectFields);
            $objSelect->where('pageId = ?', $objPageData->pageId);
            $objSelect->where('version = ?', $objPageData->version);
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

              $objGenTable = $this->getModelGenericData()->getGenericTable('page-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Region'.$objRegion->getRegionId().'-InstanceFiles');
              $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

              $objSelect = $objGenTable->select();
              $objSelect->setIntegrityCheck(false);

              $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idFiles', 'idRegionInstances'));
              $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
              $objSelect->where('pageId = ?', $objPageData->pageId);
              $objSelect->where('version = ?', $objPageData->version);
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

              $objGenTable = $this->getModelGenericData()->getGenericTable('page-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Region'.$objRegion->getRegionId().'-InstanceMultiFields');
              $strTableName = $objGenTable->info(Zend_Db_Table_Abstract::NAME);

              $objSelect = $objGenTable->select();
              $objSelect->setIntegrityCheck(false);

              $objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array('idRelation', 'value', 'idRegionInstances'));
              $objSelect->join('fields', 'fields.id = `'.$objGenTable->info(Zend_Db_Table_Abstract::NAME).'`.idFields', array('name'));
              $objSelect->where('pageId = ?', $objPageData->pageId);
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

        /**
         * now laod all the special fields
         */
        if(count($this->setup->SpecialFields()) > 0){
          foreach($this->setup->SpecialFields() as $objField){
            $objField->setGenericSetup($this->setup);
            $objField->load($this->setup->getElementId(), 'page', $objPageData->pageId, $objPageData->version);
          }
        }
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getModelPages
   * @return Model_Pages
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelPages(){
    if (null === $this->objModelPages) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Pages.php';
      $this->objModelPages = new Model_Pages();
    }

    return $this->objModelPages;
  }

  /**
   * getModelFolders
   * @return Model_Folders
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelFolders(){
    if (null === $this->objModelFolders) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Folders.php';
      $this->objModelFolders = new Model_Folders();
    }

    return $this->objModelFolders;
  }
}

?>