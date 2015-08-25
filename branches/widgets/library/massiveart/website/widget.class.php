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
 * Widget
 *
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-19: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 * @package massiveart.website
 * @subpackage Widget
 */

class Widget{
	/**
   * @var Core
   */
  protected $core;
  
  protected $intRootLevelId;
  protected $strRootLevelTitle;
  protected $intWidgetId;
  protected $intWidgetVersion;
  protected $intLanguageId;
  protected $strLanguageCode;
  
  protected $intTemplateId;
  protected $strNavigationUrl;
  protected $strTemplateFile;
  
  protected $strWidgetInstanceId;
  protected $strActionName;
  protected $strWidgetName;
  protected $strWidgetTitle;
  
  protected $intGenericFormTypesId;
  protected $intGenericFormVersion;
  
  protected $strUrlParentId;
  protected $objGenericData;
  protected $objModelGenericData;
  protected $objModelFiles;
 
	/**
   * Constructor
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }
  
  /**
   * loadMultiplyFields
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadMultiplyFields() {
  	$this->core->logger->debug('massiveart->website->widget->loadMultiplyFields()');
  	try{
  		/*
  		 * Multiply Fields
  		 */
  		$intRegionId=11;
  		$objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->getGenericFormId().'-'.$this->getGenericFormVersion().'-Region'.$intRegionId.'-Instances');
      $objSelect = $objGenTable->select();
      $objSelect->where('subwidgetId = ?', $this->getWidgetInstanceId());
      $objSelect->where('version = ?', $this->getGenericFormVersion());
      $objSelect->where('idLanguages = ?', $this->getLanguageId());
      $objSelect->order(array('sortPosition'));
      $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();
      
      return $arrGenFormsData;
     	
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
	/**
   * loadFieldFiles
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadFieldFiles($intRegionInstanceId) {
  	$this->core->logger->debug('massiveart->website->widget->loadFieldFiles()');
  	try{
  		
  		/*
  		 * Multiply Fields
  		 */
  		$intRegionId=11;
  		$objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->getGenericFormId().'-'.$this->getGenericFormVersion().'-InstanceFiles');
      $objSelect = $objGenTable->select();
      $objSelect->where('subwidgetId = ?', $this->getWidgetInstanceId());
      $objSelect->where('version = ?', $this->getGenericFormVersion());
      $objSelect->where('idLanguages = ?', $this->getLanguageId());
      $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();
      
      return $arrGenFormsData;
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
	/**
   * loadMultiplyFieldFiles
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadMultiplyFieldFiles($intRegionInstanceId) {
  	$this->core->logger->debug('massiveart->website->widget->loadMultiplyFieldFiles()');
  	try{
  		
  		/*
  		 * Multiply Fields
  		 */
  		$intRegionId=11;
  		$objGenTable = $this->getModelGenericData()->getGenericTable('subwidget-'.$this->getGenericFormId().'-'.$this->getGenericFormVersion().'-Region'.$intRegionId.'-InstanceFiles');
      $objSelect = $objGenTable->select();
      $objSelect->where('subwidgetId = ?', $this->getWidgetInstanceId());
      $objSelect->where('version = ?', $this->getGenericFormVersion());
      $objSelect->where('idLanguages = ?', $this->getLanguageId());
      $objSelect->where('idRegionInstances = ?', $intRegionInstanceId);
      $arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();
      
      return $arrGenFormsData;
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
   * getModelGenericData
   * @return Model_GenericData
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getModelGenericData(){
    if (null === $this->objModelGenericData) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/GenericData.php';
      $this->objModelGenericData = new Model_GenericData();
    }

    return $this->objModelGenericData;
  }
  
  /**
   * getWidgetObject
   * @return object Widget
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getWidgetObject(){
  	return Zend_Registry::get('Core');
  }
  
	/**
   * setGenericFormVersion
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setGenericFormVersion($intGenericFormVersion){
  	$this->intGenericFormVersion=$intGenericFormVersion;
  }
  
  /**
   * getGenericFormId
   * @return integer intGenericFormId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getGenericFormVersion(){
  	return $this->intGenericFormVersion;
  }
  
  /**
   * setGenericFormId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setGenericFormId($intGenericFormId){
  	$this->intGenericFormId=$intGenericFormId;
  }
  
  /**
   * getGenericFormId
   * @return integer intGenericFormId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getGenericFormId(){
  	return $this->intGenericFormId;
  }
  
	/**
   * getRegion
   * @param integer $intRegionId
   * @return GenericElementRegion
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getRegion($intRegionId){
    try{
      $this->load();
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * setGenericFormTypesId
   * @param $intId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setGenericFormTypesId($intId){
  	$this->intGenericFormTypesId = $intId;
  }
  
	/**
   * getGenericFormTypesId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getGenericFormTypesId(){
  	return $this->intGenericFormTypesId;
  }
  
	/**
   * setUrlParentId
   * @param $strUrlParentId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setUrlParentId($strUrlParentId){
  	$this->strUrlParentId = $strUrlParentId;
  }
  
	/**
   * getUrlParentId
   * @return $strUrlParentId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
	public function getUrlParentId(){
  	return $this->strUrlParentId;
  }
  
  /**
   * setRootLevelId
   * @param $intRootLevelId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setRootLevelId($intRootLevelId){
  	$this->intRootLevelId = $intRootLevelId;
  }
  
  /**
   * getRootLevelId
   * @return integer
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getRootLevelId(){
  	return $this->intRootLevelId;
  }
  
  /**
   * setRootLevelTitle
   * @param $strRootLevelTitle
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setRootLevelTitle($strRootLevelTitle){
  	$this->strRootLevelTitle = $strRootLevelTitle;
  }
  
  /**
   * getRootLevelTitle
   * @return strRootLevelTitle
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
	public function getRootLevelTitle(){
  	return $this->strRootLevelTitle;
  }
  
  /**
   * setWidgetVersion
   * @param $intVersion
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setWidgetVersion($intVersion){
  	$this->intWidgetVersion = $intVersion;
  }
  
  /**
   * setLanguageCode
   * @param string $strLanguageCode
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setLanguageCode($strLanguageCode){
  	$this->strLanguageCode = $strLanguageCode;	
  }
  
  /**
   * getLanguageCode
   * @return string $strLanguageCode
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getLanguageCode() {
  	return $this->strLanguageCode;
  }
  
  /**
   * setLanguageId
   * @param $intLanguageId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setLanguageId($intLanguageId){
  	$this->intLanguageId = $intLanguageId;
  }  
  
  /**
   * getLanguageId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getLanguageId(){
  	return $this->intLanguageId;
  } 
  
  /**
   * setWidgetInstanceId
   * @param $strWidgetInstanceId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setWidgetInstanceId($strWidgetInstanceId){
  	$this->strWidgetInstanceId = $strWidgetInstanceId;
  }
  
  /**
   * getWidgetInstanceId
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getWidgetInstanceId(){
  	return $this->strWidgetInstanceId;
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
   * setNavigationUrl
   * @param string $strNavigationUrl
   */
  public function setNavigationUrl($strNavigationUrl){
  	$this->strNavigationUrl = $strNavigationUrl;
  }
  
  /**
   * getNavigationUrl
   * @return string $strNavigationUrl
   */
  public function getNavigationUrl(){
  	return $this->strNavigationUrl;
  }
  
	/**
   * setTemplateFile
   * @param string $strTemplateFile
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
   * setAction
   * @param $strActionname
   * @return string $strActionname
   */
  public function setAction($strActionName){
  	$this->strActionName = $strActionName;
  }
  
  /**
   * getAction
   * @return string $strActionName
   */
  public function getAction() {
  	return $this->strActionName;
  }
  
  /**
   * setWidgetName
   * @param $strWidgetName
   * @return string $strWidgetName
   */
  public function setWidgetName($strWidgetName){
  	$this->strWidgetName = $strWidgetName;
  }
  
  /**
   * getWidgetName
   * @return string $strWidgetName
   */
  public function getWidgetName(){
  	return $this->strWidgetName;
  }
  
  /**
   * setWidgetTitle
   * @param $strWidgetName
   * @return string $strWidgetName
   */
  public function setWidgetTitle($strWidgetTitle){
    $this->strWidgetTitle = $strWidgetTitle;
  }
  
  /**
   * getWidgetTitle
   * @return string $strWidgetName
   */
  public function getWidgetTitle(){
    return $this->strWidgetTitle;
  }
}
 
?>