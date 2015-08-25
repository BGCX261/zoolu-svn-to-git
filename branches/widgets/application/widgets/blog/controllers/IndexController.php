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
 * @package    application.widgets.blog.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Blog_IndexController
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-11: Florian Mathis
 *  
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */

class Blog_IndexController extends WidgetControllerAction  {
  /*
   * @var object $objBlogEntries
   */
	protected $objBlogEntries;
	
	/**
	 * @var object $objBlogEntriesTags
	 */
	protected $objBlogEntriesTags;
	
	/**
	 * @var object $objWidgetInstanceProperties
	 */
	protected $objWidgetInstanceProperties;
	
	/**
	 * @var object $objBlogEntryComments
	 */
	protected $objBlogEntryComments;
  
	/**
	 * Initialize WidgetController action and add 
	 * default css and js widget theme files
	 * 
	 * @see library/massiveart/controllers/WidgetControllerAction#init()
	 * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
	 */
	public function init() {
		parent::init();
		$this->addThemeCss('view');
		$this->addThemeLink('alternate', 'application/rss+xml', $this->objWidget->getRootLevelTitle() . ' - ' . $this->objWidget->getWidgetTitle() . ' RSS', '?do=rss');
		$this->view->setHelperPath(dirname(dirname(__FILE__)) . '/views/helpers/', 'Blog_View_Helper');
    $this->view->addHelperPath(dirname(dirname(__FILE__)) . '/views/helpers/', 'Blog_View_Helper');  
	}
	
  /**
   * IndexAction
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
	public function indexAction() {
		$objEntries = $this->getBlogEntriesTable();
		$objWidgetProperties = $this->getWidgetInstancePropertiesTable();
		$this->view->total = $objEntries->getBlogEntryCount($this->objWidget->getWidgetInstanceId(), $this->_getParam('t'));

		if($this->view->total > 0) {
	    $this->view->perPage = $objWidgetProperties->getPropertyValue('pagination', $this->objWidget->getWidgetInstanceId());
	    $offset = ($this->_getParam('page') > 0) ? $this->view->perPage * ($this->_getParam('page') - 1) : 0;
	
	    $objEntry = $objEntries->getBlogEntries( $this->objWidget->getWidgetInstanceId(),
	                                             (!isset($_SESSION['sesTestMode']) || (isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == false))?false:true,
	                                             $this->view->perPage,
	                                             $offset, 
	                                             $this->_getParam('t'));
			$this->view->assign('objEntries',$objEntry);
			
			$objBlogWidgetTags = $this->getBlogEntriesTagsTable();
			$objBlogWidgetTags->setInstanceId($this->objWidget->getWidgetInstanceId());
			$this->view->assign('objWidgetTags', $objBlogWidgetTags);
		}
	}
	
  /**
   * viewAction
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function viewAction() {  	
  	$this->addThemeJs('comment');
  	
  	$objBlogEntries = $this->getBlogEntriesTable();
  	$objEntry = $objBlogEntries->getBlogEntryBySubwidgetId($this->objWidget->getWidgetInstanceId());
  	$this->view->assign('objEntry',$objEntry[0]);
  	
  	$arr = $this->getBlogEntriesTagsTable()->getTagsBySubwidgetId($this->objWidget->getWidgetInstanceId());
  	$this->view->assign('arrTags', $arr);
  	
  	$comments = $this->getBlogEntryComments()->getAllComments($objEntry[0]['blogEntryId']);
  	$this->view->assign('comments', $comments);
  }
  
  public function generatecaptchaAction(){
		// not implemented
	}
	  
  
  /**
   * rssAction
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function rssAction(){
  	header('Content-type: application/rss+xml');
  	$this->blnRenderMaster = false;
		
  	require_once(dirname(__FILE__).'/../helpers/FeedBuilder.php');
  	
  	$objFeed = Zend_Feed::importBuilder(new Blog_FeedBuilder(Zend_Registry::get('Widget')), 'rss');
  	$this->view->assign('text', $objFeed->saveXml());
  }
  
	/**
   * getBlogEntriesTable
   * @return Model_BlogEntry
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getBlogEntriesTable(){
    if (null === $this->objBlogEntries) {
      /**
       * autoload only handles "library" components.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.'blog/models/BlogEntry.php';
      $this->objBlogEntries = new Model_BlogEntry();
    }
    return $this->objBlogEntries;
  }
  
	/**
   * getBlogEntryComments
   * @return Model_BlogEntryComment
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getBlogEntryComments(){
    if (null === $this->objBlogEntryComments) {
      /**
       * autoload only handles "library" components.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.'blog/models/BlogEntryComments.php';
      $this->objBlogEntryComments = new Model_BlogEntryComments();
    }
    return $this->objBlogEntryComments;
  }

	/**
   * getBlogEntriesTagsTable
   * @return Model_BlogTags
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getBlogEntriesTagsTable(){
    if (null === $this->objBlogEntriesTags) {
      /**
       * autoload only handles "library" components.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.'blog/models/BlogTags.php';
      $this->objBlogEntriesTags = new Model_BlogTags();
    }
    return $this->objBlogEntriesTags;
  }
  
	/**
   * getWidgetInstancePropertiesTable
   * @return Model_WidgetInstanceProperties
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getWidgetInstancePropertiesTable(){
    if (null === $this->objWidgetInstanceProperties) {
      /**
       * autoload only handles "library" components.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.'blog/models/WidgetInstanceProperties.php';
      $this->objWidgetInstanceProperties = new Model_WidgetInstanceProperties();
    }
    return $this->objWidgetInstanceProperties;
  }
}

?>