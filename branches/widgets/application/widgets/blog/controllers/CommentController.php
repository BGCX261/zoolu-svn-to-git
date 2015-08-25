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
 * Blog_CommentController
 * Version history (please keep backward compatible):
 * 1.0, 2009-11-18: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.at>
 * @version 1.0
 */
class Blog_CommentController extends Zend_Controller_Action {
	
	 /**
   * @var Model_BlogEntryComment
   */
  private $objModelBlogEntryComment;
  
  /**
   * @var Core
   */
  protected $core;
  
  /**
   * @var Zend_Controller_Request_Abstract
   */
  private $objRequest;
  
  /**
   * Init
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function init(){
  	$this->core = Zend_Registry::get('Core');
  	$this->objRequest = $this->getRequest();
  }
  
	/**
	 * addAction
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function addAction() {
		$this->core->logger->debug('widgets->blog->CommentController->addAction');
		try {
			$this->_helper->viewRenderer->setNoRender();
			if($this->objRequest->getPost()) {
				$arrFormData = $this->objRequest->getPost();
				//TODO: Replace the keys from the $arrFormData Array with the correct ones from the forms
				$arrData = array('idWidget_BlogEntries' => $arrFormData['idBlogentry'],
				                  'title' => $arrFormData['blogWidgetCommentName'],
				                  'text' => $arrFormData['blogWidgetCommentText'],
				                  'mail' => $arrFormData['blogWidgetCommentMail']
				);
				$this->getModelBlogEntryComment()->addBlogEntryComment($arrData);
			}
		}catch(Exception $exc) {
			$this->core->logger->err($exc);
			exit();
		}
	}
	
	/**
	 * load all comments
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function loadcommentsAction(){
		$this->core->logger->debug('widgets->blog->CommentController->loadCommentsAction');
		try{
			$this->_helper->viewRenderer->setNoRender();
			$arrFormData = $this->objRequest->getParams();
			return $this->getModelBlogEntryComment()->getAllComments($arrFormData['idBlogentry']);
		}catch(Exception $exc) {
			$this->core->logger->err($exc);
			exit();
		}
	}
	
	/**
	 * deleteAction
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function deleteAction() {
		$this->core->logger->debug('widgets->blog->CommentController->deleteAction');
		$this->_helper->viewRenderer->setNoRender();
		try {
			if($this->objRequest->getPost() && $this->objRequest->isXmlHttpRequest()) {
				$this->getModelBlogEntryComment()->deleteBlogEntryComment($this->objRequest->getParam('id'));
			}
		} catch(Exception $exc) {
			$this-> core->logger->err($exc);
			exit();
		}
	}
	
  /**
   * getModelBlogEntryComment
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   * @return Model_BlogEntryComments
   */
  protected function getModelBlogEntryComment() {
    if(null === $this->objModelBlogEntryComment) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.'blog/models/BlogEntryComment.php';
      $this->objModelBlogEntryComment = new Model_BlogEntryComment();
    }
    return $this->objModelBlogEntryComment;
  }
}
?>