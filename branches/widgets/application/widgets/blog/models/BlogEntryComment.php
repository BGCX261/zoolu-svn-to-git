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
 * @package    application.zoolu.modules.cms.models.tables
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Model_BlogEntryComments
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-11: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 */
class Model_BlogEntryComment {
	/**
	 * @var Model_Table_BlogEntryComments
	 */
	protected $objBlogEntryCommentTable;
	
	/**
	 * @var Core
	 */
	protected $core;
	
	public function __construct() {
		$this->core = Zend_Registry::get('Core');
	}
	
	/**
	 * addBlogEntryComment
	 * @param array $arrValues
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function addBlogEntryComment($arrValues) {
		$this->core->logger->debug('widgets->blog->Model_Blog->addBlogEntryComment('.$arrValues.')');
		
		return $this->getBlogEntryCommentTable()->insert($arrValues);
	}
	
  /**
   * editBlogEntryComment
   * @param array $arrValues
   * @param number $intBlogEntryComment
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function editBlogEntryComment($arrValues, $intBlogEntryComment) {
    $this->core->logger->debug('widgets->blog->Model_Blog->editBlogEntryComment('.$arrValues.', '.$intBlogEntryComment.')');
    
    $strWhere = $this->getBlogEntryCommentTable()->getAdapter()->quoteInto('id = ?', $intBlogEntryComment);
    $this->getBlogEntryCommentTable()->update($arrValues, $strWhere);
  }
  
  /**
   * deleteBlogEntryComment
   * @param number $intBlogEntryComment
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function deleteBlogEntryComment($intBlogEntryComment) {
    $this->core->logger->debug('widgets->blog->Model_Blog->deleteBlogEntryComment('.$intBlogEntryComment.')');
    
    $strWhere = $this->getBlogEntryCommentTable()->getAdapter()->quoteInto('id = ?', $intBlogEntryComment);
    return $this->getBlogEntryCommentTable()->delete($strWhere);
  }
  
  /**
   * getAllComments
   * @param integer $intBlogEntryComment
   * @return Zend_Db_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getAllComments($intBlogEntryComment){
  	$this->core->logger->debug('widgets->blog->Model_Blog->getAllComments('.$intBlogEntryComment.')');
  	
  	$strWhere = $this->getBlogEntryCommentTable()->getAdapter()->quoteInto('idWidget_BlogEntries = ?', $intBlogEntryComment);
  	return $this->getBlogEntryCommentTable()->fetchAll($strWhere);
  }
	
	/**
	 * getBlogEntryCommentTable
	 * @return Model_Table_BlogEntryComments
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function getBlogEntryCommentTable() {
		if($this->objBlogEntryCommentTable === null) {
      require_once GLOBAL_ROOT_PATH.'application/widgets/blog/models/tables/BlogEntryComments.php';
      $this->objBlogEntryCommentTable = new Model_Table_BlogEntryComments();
    }

    return $this->objBlogEntryCommentTable;
	}
}
?>