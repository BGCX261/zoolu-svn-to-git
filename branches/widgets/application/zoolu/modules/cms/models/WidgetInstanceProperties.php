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
 * Model_WidgetInstanceProperties
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-06: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 */
class Model_WidgetInstanceProperties {
	/**
	 * @var Core
	 */
	private $core;

	/**
	 * @var Model_Table_WidgetInstanceProperties
	 */
	protected $objWidgetInstancePropertiesTable;
	
	public function __construct() {
		$this->core = Zend_Registry::get('Core');
	}
	
	/**
	 * updateProperty
	 * @param string $strProperty
	 * @param string $strValue
	 * @param integer $intWidgetInstanceId
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function updateProperty($strProperty, $strValue, $intWidgetInstanceId) {
		$this->core->logger->debug('cms->models->Model_WidgetInstanceProperties->updateProperty('.$strProperty.', '.$strValue.', '.$intWidgetInstanceId.')');
		
		try {
			$strWhere = $this->getWidgetInstancePropertiesTable()->getAdapter()->quoteInto('idWidgetInstances = ?', $intWidgetInstanceId);
			$strWhere .= ' AND '.$this->getWidgetInstancePropertiesTable()->getAdapter()->quoteInto('property = ?', $strProperty);
			
			$this->getWidgetInstancePropertiesTable()->delete($strWhere);
			
			$this->getWidgetInstancePropertiesTable()->insert(array('property' => $strProperty, 'value' => $strValue, 'idWidgetInstances' => $intWidgetInstanceId));
		}catch(Exception $exc){
			$this->core->logger->err($exc);
			exit();
		}
	}
	
	/**
	 * getProperties
	 * @param number $intWidgetInstanceId
	 * @return unknown_type
	 */
	public function getProperties($intWidgetInstanceId){
		$this->core->logger->debug('cms->models->Model_WidgeInstanceProperties->getProperties('.$intWidgetInstanceId.')');
		
		try {
			$objSelect = $this->getWidgetInstancePropertiesTable()->select();
			$objSelect->from($this->getWidgetInstancePropertiesTable()->info('name'), array('property', 'value'));
			$objSelect->where('idWidgetInstances = ?', $intWidgetInstanceId);
			
			return $this->getWidgetInstancePropertiesTable()->fetchAll($objSelect)->toArray();
		}catch(Exception $exc){
			$this->core->logger->err($exc);
			exit();
		}
	}
	
	/**
	 * getWidgetInstancePropertiesTable
	 * @return Model_Table_WidgetInstanceProperties
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function getWidgetInstancePropertiesTable() {
		if($this->objWidgetInstancePropertiesTable === NULL) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/tables/WidgetInstanceProperties.php';
      $this->objWidgetInstancePropertiesTable = new Model_Table_WidgetInstanceProperties();
    }
    
    return $this->objWidgetInstancePropertiesTable;
	}
}
?>