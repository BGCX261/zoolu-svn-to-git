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
 * @package    library.massiveart.website.cache.navigation
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * NavigationItem
 * 
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-17: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 * @package massiveart.website.navigation
 * @subpackage NavigationItem
 */

class NavigationItem {
  
  protected $intOrder;
  
  protected $strTitle;
  protected $strUrl;
  
  protected $intId;
  protected $intParentId;
  protected $strItemId;
  
  /**
   * construct
   * @author Thomas Schedler <tsh@massiveart.com>   
   * @version 1.0
   */
  public function __construct() { }
  
  /**
   * setTitle
   * @param string $strTitle
   */
  public function setTitle($strTitle){
    $this->strTitle = $strTitle;
  }

  /**
   * getTitle
   * @param string $strTitle
   */
  public function getTitle(){
    return $this->strTitle;
  }
  
  /**
   * setUrl
   * @param string $strUrl
   */
  public function setUrl($strUrl){
    $this->strUrl = $strUrl;
  }

  /**
   * getUrl
   * @param string $strUrl
   */
  public function getUrl(){
    return $this->strUrl;
  }
  
  /**
   * setOrder
   * @return integer $intOrder
   */
  public function setOrder($intOrder){
    $this->intOrder = $intOrder;
  }
  
  /**
   * getOrder
   * @return integer
   */
  public function getOrder(){
    return $this->intOrder;
  }
  
  /**
   * setId
   * @return integer $intId
   */
  public function setId($intId){
    $this->intId = $intId;
  }
  
  /**
   * getId
   * @return integer
   */
  public function getId(){
    return $this->intId;
  }
  
  /**
   * setParentId
   * @return integer $intParentId
   */
  public function setParentId($intParentId){
    $this->intParentId = $intParentId;
  }
  
  /**
   * getParentId
   * @return integer
   */
  public function getParentId(){
    return $this->intParentId;
  }
  
  /**
   * setItemId
   * @param stirng $strItemId
   */
  public function setItemId($strItemId){
    $this->strItemId = $strItemId;
  }

  /**
   * getItemId
   * @param string $strItemId
   */
  public function getItemId(){
    return $this->strItemId;
  }
}
?>