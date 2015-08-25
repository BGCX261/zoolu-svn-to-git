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
 * @package    application.widgets.Blog.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * NavigationController
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-11 Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 */
class Blog_NavigationController extends AuthControllerAction {
	/**
	 * @var objModelBlog
	 */
	protected $objModelBlogEntry;
	
	/**
   * @var Model_Widgets
   */
  protected $objModelWidgets;
	
	/**
	 * navigationAction
	 * @author Daniel Rotter <daniel.rotter@gmail.com>
	 * @version 1.0
	 */
	public function widgetnavigationAction() {
		$this->core->logger->debug('widgets->blog->controllers->NavigationController->widgetnavigationAction()');

    $this->view->assign('currLevel', $this->getRequest()->getParam('currLevel'));
    $this->view->assign('childElements', $this->getModelBlogEntry()->getBlogEntries($this->getRequest()->getParam('instanceId')));
    $this->view->assign('widgetName', 'blog');
    $this->view->assign('widgetInstanceId', $this->getRequest()->getParam('instanceId'));
    $this->view->assign('idWidgetInstance', $this->getRequest()->getParam('idWidgetInstances'));
	}
	
	/**
   * getModelBlog
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getModelBlogEntry(){
    if (null === $this->objModelBlogEntry) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it 
       * from its modules path location.
       */ 
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_widgets.'blog/models/BlogEntry.php';
      $this->objModelBlogEntry = new Model_BlogEntry();
    }
    
    return $this->objModelBlogEntry;
  }
  
  /**
   * getModelWidgets
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  protected function getModelWidgets(){
    if (null === $this->objModelWidgets) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it 
       * from its modules path location.
       */ 
      require_once GLOBAL_ROOT_PATH.'/application/zoolu/modules/cms/models/Widgets.php';
      $this->objModelWidgets = new Model_Widgets();
    }
    
    return $this->objModelWidgets;
  }
}
?>