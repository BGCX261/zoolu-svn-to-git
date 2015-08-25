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
 * @package    application.widgets.blog.models
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Model_BlogEntry
 *
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-11: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.1
 */

class Model_BlogEntry {	
	/**
	 * @var Model_Table_BlogEntries
	 */
	protected $objBlogEntryTable;
	
	/**
	 * @var Model_Subwidgets
	 */
	protected $objModelSubwidgets;
	
	/**
	 * @var Model_Table_Subwidgets
	 */
	protected $objSubwidgetsTable;
	
	/**
	 * @var Core
	 */
	protected $core;
	protected $intBlogEntryCount;
	
	public function __construct() {
		$this->core = Zend_Registry::get('Core');
	}
	
	/**
	 * getBlogEntryCount
	 * @return integer $intBlogEntryCount
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0getGenericTable
	 */
	public function getBlogEntryCount($strWidgetInstanceId, $strTag=null){
		$this->core->logger->debug('widgets->blog->Model_BlogEntry->getBlogEntryCount('.$strWidgetInstanceId.', '.$strTag.')');

		$objSelect = $this->getSubwidgetsTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	$objSelect->from($this->objSubwidgetsTable, array('id'));
		
  	if($strTag){
			$objSelect->join('tagSubwidgets', 'tagSubwidgets.subwidgetId = subwidgets.subwidgetId', array());
			$objSelect->join('tags', 'tags.id = tagSubwidgets.idTags', array());
			$objSelect->where('tags.title = ?', $strTag);
  	}
  	
  	$objSelect->where('subwidgets.widgetInstanceId = ?', $strWidgetInstanceId);
		$data = $this->getModelSubwidgets()->getGenericTable('subwidgets')->fetchAll($objSelect);
		$this->intBlogEntryCount = $data->count();
		
		return $this->intBlogEntryCount;
	}
	
	/**
	 * getBlogEntries
	 * @return Zend_Db_Table_Rowset_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
	 */
	public function getBlogEntries($strWidgetInstanceId, $blnReturnTest=true, $intPerPage=10, $intOffset=0, $strTag=null, $objWidget=null) {
		$this->core->logger->debug('widgets->blog->Model_BlogEntry->getBlogEntries('.$strWidgetInstanceId.', '.$intPerPage.', '.$intOffset.')');

		$objSelectForm = $this->getBlogEntryTable()->select();
		$objSelectForm->setIntegrityCheck(false);
		$objSelectForm->from($this->objBlogEntryTable, array('id', 'title', 'users.username', 'subwidgets.widgetInstanceId', 'subwidgets.created', 'created_ts' => 'UNIX_TIMESTAMP(subwidgets.created)', 'text', 'urls.url', 'subwidgets.idStatus'));
		$objSelectForm->join('urls','urls.relationId = widget_BlogEntries.subwidgetId', array('url'));
		$objSelectForm->join('languages', 'urls.idLanguages = languages.id', array('languageCode'));
		$objSelectForm->join('subwidgets', 'subwidgets.subwidgetId = widget_BlogEntries.subwidgetId');
		$objSelectForm->join('users','subwidgets.idUsers = users.id', array('idLanguages', 'username', 'password', 'fname', 'sname'));
		
		if($objWidget != null) {
			$objSelectForm->join('rootLevelUrls', 'rootLevelUrls.idRootLevels = '.$objWidget->getRootLevelId(), array('rooturl' => 'url'));
			
		}
		
		if($strTag){
			$objSelectForm->join('tagSubwidgets', 'tagSubwidgets.subwidgetId = subwidgets.subwidgetId', array());
			$objSelectForm->join('tags', 'tags.id = tagSubwidgets.idTags', array());
			$objSelectForm->where('tags.title = ?', $strTag);
  	}
  	
  	if(!$blnReturnTest){
  		$objSelectForm->where('subwidgets.idStatus = ?', $this->core->sysConfig->status->live);
  	}
  	
		$objSelectForm->where('subwidgets.widgetInstanceId = ?', $strWidgetInstanceId);
		$objSelectForm->order('subwidgets.created DESC');
		$objSelectForm->limit($intPerPage, $intOffset);
		$data = $this->objBlogEntryTable->fetchAll($objSelectForm);
		$this->intBlogEntryCount = $data->count();

		return $data;
	}
	
 /**
   * addBlogEntry
   * @return number The new Id
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function addBlogEntry($arrValues) {
  	$this->core->logger->debug('widgets->blog->Model_BlogEntry->addBlogEntry(array)');
  	
    return $this->getBlogEntryTable()->insert($arrValues);
  }
  
  /**
   * getBlogEntry
   * @param number $intBlogEntryId
   * @return array
   * @author Daniel Rotter
   * @version 1.0
   */
  public function getBlogEntry($intBlogEntryId) {
  	$this->core->logger->debug('widgets->blog->Model_BlogEntry->getBlogEntry('.$intBlogEntryId.')');
  	
  	$objSelect = $this->getBlogEntryTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	$objSelect->from($this->objBlogEntryTable, array('id', 'title', 'text', 'subwidgetId', 'subwidgets.idStatus'));
  	$objSelect->join('subwidgets', 'subwidgets.subwidgetId = widget_BlogEntries.subwidgetId', array('widgetInstanceId'));
  	$objSelect->where('widget_BlogEntries.id = ?', $intBlogEntryId);
  	$objSelect->limit(1);
  	
  	return $this->objBlogEntryTable->fetchRow($objSelect)->toArray();
  }
  
  /**
   * getBlogEntryBySubwidgetId
   * @param $strSubwidgetId
   * @return array
   * @author Florian Mathis
   * @version 1.0
   */
  public function getBlogEntryBySubwidgetId($strSubwidgetId){
  	$this->core->logger->debug('widgets->blog->Model_BlogEntry->getBlogEntryBySubwidgetId('.$strSubwidgetId.')');
  	
  	$objSelect = $this->getBlogEntryTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	$objSelect->from($this->objBlogEntryTable, array('blogEntryId' => 'id', 'title', 'users.username', 'subwidgets.created', 'text'));
  	$objSelect->join('subwidgets', 'subwidgets.subwidgetId = widget_BlogEntries.subwidgetId');
  	$objSelect->joinInner('users','subwidgets.idUsers = users.id');
  	$objSelect->where('widget_BlogEntries.subwidgetId = ?', $strSubwidgetId);

  	return $this->objBlogEntryTable->fetchAll($objSelect);
  }
  
  /**
   * editBlogEntry
   * @param array $arrValues
   * @param number $intBlogEntry
   */
  public function editBlogEntry($arrValues, $intBlogEntry) {
  	$this->core->logger->debug('widgets->blog->Model_Blog->editBlogEntry(array, '.$intBlogEntry.')');
  	
  	$strWhere = $this->getBlogEntryTable()->getAdapter()->quoteInto('id = ?', $intBlogEntry);
  	$this->getBlogEntryTable()->update($arrValues, $strWhere);
  }
  
  /**
   * deleteBlogEntry
   * @param number $intBlogEntry
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function deleteBlogEntry($intBlogEntry) {
  	$this->core->logger->debug('widgets->blog->Model_Blog->deleteBlogEntry('.$intBlogEntry.')');
  	
  	//Delete Subwidget-Entry
  	$objSelect = $this->getBlogEntryTable()->select();
  	$objSelect->setIntegrityCheck(false);
  	$objSelect->from($this->objBlogEntryTable, array('subwidgets.Id'));
  	$objSelect->join('subwidgets', 'subwidgets.subwidgetId = widget_BlogEntries.subwidgetId');
  	$objSelect->where('widget_BlogEntries.id = ?', $intBlogEntry);
  	$objSelect->limit(1);
  	$arrData = $this->getBlogEntryTable()->fetchRow($objSelect);
  	
  	$this->getModelSubwidgets()->delete($arrData->Id);
  	
  	$strWhere = $this->getBlogEntryTable()->getAdapter()->quoteInto('id = ?', $intBlogEntry);
  	return $this->getBlogEntryTable()->delete($strWhere);
  }
	
	/**
   * getBlogEntryTable
   * @return Zend_Db_Table_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getBlogEntryTable(){
    if($this->objBlogEntryTable === null) {
      require_once GLOBAL_ROOT_PATH.'application/widgets/blog/models/tables/BlogEntries.php';
      $this->objBlogEntryTable = new Model_Table_BlogEntries();
    }

    return $this->objBlogEntryTable;
  }
  
	/**
   * getSubwidgetsTable
   * @return Zend_Db_Table_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getSubwidgetsTable(){
    if($this->objSubwidgetsTable === null) {
      require_once GLOBAL_ROOT_PATH.'application/widgets/blog/models/tables/Subwidgets.php';
      $this->objSubwidgetsTable = new Model_Table_Subwidgets();
    }

    return $this->objSubwidgetsTable;
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