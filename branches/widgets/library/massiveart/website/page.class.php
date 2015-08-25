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
 * @package    library.massiveart.website
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Page
 *
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-09: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 * @package massiveart.website
 * @subpackage Page
 */

require_once(dirname(__FILE__).'/page/container.class.php');
require_once(dirname(__FILE__).'/page/entry.class.php');

class Page {

  /**
   * @var Core
   */
  protected $core;

  /**
   * @var Model_Pages
   */
  private $objModelPages;

  /**
   * @var Model_Folders
   */
  private $objModelFolders;

  /**
   * @var Model_Contacts
   */
  private $objModelContacts;

  /**
   * @var Model_Categories
   */
  private $objModelCategories;

  /**
   * @var Model_Files
   */
  protected $objModelFiles;

  /**
   * @var Model_Tags
   */
  protected $objModelTags;

  /**
   * @var GenericData
   */
  protected $objGenericData;

  /**
   * @var array
   */
  protected $arrContactsData = array();

  /**
   * @var array
   */
  protected $arrCategoriesData = array();

  /**
   * @var array
   */
  protected $arrTagsData = array();

  /**
   * @var array
   */
  protected $arrFileData = array();

  protected $intRootLevelId;
  protected $strRootLevelTitle;
  protected $intElementId;
  protected $strPageId;
  protected $intPageVersion;
  protected $intLanguageId;
  protected $strTemplateFile;
  protected $intTemplateId;

  protected $strPublisherName;
  protected $strChangeUserName;
  protected $strCreatorName;

  protected $objPublishDate;
  protected $objChangeDate;
  protected $objCreateDate;

  protected $intPageTypeId;
  protected $blnIsStartPage;
  protected $blnShowInNavigation;
  protected $intParentId;
  protected $intParentTypeId;

  /**
   * Constructor
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }

  /**
   * load
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function load(){
    try{
      $this->getModelPages();
      $objPageData = $this->objModelPages->loadById($this->strPageId, $this->intPageVersion);

      if(count($objPageData) > 0){
        $objPage = $objPageData->current();

        $this->objGenericData = new GenericData();
        $this->objGenericData->Setup()->setRootLevelId($this->intRootLevelId);
        $this->objGenericData->Setup()->setFormId($objPage->genericFormId);
        $this->objGenericData->Setup()->setFormVersion($objPage->version);
        $this->objGenericData->Setup()->setFormTypeId($objPage->idGenericFormTypes);
        $this->objGenericData->Setup()->setTemplateId($objPage->idTemplates);
        $this->objGenericData->Setup()->setElementId($objPage->id);
        $this->objGenericData->Setup()->setActionType($this->core->sysConfig->generic->actions->edit);
        $this->objGenericData->Setup()->setFormLanguageId($this->core->sysConfig->languages->default->id);
        $this->objGenericData->Setup()->setLanguageId($this->intLanguageId);
        $this->objGenericData->Setup()->setModelSubPath('cms/models/');

        $this->objGenericData->loadData();

        $this->setElementId($objPage->id);
        $this->setTemplateFile($objPage->filename);
        $this->setTemplateId($objPage->idTemplates);
        $this->setPublisherName($objPage->publisher);
        $this->setPublishDate($objPage->published);
        $this->setCreatorName($objPage->creator);
        $this->setCreateDate($objPage->created);
        $this->setChangeUserName($objPage->changeUser);
        $this->setChangeDate($objPage->changed);
        $this->setPageTypeId($objPage->idPageTypes);
        $this->setIsStartElement($objPage->isStartPage);
        $this->setShowInNavigation($objPage->showInNavigation);
        $this->setParentId($objPage->idParent);
        $this->setParentTypeId($objPage->idParentTypes);

      }else{
        throw new Exception('Not able to load page, because no page found in database!');
      }

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * indexPage
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function indexPage(){
    $this->core->logger->debug('massiveart->website->page->indexPage()');
    try{
      if($this->objGenericData instanceof GenericData){
        $this->objGenericData->indexData(GLOBAL_ROOT_PATH.$this->core->sysConfig->path->search_index->page, $this->strPageId);
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getRegion
   * @param integer $intRegionId
   * @return GenericElementRegion
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getRegion($intRegionId){
    try{
      return $this->objGenericData->Setup()->getRegion($intRegionId);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getField
   * @param string $strFieldName
   * @return GenericElementField
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getField($strFieldName){
    try{
      return $this->objGenericData->Setup()->getField($strFieldName);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }


  /**
   * getFieldValue
   * @param string $strFieldName
   * @return string field value
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFieldValue($strFieldName){
    try{
      $objField = $this->objGenericData->Setup()->getField($strFieldName);
      if(is_object($objField)){
        return $objField->getValue();
      }else{
        return null;
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getFileFieldValue
   * @param string $strFileFieldName
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFileFieldValue($strFileFieldName){
    try{
      if(!array_key_exists($strFileFieldName, $this->arrFileData)){
        $this->arrFileData[$strFileFieldName] = null;

        $strFileIds = $this->objGenericData->Setup()->getFileField($strFileFieldName)->getValue();

        if($strFileIds != ''){
          $this->getModelFiles();
          $this->arrFileData[$strFileFieldName] = $this->objModelFiles->loadFilesById($strFileIds);
        }
      }
      return $this->arrFileData[$strFileFieldName];
    }catch (Exception $exc) {
    	$this->core->logger->err($exc);
    }
  }

  /**
   * getFileFieldValueById
   * @param string $strFileIds
   * @return object $objFiles
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFileFieldValueById($strFileIds){
    try{
      if($strFileIds != ''){
        $this->getModelFiles();
        $objFiles = $this->objModelFiles->loadFilesById($strFileIds);
        return $objFiles;
      }else{
        return '';
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getContactsValues
   * @param string $strFieldName
   * @return object arrContactsData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getContactsValues($strFieldName){
    try{
    	if(!array_key_exists($strFieldName, $this->arrContactsData)){
    	  $this->arrContactsData[$strFieldName] = null;
    	  $mixedIds = self::getFieldValue($strFieldName);

    	  $this->arrContactsData[$strFieldName] = $this->getPageContacts($mixedIds);
    	}
    	return $this->arrContactsData[$strFieldName];

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getPageContacts
   * @param string|array $mixedContactIds
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPageContacts($mixedContactIds){
    try{
      $this->getModelContacts();

      $objContacts = $this->objModelContacts->loadContactsById($mixedContactIds);
      return $objContacts;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getCategoriesValues
   * @param string $strFieldName
   * @return object $objCategoriesData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getCategoriesValues($strFieldName){
    try{
      if(!array_key_exists($strFieldName, $this->arrCategoriesData)){
        $this->arrCategoriesData[$strFieldName] = null;

        $mixedIds = self::getFieldValue($strFieldName);
        $sqlSelect = $this->objGenericData->Setup()->getField($strFieldName)->sqlSelect;

        if(is_array($mixedIds)){
          if(count($mixedIds) > 0){
            $strReplaceWhere = '';
            foreach($mixedIds as $strValue){
              $strReplaceWhere .= $strValue.',';
            }
            $strReplaceWhere = trim($strReplaceWhere, ',');

            $objReplacer = new Replacer();
            $sqlSelect = $objReplacer->sqlReplacer($sqlSelect, $this->intLanguageId, $this->objGenericData->Setup()->getRootLevelId(), ' AND tbl.id IN ('.$strReplaceWhere.')');
            $this->arrCategoriesData[$strFieldName] = $this->core->dbh->query($sqlSelect)->fetchAll(Zend_Db::FETCH_OBJ);
          }
        }else if ($mixedIds != ''){
          $objReplacer = new Replacer();
          $sqlSelect = $objReplacer->sqlReplacer($sqlSelect, $this->intLanguageId, $this->objGenericData->Setup()->getRootLevelId(), ' AND tbl.id = '.$mixedIds);
          $this->arrCategoriesData[$strFieldName] = $this->core->dbh->query($sqlSelect)->fetchAll(Zend_Db::FETCH_OBJ);
        }
      }
      return $this->arrCategoriesData[$strFieldName];

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getTagsValues
   * @param string $strFieldName
   * @return object $objTagsData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getTagsValues($strFieldName){
    try{
      if(!array_key_exists($strFieldName, $this->arrTagsData)){
        $this->getModelTags();
        $this->objModelTags->setLanguageId($this->intLanguageId);
        $this->arrTagsData[$strFieldName] = $this->objModelTags->loadTypeTags('page', $this->strPageId, $this->intPageVersion);
      }
      return $this->arrTagsData[$strFieldName];
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getOverviewContainer
   * @return array $arrContainer
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getOverviewContainer(){
    try{
      $arrContainer = array();
      $arrGenForms = array();
      $arrPageEntries = array();
      $arrPageIds = array();
      $counter = 0;

      $objMyMultiRegion = $this->getRegion(15); //15 is the default overview block region

      if($objMyMultiRegion instanceof GenericElementRegion){
       foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){

          $objContainer = new PageContainer();
          $objContainer->setContainerKey($objMyMultiRegion->getField('entry_category')->getInstanceValue($intRegionInstanceId));
          $objContainer->setContainerTitle($objMyMultiRegion->getField('entry_title')->getInstanceValue($intRegionInstanceId));
          $objContainer->setEntryNumber($objMyMultiRegion->getField('entry_number')->getInstanceValue($intRegionInstanceId));
          $objContainer->setEntryViewType($objMyMultiRegion->getField('entry_viewtype')->getInstanceValue($intRegionInstanceId));

          if($objContainer->getEntryNumber() > 0){
            $objContainer->setContainerLabel($objMyMultiRegion->getField('entry_label')->getInstanceValue($intRegionInstanceId));
            $objContainer->setContainerSortType($objMyMultiRegion->getField('entry_sorttype')->getInstanceValue($intRegionInstanceId));
            $objContainer->setContainerSortOrder($objMyMultiRegion->getField('entry_sortorder')->getInstanceValue($intRegionInstanceId));
            $objContainer->setContainerDepth($objMyMultiRegion->getField('entry_depth')->getInstanceValue($intRegionInstanceId));

            $objEntries = $this->getOverviewPages($objContainer->getContainerKey(), $objContainer->getContainerLabel(), $objContainer->getEntryNumber(), $objContainer->getContainerSortType(), $objContainer->getContainerSortOrder(), $objContainer->getContainerDepth(), $arrPageIds);
            if(count($objEntries) > 0){
              foreach($objEntries as $objEntryData){
                $objEntry = new PageEntry();
                if($objEntryData->idPageTypes == $this->core->sysConfig->page_types->link->id){
                  $objEntry->setEntryId($objEntryData->plId);
                  $objEntry->title = $objEntryData->title;
                  $objEntry->url = '/'.strtolower($objEntryData->languageCode).'/'.$objEntryData->plUrl;

                  $arrGenForms[$objEntryData->plGenericFormId.'-'.$objEntryData->plVersion][] = $objEntryData->plId;
                  $arrPageEntries[$objEntryData->plId] = $counter;

                  $objContainer->addPageEntry($objEntry, 'entry_'.$objEntryData->plId);
                }else{
                  $objEntry->setEntryId($objEntryData->id);
                  $objEntry->title = $objEntryData->title;
                  $objEntry->url = '/'.strtolower($objEntryData->languageCode).'/'.$objEntryData->url;

                  $arrGenForms[$objEntryData->genericFormId.'-'.$objEntryData->version][] = $objEntryData->id;
                  $arrPageEntries[$objEntryData->id] = $counter;

                  $objContainer->addPageEntry($objEntry, 'entry_'.$objEntryData->id);
                }
                array_push($arrPageIds, $objEntryData->id);
              }
            }
          }
          $arrContainer[$counter] = $objContainer;
          $counter++;
        }
      }

      /**
       * get data of instance tables
       */
      if(count($arrGenForms) > 0){
        foreach($arrGenForms as $key => $arrPageIds){
          $arrGenFormPageIds = array();
          if(count($arrPageIds) > 0){
            foreach($arrPageIds as $value){
              array_push($arrGenFormPageIds, $value);
            }
          }
          $objPageRowset = $this->objModelPages->loadsInstanceDataByIds($key, $arrGenFormPageIds);

          /**
           * overwrite page entries
           */
          if(isset($objPageRowset) && count($objPageRowset) > 0){
            foreach($objPageRowset as $objPageRow){
              if(array_key_exists($objPageRow->id, $arrPageEntries)){
                if(array_key_exists($arrPageEntries[$objPageRow->id], $arrContainer)){
                  $objPageEntry = $arrContainer[$arrPageEntries[$objPageRow->id]]->getPageEntry('entry_'.$objPageRow->id);
                  $objPageEntry->shortdescription = (isset($objPageRow->shortdescription)) ? $objPageRow->shortdescription : '';
                  $objPageEntry->description = (isset($objPageRow->description)) ? $objPageRow->description : '';
                  $objPageEntry->filename = (isset($objPageRow->filename)) ? $objPageRow->filename : '';
                  $objPageEntry->filetitle = (isset($objPageRow->filetitle)) ? $objPageRow->filetitle : '';

                  $arrContainer[$arrPageEntries[$objPageRow->id]]->addPageEntry($objPageEntry, 'entry_'.$objPageRow->id);
                }
              }
            }
          }
        }
      }

      return $arrContainer;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getOverviewPages
   * @param integer $intCategoryId
   * @param integer $intEntryNumber
   * @param integer $intSortType
   * @param integer $intSortOrder
   * @param integer $intEntryDepth
   * @param array $arrPageIds
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getOverviewPages($intCategoryId, $intLabelId, $intEntryNumber, $intSortType, $intSortOrder, $intEntryDepth, $arrPageIds){
    try{
      $this->getModelPages();

      $objPages = $this->objModelPages->loads($this->intParentId, $intCategoryId, $intLabelId, $intEntryNumber, $intSortType, $intSortOrder, $intEntryDepth, $arrPageIds);
      return $objPages;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getPagesContainer
   * @return array $arrContainer
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPagesContainer(){
    try{
      $arrContainer = array();
      $arrGenForms = array();
      $arrPageEntries = array();
      $counter = 0;

      $objMyMultiRegion = $this->getRegion(17);

      if($objMyMultiRegion instanceof GenericElementRegion){
       foreach($objMyMultiRegion->RegionInstanceIds() as $intRegionInstanceId){

          $objContainer = new PageContainer();
          $objContainer->setContainerKey($objMyMultiRegion->getField('entry_nav_point')->getInstanceValue($intRegionInstanceId));
          $objContainer->setContainerTitle($objMyMultiRegion->getField('entry_title')->getInstanceValue($intRegionInstanceId));
          $objContainer->setEntryNumber($objMyMultiRegion->getField('entry_number')->getInstanceValue($intRegionInstanceId));

          if($objContainer->getContainerKey() > 0 && $objContainer->getEntryNumber() > 0){
            $intEntryCategory = $objMyMultiRegion->getField('entry_category')->getInstanceValue($intRegionInstanceId);
            $intEntryLabel = $objMyMultiRegion->getField('entry_label')->getInstanceValue($intRegionInstanceId);
            $intEntrySortType = $objMyMultiRegion->getField('entry_sorttype')->getInstanceValue($intRegionInstanceId);
            $intEntrySortOrder = $objMyMultiRegion->getField('entry_sortorder')->getInstanceValue($intRegionInstanceId);

            $objContainer->setContainerSortType($intEntrySortType);
            $objContainer->setContainerSortOrder($intEntrySortOrder);

            $objEntries = $this->getFolderChildPages($objContainer->getContainerKey(), $intEntryCategory, $intEntryLabel, $objContainer->getEntryNumber(), $objContainer->getContainerSortType(), $objContainer->getContainerSortOrder());

            if(count($objEntries) > 0){
              foreach($objEntries as $objEntryData){
                $objEntry = new PageEntry();

                $objEntry->setEntryId($objEntryData->idPage);
                $objEntry->title = $objEntryData->title;
                $objEntry->url = '/'.strtolower($objEntryData->languageCode).'/'.$objEntryData->url;
                $objEntry->created = $objEntryData->pageCreated;

                $arrGenForms[$objEntryData->genericFormId.'-'.$objEntryData->version][] = $objEntryData->idPage;
                $arrPageEntries[$objEntryData->idPage] = $counter;

                $objContainer->addPageEntry($objEntry, 'entry_'.$objEntryData->idPage);
              }
            }
          }
          $arrContainer[$counter] = $objContainer;
          $counter++;
        }
      }

      /**
       * get data of instance tables
       */
      if(count($arrGenForms) > 0){
        foreach($arrGenForms as $key => $arrPageIds){
          $arrGenFormPageIds = array();
          if(count($arrPageIds) > 0){
            foreach($arrPageIds as $value){
              array_push($arrGenFormPageIds, $value);
            }
          }
          $objPageRowset = $this->objModelPages->loadsInstanceDataByIds($key, $arrGenFormPageIds);

          /**
           * overwrite page entries
           */
          if(isset($objPageRowset) && count($objPageRowset) > 0){
            foreach($objPageRowset as $objPageRow){
              if(array_key_exists($objPageRow->id, $arrPageEntries)){
                if(array_key_exists($arrPageEntries[$objPageRow->id], $arrContainer)){
                  $objPageEntry = $arrContainer[$arrPageEntries[$objPageRow->id]]->getPageEntry('entry_'.$objPageRow->id);
                  $objPageEntry->shortdescription = (isset($objPageRow->shortdescription)) ? $objPageRow->shortdescription : '';
                  $objPageEntry->description = (isset($objPageRow->description)) ? $objPageRow->description : '';
                  $objPageEntry->filename = (isset($objPageRow->filename)) ? $objPageRow->filename : '';
                  $objPageEntry->filetitle = (isset($objPageRow->filetitle)) ? $objPageRow->filetitle : '';

                  $arrContainer[$arrPageEntries[$objPageRow->id]]->addPageEntry($objPageEntry, 'entry_'.$objPageRow->id);
                }
              }
            }
          }
        }
      }

      return $arrContainer;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getFolderChildPages
   * @param integer $intFolderId
   * @param integer $intCategoryId
   * @param integer $intLimitNumber
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFolderChildPages($intFolderId, $intCategoryId, $intLabelId, $intLimitNumber, $strSortType, $strSortOrder){
    try{
      $this->getModelFolders();

      $objPages = $this->objModelFolders->loadFolderChildPages($intFolderId, $intCategoryId, $intLabelId, $intLimitNumber, $strSortType, $strSortOrder);
      return $objPages;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getPagesByCategory
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPagesByCategory(){
    try{
      $this->getModelPages();

      $intCategoryId = $this->objGenericData->Setup()->getField('top_category')->getValue();
      $intLabelId = $this->objGenericData->Setup()->getField('top_label')->getValue();
      $intLimitNumber = $this->objGenericData->Setup()->getField('top_number')->getValue();
      $intSortType = $this->objGenericData->Setup()->getField('top_sorttype')->getValue();
      $intSortOrder = $this->objGenericData->Setup()->getField('top_sortorder')->getValue();

      $objPages = $this->objModelPages->loadsByCategory($this->intRootLevelId, $intCategoryId, $intLabelId, $intLimitNumber, $intSortType, $intSortOrder);

      return $objPages;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getEventsContainer
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getEventsContainer($intQuarter = 0, $intYear = 0){
    try{
      $arrContainer = array();
      $arrGenForms = array();
      $arrPageEntries = array();
      $arrPageIds = array();
      $counter = 0;

      $objContainer = new PageContainer();
      $objEntries = $this->getPagesByTemplate($this->core->sysConfig->page_types->page->event_templateId, $intQuarter, $intYear);

      if(count($objEntries) > 0){
        foreach($objEntries as $objEntryData){
          $objEntry = new PageEntry();
          $objEntry->setEntryId($objEntryData->id);
          $objEntry->title = $objEntryData->title;
          $objEntry->url = '/'.strtolower($objEntryData->languageCode).'/'.$objEntryData->url;
          $objEntry->datetime = $objEntryData->datetime;

          $arrGenForms[$objEntryData->genericFormId.'-'.$objEntryData->version][] = $objEntryData->id;
          $arrPageEntries[$objEntryData->id] = $counter;

          $objContainer->addPageEntry($objEntry, 'entry_'.$objEntryData->id);
        }
        $arrContainer[$counter] = $objContainer;
        $counter++;
      }

      /**
       * get data of instance tables
       */
      if(count($arrGenForms) > 0){
        foreach($arrGenForms as $key => $arrPageIds){
          $arrGenFormPageIds = array();
          if(count($arrPageIds) > 0){
            foreach($arrPageIds as $value){
              array_push($arrGenFormPageIds, $value);
            }
          }
          $objPageRowset = $this->objModelPages->loadsInstanceDataByIds($key, $arrGenFormPageIds);

          /**
           * overwrite page entries
           */
          if(isset($objPageRowset) && count($objPageRowset) > 0){
            foreach($objPageRowset as $objPageRow){
              if(array_key_exists($objPageRow->id, $arrPageEntries)){
                if(array_key_exists($arrPageEntries[$objPageRow->id], $arrContainer)){
                  $objPageEntry = $arrContainer[$arrPageEntries[$objPageRow->id]]->getPageEntry('entry_'.$objPageRow->id);
                  $objPageEntry->shortdescription = (isset($objPageRow->shortdescription)) ? $objPageRow->shortdescription : '';
                  $objPageEntry->description = (isset($objPageRow->description)) ? $objPageRow->description : '';
                  $objPageEntry->event_status = (isset($objPageRow->event_status)) ? $objPageRow->event_status : '';
                  $objPageEntry->filename = (isset($objPageRow->filename)) ? $objPageRow->filename : '';
                  $objPageEntry->filetitle = (isset($objPageRow->filetitle)) ? $objPageRow->filetitle : '';

                  $arrContainer[$arrPageEntries[$objPageRow->id]]->addPageEntry($objPageEntry, 'entry_'.$objPageRow->id);
                }
              }
            }
          }
        }
      }

      return $arrContainer;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getPagesByTemplate
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPagesByTemplate($intTemplateId, $intQuarter = 0, $intYear = 0){
    try{
      $this->getModelPages();
      $objPages = $this->objModelPages->loadsByTemplatedId($intTemplateId, $intQuarter, $intYear);
      return $objPages;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getPageInstanceDataById
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPageInstanceDataById($intPageId, $strGenForm){
    try{
      $this->getModelPages();

      $objPageRowset = $this->objModelPages->loadInstanceDataById($intPageId, $strGenForm);
      return $objPageRowset;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * getModelPages
   * @return Model_Pages
   * @author Cornelius Hansjakob <cha@massiveart.com>
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
      $this->objModelPages->setLanguageId($this->intLanguageId);
    }

    return $this->objModelPages;
  }

  /**
   * getModelFolders
   * @return Model_Folders
   * @author Cornelius Hansjakob <cha@massiveart.com>
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
      $this->objModelFolders->setLanguageId($this->intLanguageId);
    }

    return $this->objModelFolders;
  }

  /**
   * getModelContacts
   * @return Model_Contacts
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelContacts(){
    if (null === $this->objModelContacts) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Contacts.php';
      $this->objModelContacts = new Model_Contacts();
      $this->objModelContacts->setLanguageId($this->intLanguageId);
    }

    return $this->objModelContacts;
  }

  /**
   * getModelCategories
   * @return Model_Categories
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelCategories(){
    if (null === $this->objModelCategories) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Categories.php';
      $this->objModelCategories = new Model_Categories();
      $this->objModelCategories->setLanguageId($this->intLanguageId);
    }

    return $this->objModelCategories;
  }

  /**
   * getModelFiles
   * @return Model_Files
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelFiles(){
    if (null === $this->objModelFiles) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Files.php';
      $this->objModelFiles = new Model_Files();
      $this->objModelFiles->setLanguageId($this->intLanguageId);
    }

    return $this->objModelFiles;
  }

  /**
   * getModelTags
   * @return Model_Tags
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelTags(){
    if (null === $this->objModelTags) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Tags.php';
      $this->objModelTags = new Model_Tags();
    }

    return $this->objModelFiles;
  }

  /**
   * setRootLevelId
   * @param integer $intRootLevelId
   */
  public function setRootLevelId($intRootLevelId){
    $this->intRootLevelId = $intRootLevelId;
  }

  /**
   * getRootLevelId
   * @param integer $intRootLevelId
   */
  public function getRootLevelId(){
    return $this->intRootLevelId;
  }

  /**
   * setRootLevelTitle
   * @param stirng $strRootLevelTitle
   */
  public function setRootLevelTitle($strRootLevelTitle){
    $this->strRootLevelTitle = $strRootLevelTitle;
  }

  /**
   * getRootLevelTitle
   * @param string $strRootLevelTitle
   */
  public function getRootLevelTitle(){
    return $this->strRootLevelTitle;
  }

  /**
   * setElementId
   * @param integer $intElementId
   */
  public function setElementId($intElementId){
    $this->intElementId = $intElementId;
  }

  /**
   * getElementId
   * @param integer $intElementId
   */
  public function getElementId(){
    return $this->intElementId;
  }

  /**
   * setPageId
   * @param stirng $strPageId
   */
  public function setPageId($strPageId){
    $this->strPageId = $strPageId;
  }

  /**
   * getPageId
   * @param string $strPageId
   */
  public function getPageId(){
    return $this->strPageId;
  }

  /**
   * setPageVersion
   * @param integer $intPageVersion
   */
  public function setPageVersion($intPageVersion){
    $this->intPageVersion = $intPageVersion;
  }

  /**
   * getPageVersion
   * @param integer $intPageVersion
   */
  public function getPageVersion(){
    return $this->intPageVersion;
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
   * setTemplateFile
   * @param stirng $strTemplateFile
   */
  public function setTemplateFile($strTemplateFile){
    $this->strTemplateFile = $strTemplateFile;
  }

  /**
   * getTemplateFile
   * @param string $strTemplateFile
   */
  public function getTemplateFile(){
    return $this->strTemplateFile;
  }

  /**
   * setTemplateId
   * @param integer $intTemplateId
   */
  public function setTemplateId($intTemplateId){
    $this->intTemplateId = $intTemplateId;
  }

  /**
   * getTemplateId
   * @return integer $intTemplateId
   */
  public function getTemplateId(){
    return $this->intTemplateId;
  }

  /**
   * setPublisherName
   * @param stirng $strPublisherName
   */
  public function setPublisherName($strPublisherName){
    $this->strPublisherName = $strPublisherName;
  }

  /**
   * getPublisherName
   * @param string $strPublisherName
   */
  public function getPublisherName(){
    return $this->strPublisherName;
  }

  /**
   * setChangeUserName
   * @param stirng $strChangeUserName
   */
  public function setChangeUserName($strChangeUserName){
    $this->strChangeUserName = $strChangeUserName;
  }

  /**
   * getChangeUserName
   * @param string $strChangeUserName
   */
  public function getChangeUserName(){
    return $this->strChangeUserName;
  }

  /**
   * setCreatorName
   * @param stirng $strCreatorName
   */
  public function setCreatorName($strCreatorName){
    $this->strCreatorName = $strCreatorName;
  }

  /**
   * getCreatorName
   * @param string $strCreatorName
   */
  public function getCreatorName(){
    return $this->strCreatorName;
  }

  /**
   * setPageTypeId
   * @param integer $intPageTypeId
   */
  public function setPageTypeId($intPageTypeId){
    $this->intPageTypeId = $intPageTypeId;
  }

  /**
   * getPageTypeId
   * @param integer $intPageTypeId
   */
  public function getPageTypeId(){
    return $this->intPageTypeId;
  }

  /**
   * setParentId
   * @param integer $intParentId
   */
  public function setParentId($intParentId){
    $this->intParentId = $intParentId;
  }

  /**
   * getParentId
   * @param integer $intParentId
   */
  public function getParentId(){
    return $this->intParentId;
  }

  /**
   * setParentTypeId
   * @param integer $intParentTypeId
   */
  public function setParentTypeId($intParentTypeId){
    $this->intParentTypeId = $intParentTypeId;
  }

  /**
   * getParentTypeId
   * @param integer $intParentTypeId
   */
  public function getParentTypeId(){
    return $this->intParentTypeId;
  }

  /**
   * setIsStartElement
   * @param boolean $blnIsStartPage
   */
  public function setIsStartElement($blnIsStartPage, $blnValidate = true){
    if($blnValidate == true){
      if($blnIsStartPage === true || $blnIsStartPage === 'true' || $blnIsStartPage == 1){
        $this->blnIsStartPage = true;
      }else{
        $this->blnIsStartPage = false;
      }
    }else{
      $this->blnIsStartPage = $blnIsStartPage;
    }
  }

  /**
   * getIsStartElement
   * @return boolean $blnIsStartPage
   */
  public function getIsStartElement($blnReturnAsNumber = true){
    if($blnReturnAsNumber == true){
      if($this->blnIsStartPage == true){
        return 1;
      }else{
        return 0;
      }
    }else{
      return $this->blnIsStartPage;
    }
  }

  /**
   * setShowInNavigation
   * @param boolean $blnShowInNavigation
   */
  public function setShowInNavigation($blnShowInNavigation, $blnValidate = true){
    if($blnValidate == true){
      if($blnShowInNavigation === true || $blnShowInNavigation === 'true' || $blnShowInNavigation == 1){
        $this->blnShowInNavigation = true;
      }else{
        $this->blnShowInNavigation = false;
      }
    }else{
      $this->blnShowInNavigation = $blnShowInNavigation;
    }
  }

  /**
   * getShowInNavigation
   * @return boolean $blnShowInNavigation
   */
  public function getShowInNavigation($blnReturnAsNumber = true){
    if($blnReturnAsNumber == true){
      if($this->blnShowInNavigation == true){
        return 1;
      }else{
        return 0;
      }
    }else{
      return $this->blnShowInNavigation;
    }
  }

  /**
   * setPublishDate
   * @param string/obj $Date
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function setPublishDate($Date, $blnIsValidDateObj = false){
    if($blnIsValidDateObj == true){
      $this->objPublishDate = $Date;
    }else{
      $arrTmpTimeStamp = explode(' ', $Date);
      if(count($arrTmpTimeStamp) > 1){
        $arrTmpTime = explode(':', $arrTmpTimeStamp[1]);
        $arrTmpDate = explode('-', $arrTmpTimeStamp[0]);
        if(count($arrTmpDate) == 3){
          $this->objPublishDate =  mktime($arrTmpTime[0], $arrTmpTime[1], $arrTmpTime[2], $arrTmpDate[1], $arrTmpDate[2], $arrTmpDate[0]);
        }
      }
    }
  }

  /**
   * getPublishDate
   * @param string $strFormat
   * @return string $strPublishDate
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getPublishDate($strFormat = 'd.m.Y', $blnGetDateObj = false){
    if($blnGetDateObj == true){
      return $this->objPublishDate;
    }else{
      if($this->objPublishDate != null){
        return date($strFormat, $this->objPublishDate);
      }else{
        return null;
      }
    }
  }

  /**
   * setChangeDate
   * @param string/obj $Date
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function setChangeDate($Date, $blnIsValidDateObj = false){
    if($blnIsValidDateObj == true){
      $this->objChangeDate = $Date;
    }else{
      $arrTmpTimeStamp = explode(' ', $Date);
      if(count($arrTmpTimeStamp) > 0){
        $arrTmpTime = explode(':', $arrTmpTimeStamp[1]);
        $arrTmpDate = explode('-', $arrTmpTimeStamp[0]);
        if(count($arrTmpDate) == 3){
          $this->objChangeDate =  mktime($arrTmpTime[0], $arrTmpTime[1], $arrTmpTime[2], $arrTmpDate[1], $arrTmpDate[2], $arrTmpDate[0]);
        }
      }
    }
  }

  /**
   * getChangeDate
   * @param string $strFormat
   * @return string $strChangeDate
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getChangeDate($strFormat = 'd.m.Y', $blnGetDateObj = false){
    if($blnGetDateObj == true){
      return $this->objChangeDate;
    }else{
      if($this->objChangeDate != null){
        return date($strFormat, $this->objChangeDate);
      }else{
        return null;
      }
    }
  }

  /**
   * setCreateDate
   * @param string/obj $Date
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function setCreateDate($Date, $blnIsValidDateObj = false){
    if($blnIsValidDateObj == true){
      $this->objCreateDate = $Date;
    }else{
      $arrTmpTimeStamp = explode(' ', $Date);
      if(count($arrTmpTimeStamp) > 0){
        $arrTmpTime = explode(':', $arrTmpTimeStamp[1]);
        $arrTmpDate = explode('-', $arrTmpTimeStamp[0]);
        if(count($arrTmpDate) == 3){
          $this->objCreateDate =  mktime($arrTmpTime[0], $arrTmpTime[1], $arrTmpTime[2], $arrTmpDate[1], $arrTmpDate[2], $arrTmpDate[0]);
        }
      }
    }
  }

  /**
   * getCreateDate
   * @param string $strFormat
   * @return string $strCreateDate
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getCreateDate($strFormat = 'd.m.Y', $blnGetDateObj = false){
    if($blnGetDateObj == true){
      return $this->objCreateDate;
    }else{
      if($this->objCreateDate != null){
        return date($strFormat, $this->objCreateDate);
      }else{
        return null;
      }
    }
  }

}

?>