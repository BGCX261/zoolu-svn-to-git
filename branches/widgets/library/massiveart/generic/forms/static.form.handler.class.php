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
 * @package    library.massiveart.generic.forms
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * StaticFormHandler Class - based on Singleton Pattern
 *
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-11: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.1
 * @package massiveart.forms
 * @subpackage StaticFormHandler
 */
class StaticFormHandler {
	
	/**
   * @var Core
   */
  private $core;
  
  /**
   * @var StaticFormHandler object instance
   */
  private static $instance = null;

	protected $objModel;
	protected $strHandlerDirectory;
	protected $strModuleName;
	protected $arrData;
	protected $arrFieldData;
  
	/**
   * Constructor
   */
  protected function __construct(){
    $this->core = Zend_Registry::get('Core');
  }

  private function __clone(){}

  /**
   * getInstance
   * @return StaticFormHandler object instance of the class
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public static function getInstance(){
    if(self::$instance == null){
      self::$instance = new StaticFormHandler();
    }
    return self::$instance;
  }
  
  /**
   * setHandlerDirectory
   * @param $strHandlerDirectory
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setHandlerDirectory($strHandlerDirectory) {
  	$this->strHandlerDirectory = $strHandlerDirectory;
  }
  
  /**
   * setModuleName
   * @param $strModuleName
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function setModuleName($strModuleName) {
  	$this->strModuleName = $strModuleName;
  }
  
  /**
   * getModelTable
   * @return unknown_type
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function getModelTable() {
  	/**
  	 * get Static Form Handler
  	 */
  	if(null === $this->objModel) {
  		/**
			 * autoload only handles "library" compoennts.
			 * Since this is an application model, we need to require it
			 * from its modules path location.
			 */
			require_once GLOBAL_ROOT_PATH.$this->strHandlerDirectory.'/'.$this->strModuleName.'.php';
			$strClassName = 'Model_'.ucfirst($this->strModuleName);
			$this->objModel = new $strClassName();
  	}
  	
  	return $this->objModel;
  }
  
  /**
   * save
   * @param $arrData
   * @return boolean
   */
  public function save($arrData) {
  	$this->core->logger->debug('library->massiveart->generic->forms->staticFormhandler->save()');
  	try{
	  	if(null === $this->objModel) {
	  		$this->getModelTable();
	  	}
	  	
	  	$arrFields = $this->objModel->getFormFields('default');
	  	foreach($arrFields AS $field) {
	  		if(array_key_exists($field, $arrData)) {
	  			$this->arrFieldData[$field] = $arrData[$field];
	  		}
	  	}
	  	
	  	$this->objModel->getBlogEntriesTable()->insert($this->arrFieldData);
  	}catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
}

?>