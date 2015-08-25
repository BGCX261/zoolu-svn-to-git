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
 * Model_WidgetInstanceProperties
 *
 *
 * Version history (please keep backward compatible):
 * 1.0, 2010-01-22: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.1
 */

class Model_WidgetInstanceProperties {	
	/**
	 * @var Core
	 */
	protected $core;
	
	/**
	 * @var Model_Table_BlogEntriesTag
	 */
	protected $objWidgetInstancePropertiesTable;
	
	public function __construct() {
		$this->core = Zend_Registry::get('Core');
	}
	
	/**
	 * getPropertyValue
	 * @param string $strPropertyName
	 * @param integer $intWidgetInstanceId
	 * @return Zend_Db_Table_Rowset_Abstract
	 * @author Florian Mathis <flo@massiveart.com>
	 * @version 1.0
	 */
	public function getPropertyValue($strPropertyName = null, $intWidgetInstanceId=0) {
		$this->core->logger->debug('widgets->blog->Model_BlogEntry->getPropertyValue('.$strPropertyName.', '.$intWidgetInstanceId.')');
		
		$objSelect = $this->getWidgetInstancePropertiesTable()->select();
		$objSelect->from($this->objWidgetInstancePropertiesTable, array('value'));
		$objSelect->join('widgetInstances', 'widgetInstances.id = widgetInstanceProperties.idWidgetInstances', array());
		$objSelect->where('widgetInstances.widgetInstanceId = ?', $intWidgetInstanceId)
							->where('property = ?', $strPropertyName);
		$objData = $this->objWidgetInstancePropertiesTable->fetchRow($objSelect);	

		return $objData['value'];
	}
	
	/**
   * getWidgetInstancePropertiesTable
   * @return Zend_Db_Table_Abstract
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getWidgetInstancePropertiesTable(){
    if($this->objWidgetInstancePropertiesTable === null) {
      require_once GLOBAL_ROOT_PATH.'application/widgets/blog/models/tables/WidgetInstanceProperties.php';
      $this->objWidgetInstancePropertiesTable = new Model_Table_WidgetInstanceProperties();
    }

    return $this->objWidgetInstancePropertiesTable;
  }
}

?>