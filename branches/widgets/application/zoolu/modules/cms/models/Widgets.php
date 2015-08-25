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
 * Model_Widgets
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-06: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 *
 */
class Model_Widgets {
	/**
	 * @var Core
	 */
	private $core;
	
	/**
	 * @var Model_Table_Widgets
	 */
	protected $objWidgetsTable;
	
	/**
	 * @var Model_Table_GenericForms
	 */
	protected $objGenericFormsTable;
	
	/**
	 * @var Model_Table_WidgetInstances
	 */
	protected $objWidgetInstancesTable;
	
	/**
	 * @var Model_Table_UrlReplacers
	 */
	protected $objUrlReplacersTable;
	
	/**
	 * @var Object_Url_Replacers
	 */
	protected $objUrlReplacers;
	
	/**
	 * @var Model_Table_Url
	 */
	protected $objUrlTable;
	
	/**
	 * @var Model_Subwidgets
	 */
	protected $objModelSubwidgets;
	
	/**
	 * @var number
	 */
	private $intLanguageId;
	
	/**
	 * UrlType Widget
	 */
	const URL_TYPE_WIDGET=2;
	
	/**
	 * Constructor
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function __construct() {
		$this->core = Zend_Registry::get('Core');
	}
	
	/**
   * getGenericFormsTable
   * @return Zend_Db_Table_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getGenericFormsTable(){

    if($this->objGenericFormsTable === null) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/GenericForms.php';
      $this->objGenericFormsTable = new Model_Table_GenericForms();
    }

    return $this->objGenericFormsTable;
  }
	
  /**
   * getGenericFormByWidgetId
   * @param $intIdWidget
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
	public function getGenericFormByWidgetId($intIdWidget){
		$this->core->logger->debug('cms->models->Model_Widgets->getGenericFormByWidgetId('.$intIdWidget.')');
		
		$objSelect = $this->getWidgetsTable()->select();
		$objSelect->setIntegrityCheck(false);
		$objSelect->from($this->objWidgetsTable, array('name'));
		$objSelect->where('id=?', $intIdWidget);
		$objRowWidget = $this->objWidgetsTable->fetchRow($objSelect);
		
		$strGenericFormId = 'W_'.strtoupper($objRowWidget->name).'_DEFAULT';
		$this->core->logger->debug('cms->models->Model_Widgets->getGenericFormByWidgetId(), GenericFormId: '.$strGenericFormId);
		
		$objSelectForm = $this->getGenericFormsTable()->select();
		$objSelectForm->setIntegrityCheck(false);
		$objSelectForm->from($this->objGenericFormsTable, array('id', 'genericFormId', 'version'));
		$objSelectForm->where('genericFormId=?', $strGenericFormId);
		
		return $this->objGenericFormsTable->fetchRow($objSelectForm);
	}
	
	/**
	 * getGenericFormById
	 * @param $strId
	 * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
	 */
	public function getGenericFormById($strId) {
		$this->core->logger->debug('cms->models->Model_Widgets->getGenericFormById('.$strId.')');
		
		$objSelectForm = $this->getGenericFormsTable()->select();
		$objSelectForm->setIntegrityCheck(false);
		$objSelectForm->from($this->objGenericFormsTable, array('id', 'genericFormId', 'version'));
		if(is_numeric($strId)) {
			$objSelectForm->where('id=?', $strId);
		} else {
			$objSelectForm->where('genericFormId=?', $strId);
		}
		
		return $this->objGenericFormsTable->fetchRow($objSelectForm);
	}
	
	/**
	 * getWidgetByWidgetInstanceId
	 * @param string $strWidgetInstanceId
	 * @return Zend_Db_Table_Rowset_Abstract
	 * @author Daniel Rotter <daniel.rotter@massvieart.com>
	 * @version 1.0
	 */
	public function getWidgetByWidgetInstanceId($strWidgetInstanceId) {
		$this->core->logger->debug('cms->models->Model_Widgets->getWidgetByWidgetInstanceId('.$strWidgetInstanceId.')');
		
		$objSelect = $this->getWidgetsTable()->select();
		$objSelect->setIntegrityCheck(false);
		$objSelect->from($this->objWidgetsTable, array('name'));
		$objSelect->join('widgetInstances', 'widgetInstances.idWidgets = widgets.id');
		$objSelect->where('widgetInstances.widgetInstanceId = ?', $strWidgetInstanceId);
		
		return $this->objWidgetsTable->fetchRow($objSelect);
	}
	
	/**
	 * loadWidgets
	 * @return Zend_Db_Table_Rowset_Abstract
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function loadWidgets() {
		$this->core->logger->debug('cms->models->Model_Widgets->loadWidgets()');
		
		$objSelect = $this->getWidgetsTable()->select();
		$objSelect->setIntegrityCheck(false);
		
		$objSelect->from($this->objWidgetsTable, array('id', 'name'));
		$objSelect->order('name ASC');
		
		return $this->objWidgetsTable->fetchAll($objSelect);
	}
  
	/**
   * loadWidgetUrl
   * @param string $strElementId
   * @param integer $intVersion
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadWidgetUrl($strElementId, $intVersion){
    $this->core->logger->debug('cms->models->Model_Widgets->loadWidgetUrl('.$strElementId.', '.$intVersion.')');

    $objSelect = $this->getUrlTable()->select();
    $objSelect->setIntegrityCheck(false);

    $objSelect->from($this->objUrlTable, array('url'));
    $objSelect->join('languages', 'languages.id = urls.idLanguages', array('languageCode'));
    $objSelect->where('urls.relationId = ?', $strElementId)
              ->where('urls.version = ?', $intVersion)
              ->where('urls.idLanguages = ?', $this->intLanguageId);

    return $this->objUrlTable->fetchAll($objSelect);
  }
  
  /**
   * loadWidgetByInstanceId
   * @param integer $intWidgetInstanceId
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadWidgetByInstanceId($intWidgetInstanceId){
  	$this->core->logger->debug('cms->models->Model_Widgets->loadWidgetByInstanceId('.$intWidgetInstanceId.')');
  	
  	$objSelect = $this->getWidgetInstancesTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	
  	$objSelect->from('widgetInstances', array('widgetInstanceTitles.title', 'widgets.name'));
  	$objSelect->join('widgets', 'widgetInstances.idWidgets = widgets.id');
  	$objSelect->join('widgetInstanceTitles', 'widgetInstances.widgetInstanceId = widgetInstanceTitles.widgetInstanceId', array());
  	$objSelect->where('widgetInstances.widgetInstanceId = ?', $intWidgetInstanceId);

  	return $this->objWidgetInstancesTable->fetchRow($objSelect);
  }
  
  /**
   * loadSubWidgetByInstanceId
   * @param integer $intWidgetInstanceId
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadSubWidgetByInstanceId($intWidgetInstanceId){
  	$this->core->logger->debug('cms->models->Model_Widgets->loadSubWidgetByInstanceId('.$intWidgetInstanceId.')');
  	
  	$objSelect = $this->getModelSubwidgets()->getGenericTable('subwidgets')->select();
  	$objSelect->from('subwidgets', array());
  	$objSelect->setIntegrityCheck(false);
  	$objSelect->join('widgetInstances', 'subwidgets.widgetInstanceId = widgetInstances.widgetInstanceId', array('widgetInstanceTitles.title','widgets.name'));
  	$objSelect->join('widgetInstanceTitles', 'widgetInstances.widgetInstanceId = widgetInstanceTitles.widgetInstanceId', array());
  	$objSelect->join('widgets', 'widgetInstances.idWidgets = widgets.id', array());
  	$objSelect->join('widgetTable', 'widgetTable.id = subwidgets.idWidgetTable', array());
  	$objSelect->join('genericForms', 'genericForms.id = widgetTable.idGenericForms', array('genericFormId', 'version', 'idGenericFormTypes'));
  	$objSelect->where('subwidgets.subwidgetId = ?', $intWidgetInstanceId);
  	
  	return $this->getModelSubwidgets()->getGenericTable('subwidgets')->fetchRow($objSelect);
  }
  
	/**
   * loadWidgetByUrl
   * @param string $strElementId
   * @param integer $intVersion
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadWidgetByUrl($intRootLevelId = 'null', $strUrl){
    $this->core->logger->debug('cms->models->Model_Widgets->loadWidgetByUrl('.$intRootLevelId.', '.$strUrl.')');

    if($intRootLevelId != 'null'){
    	return $this->loadWidgetByUrlAndRootLevel($intRootLevelId, $strUrl);
	  }else{
	  	$objSelect = $this->getUrlTable()->select();
	    $objSelect->setIntegrityCheck(false);
	
	    $objSelect->from($this->objUrlTable, array('url'));
	    $objSelect->join('widgetInstances', 'widgetInstances.widgetInstanceId = urls.relationId AND widgetInstances.version = urls.version', array('widgetInstanceId'));
	   	$objSelect->join('widgets', 'widgetInstances.idWidgets = widgets.id');
	   	$objSelect->join('widgetInstanceTitles', 'widgetInstances.widgetInstanceId = widgetInstanceTitles.widgetInstanceId', array('title'));
	   	$objSelect->where('urls.url = ?', $strUrl)
	   					 	->where('urls.idLanguages = ?', 1);
	    
	    return $this->objUrlTable->fetchRow($objSelect);
    }
  }
  
	/**
   * loadWidgetByUrlAndRootLevel
   * @param integer $intRootLevelId
   * @param string $strUrl
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadWidgetByUrlAndRootLevel($intRootLevelId, $strUrl){
    $this->core->logger->debug('cms->models->Model_Widgets->loadWidgetByUrlAndRootLevel('.$intRootLevelId.', '.$strUrl.')');

    $sqlStmt = $this->core->dbh->query('SELECT urls.relationId, urls.version, urls.idLanguages FROM urls
                                          INNER JOIN widgetInstances ON
                                            widgetInstances.widgetInstanceId = urls.relationId AND
                                            widgetInstances.version = urls.version AND
                                            widgetInstances.idParentTypes = ?
                                          INNER JOIN folders ON
                                            folders.id = widgetInstances.idParent
                                          WHERE urls.url = ? AND
                                            urls.idLanguages = ? AND
                                            folders.idRootLevels = ?
                                        UNION
                                        SELECT urls.relationId, urls.version, urls.idLanguages FROM urls
                                          INNER JOIN widgetInstances ON
                                            widgetInstances.widgetInstanceId = urls.relationId AND
                                            widgetInstances.version = urls.version AND
                                            widgetInstances.idParentTypes = ?
                                          INNER JOIN rootLevels ON
                                            rootLevels.id = widgetInstances.idParent
                                          WHERE urls.url = ? AND
                                            urls.idLanguages = ? AND
                                            rootLevels.id = ?', array($this->core->sysConfig->parent_types->folder,
                                                                      $strUrl,
                                                                      $this->intLanguageId,
                                                                      $intRootLevelId,
                                                                      $this->core->sysConfig->parent_types->rootlevel,
                                                                      $strUrl,
                                                                      $this->intLanguageId,
                                                                      $intRootLevelId));
    return $sqlStmt->fetchAll(Zend_Db::FETCH_OBJ);
  }
  
  /**
   * prepareSubwidgetUrl
   * @param string strPostUrl
   * @param string $strWidgetInstanceId
   * @param integer $intVersion
   * @return string $subwidgetUrl
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function prepareSubwidgetUrl($strPostUrl, $strWidgetInstanceId, $intVersion=1){
  	$this->core->logger->debug('cms->models->Model_Widgets->prepareSubwidgetUrl()');
  	
  	//todo: get version and language id dynamic
  	$intVersion=1;
  	$this->setLanguageId(1);
  	
  	$objWidgetUrl = $this->loadWidgetUrl($strWidgetInstanceId, $intVersion);
  	$objUrl = $objWidgetUrl->current();
  	$strUrl = $objUrl->url.'/'.$this->makeUrlConform($strPostUrl);
  	
  	return $strUrl;
  }
  
	/**
   * insertWidgetUrl
   * @param string $strUrl
   * @param string $strWidgetInstanceId
   * @param integer $intVersion
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function insertWidgetUrl($strUrl, $strWidgetInstanceId, $intVersion){
    $this->core->logger->debug('cms->models->Model_Widgets->insertWidgetUrl('.$strUrl.', '.$strWidgetInstanceId.', '.$intVersion.')');

    $intUserId = Zend_Auth::getInstance()->getIdentity()->id;

    $arrData = array('relationId'       => $strWidgetInstanceId,
                     'version'     => $intVersion,
                     'idLanguages' => $this->intLanguageId,
                     'url'         => $strUrl,
                     'idUsers'     => $intUserId,
                     'creator'     => $intUserId,
                     'created'     => date('Y-m-d H:i:s'),
                     'idUrlTypes'  => self::URL_TYPE_WIDGET);

    return $objSelect = $this->getUrlTable()->insert($arrData);
  }
  
	/**
   * loadParentFolders
   * @param string $strInstanceId
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadParentFolders($strInstanceId){
    $this->core->logger->debug('cms->models->Model_Widgets->loadParentFolders('.$strInstanceId.')');

    $sqlStmt = $this->core->dbh->query('SELECT folders.id, folders.isUrlFolder, folderTitles.title
                                          FROM folders
                                            INNER JOIN folderTitles ON
                                              folderTitles.folderId = folders.folderId AND
                                              folderTitles.version = folders.version AND
                                              folderTitles.idLanguages = ?
                                          ,folders AS parent
                                            INNER JOIN widgetInstances ON
                                              widgetInstances.widgetInstanceId = ? AND
                                              parent.id = widgetInstances.idParent AND
                                              widgetInstances.idParentTypes = ?
                                           WHERE folders.lft <= parent.lft AND
                                                 folders.rgt >= parent.rgt AND
                                                 folders.idRootLevels = parent.idRootLevels
                                             ORDER BY folders.rgt', array($this->intLanguageId, $strInstanceId, $this->core->sysConfig->parent_types->folder));

    return $sqlStmt->fetchAll(Zend_Db::FETCH_OBJ);
  }
	
  /**
   * loadWidgetInstance
   * @param number $intWidgetId
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function loadWidgetInstance($intWidgetInstanceId) {
    $this->core->logger->debug('cms->models->Model_Widgets->loadWidgetInstance('.$intWidgetInstanceId.')');
    
    $objSelect = $this->getWidgetInstancesTable()->select();
    $objSelect->setIntegrityCheck(false);
    
    $objSelect->from($this->objWidgetInstancesTable, array('id', 'idGenericForms', 'sortPosition', 'idParent', 'idParentTypes', 'created', 'changed', 'published', 'idStatus', 'sortTimestamp', 'creator', 'publisher', 'idWidgets', 'widgetInstanceId', 'version', 'showInNavigation',
      '(SELECT CONCAT(users.fname, \' \', users.sname) AS changeUser FROM users WHERE users.id = widgetInstances.idUsers) AS changeUser'));
    if(is_numeric($intWidgetInstanceId)) $objSelect->where('id = ?', $intWidgetInstanceId);
    else $objSelect->where('widgetInstanceId = ?', $intWidgetInstanceId);
    
    return $this->objWidgetInstancesTable->fetchAll($objSelect);
  }
  
  /**
   * deleteWidgetInstance
   * @param number $intElementId
   * @return number
   */
  public function deleteWidgetInstance($intElementId) {
  	$this->core->logger->debug('cms->models->Model_Widgets->deleteWidgetInstance('.$intElementId.')');
  	
  	$this->getWidgetInstancesTable();
  	
  	$strWhere = $this->objWidgetInstancesTable->getAdapter()->quoteInto('id = ?', $intElementId);
  	
  	return $this->objWidgetInstancesTable->delete($strWhere);
  }
	
	/**
   * makeUrlConform()
   * @param string $strUrlPart
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  private function makeUrlConform($strUrlPart){

    $this->getUrlReplacers();
    $strUrlPart = strtolower($strUrlPart);

    if(count($this->objUrlReplacers) > 0){
      foreach($this->objUrlReplacers as $objUrlReplacer){
        $strUrlPart = str_replace(utf8_encode($objUrlReplacer->from), $objUrlReplacer->to, $strUrlPart);
      }
    }

    $strUrlPart = strtolower($strUrlPart);
    $strUrlPart = urlencode(preg_replace('/([^A-za-z0-9\s-_])/', '_', $strUrlPart));
    $strUrlPart = str_replace('+', '-', $strUrlPart);

    return $strUrlPart;
  }
  
 	/**
   * getUrlReplacers()
   * @param string $strUrlPart
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  private function getUrlReplacers(){
    if($this->objUrlReplacers === null) {
      $this->objUrlReplacers = $this->loadUrlReplacers();
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
	
	/**
   * getUrlTable
   * @return Zend_Db_Table_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getUrlTable(){

    if($this->objUrlTable === null) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Urls.php';
      $this->objUrlTable = new Model_Table_Urls();
    }

    return $this->objUrlTable;
  }
  
	/**
	 * getWidgetsTable
	 * @return Zend_Db_Table_Abstract
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function getWidgetsTable() {
		if($this->objWidgetsTable === NULL) {
			require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/tables/Widgets.php';
      $this->objWidgetsTable = new Model_Table_Widgets();
		}
		
		return $this->objWidgetsTable;
	}
	
  /**
   * getWidgetInstancesTable
   * @return Zend_Db_Table_Abstract
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function getWidgetInstancesTable() {
    if($this->objWidgetsTable === NULL) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/tables/WidgetInstances.php';
      $this->objWidgetInstancesTable = new Model_Table_WidgetInstances();
    }
    
    return $this->objWidgetInstancesTable;
  }
  
	/**
   * loadUrlReplacers
   * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function loadUrlReplacers(){
    $this->core->logger->debug('cms->models->Model_Widgets->loadUrlReplacers()');

    $objSelect = $this->getUrlReplacersTable()->select();

    $objSelect->from($this->objUrlReplacersTable, array('from', 'to'));
    $objSelect->where('urlReplacers.idLanguages = ?', $this->intLanguageId);

    return $this->objUrlReplacersTable->fetchAll($objSelect);
  }
  
  /**
   * getUrlReplacersTable
   * @return Zend_Db_Table_Abstract
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getUrlReplacersTable(){

    if($this->objUrlReplacersTable === null) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/tables/UrlReplacers.php';
      $this->objUrlReplacersTable = new Model_Table_UrlReplacers();
    }

    return $this->objUrlReplacersTable;
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