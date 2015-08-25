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
 * @package    application.widgets.blog.views.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * NavigationHelper
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-11: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 */
class NavigationHelper {
	/**
	 * 
	 * @var Core
	 */
	private $core;
	
	/**
	 * Constructor
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function __construct() {
		$this->core = Zend_Registry::get('Core');
	}
	
	/**
	 * getNavigationElements
	 * @param $objRowset
	 * @param $currLevel
	 * @return string
	 * @author Florian Mathis <flo@massiveart.com
	 * @version 1.0
	 */
	public function getNavigationElements($objRowset, $currLevel, $widgetName) {
		$this->core->logger->debug('widgets->blog->views->helpers->NavigationHelper->getNavigationElements(objRowset, '.$currLevel.')');
		$output = '';
		$counter=1;
		
		if(count($objRowset) > 0){
      foreach ($objRowset as $objRow){  
				$output .= '<div id="subwidget'.$objRow->id.'" class="blog">
            <div class="icon img_blog_'.(($objRow->idStatus == $this->core->sysConfig->status->live) ? 'on' : 'off').'"></div>
            <div class="navsortpos"><input class="iptsortpos" type="text" name="pos_blog_'.$objRow->id.'" id="pos_blog_'.$objRow->id.'" value="'.$counter.'" onfocus="return false;" /></div>
            <div class="title italic" onclick="myNavigation.editSubWidgetForm('.$objRow->id.', \''.$widgetName.'\', \''.$objRow->widgetInstanceId.'\'); return false;">'.htmlentities($objRow->title, ENT_COMPAT, $this->core->sysConfig->encoding->default).'</div>
          </div>';
				$counter++;
			}
		}
		
		return $output;
	}
}
?>