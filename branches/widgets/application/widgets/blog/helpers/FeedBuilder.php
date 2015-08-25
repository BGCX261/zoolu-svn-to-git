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
 * @package    application.widgets.blog.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Blog_FeedBuilder
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-12-03: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 */
class Blog_FeedBuilder implements Zend_Feed_Builder_Interface {
	/**
	 * The widgetInstanceId from the Blog
	 * @var string
	 */
	private $strWidgetInstanceId;
	
	/**
	 * @var Widget
	 */
	private $objWidget;
	
	/**
	 * @var Model_Widgets
	 */
	private $objModelWidgets;
	
	/**
	 * @var Core
	 */
	protected $core;
	
	/**
	 * @var number
	 */
	protected $intLanguageId;
	
	/**
	 * @var Model_BlogEntry
	 */
	protected $objBlogEntries;
	
	protected $intCount = 10;
	
	/**
	 * Constructor
	 * @param string $strWidgetInstanceId
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function __construct($objWidget){
		$this->core = Zend_Registry::get('Core');
		$this->objWidget = $objWidget;
		$this->intLanguageId = $this->core->sysConfig->languages->default->id;
	}
	
	/**
	 * getHeader
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function getHeader(){
		$objFeedHeader = new Zend_Feed_Builder_Header($this->objWidget->getRootLevelTitle().' - '.$this->objWidget->getWidgetTitle(), $this->objWidget->getNavigationUrl()); //FIXME URL
		return $objFeedHeader;
	}
	
	/**
	 * getEntries
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function getEntries(){
		$objBlogEntries = $this->getModelBlogEntries()->getBlogEntries($this->objWidget->getWidgetInstanceId(), false,  $this->intCount, 0, null, $this->objWidget);
		
		$arrEntry = array();
		
		foreach($objBlogEntries as $objBlogEntry){
			$feedUrl = $objBlogEntry->rooturl.'/'.$this->objWidget->getLanguageCode().'/'.$objBlogEntry->url;
			$objEntry = new Zend_Feed_Builder_Entry($objBlogEntry->title, 'http://'.$feedUrl, $objBlogEntry->text);
			$objEntry->setLastUpdate($objBlogEntry->created_ts);

			$arrEntry[] = $objEntry;
		}
		
		return $arrEntry; 
	}
	
 /**
   * getBlogEntries
   * @return Model_BlogEntry
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getModelBlogEntries(){
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
}
?>