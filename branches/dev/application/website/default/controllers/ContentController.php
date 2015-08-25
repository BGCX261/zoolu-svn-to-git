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
 * @package    application.website.default.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * ContentController
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2010-04-15: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

require_once(GLOBAL_ROOT_PATH.'/library/IP2Location/ip2location.class.php');

class ContentController extends Zend_Controller_Action {

  /**
   * @var Core
   */
  protected $core; 
  
  /**
   * request object instacne
   * @var Zend_Controller_Request_Abstract
   */
  protected $request; 
    
  /**
   * @var Model_Categories
   */
  protected $objModelCategories;
  
  /**
   * preDispatch
   * Called before action method.
   * 
   * @return void  
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function preDispatch(){
    $this->core = Zend_Registry::get('Core');    
    $this->request = $this->getRequest();
  }
  
  /**
   * indexAction
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function indexAction(){
    $this->core->logger->debug('website->controllers->ContentController->indexAction()');
    $this->_helper->viewRenderer->setNoRender();
  }
  
  /**
   * destinationFilterAction
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function destinationFilterAction(){
    $this->core->logger->debug('website->controllers->ContentController->destinationFilterAction()');
    $this->_helper->viewRenderer->setNoRender();
    
    $strTmpCacheId = $this->getRequest()->getParam('tmpId');    
    $strCountryCode = ((isset($this->core->objCoreSession->countryshort)) ? $this->core->objCoreSession->countryshort : '');
    
    if($strTmpCacheId != '' && $this->core->TmpCache()->test($strTmpCacheId)){
      $arrDestinationSpecifics = $this->core->TmpCache()->load($strTmpCacheId);      
      $arrDesinationCountryCodes = array();
      if($strCountryCode == ''){
        $strCountryCode = $this->getCountryShortByIP($this->_getParam('ip'));  
      }
      foreach($arrDestinationSpecifics as $objPageEntry){
        if(!array_key_exists($objPageEntry->destinationId, $arrDesinationCountryCodes)){
          $arrDesinationCountryCodes[$objPageEntry->destinationId] = $this->getDesinationCountryCodes($objPageEntry->destinationId);
        }
        if(is_array($arrDesinationCountryCodes[$objPageEntry->destinationId]) && array_search($strCountryCode, $arrDesinationCountryCodes[$objPageEntry->destinationId]) !== false){
          echo $objPageEntry->output;          
        }       
      }
      if($this->core->sysConfig->cache->page == 'false') $this->core->TmpCache()->remove($strTmpCacheId);
    }
  }
  
  /**
   * getDesinationCountryCodes
   * @param integer $intDestinationId
   * @return array
   */
  private function getDesinationCountryCodes($intDestinationId){
    $arrCountryCodes = array();
    $objCategories = $this->getModelCategories()->loadCategoryTree($intDestinationId, true);
    if(count($objCategories) > 0){
      foreach($objCategories as $objCategory){
        $arrCountryCodes[] = $objCategory->code;
      }
    }
    return $arrCountryCodes;
  }
  
  /**
   * getCountryShortByIP
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function getCountryShortByIP($strIPAddress = ''){
    if(file_exists(GLOBAL_ROOT_PATH.'library/IP2Location/IP-COUNTRY.BIN')){      
      
      $ip = new ip2location();
      $ip->open(GLOBAL_ROOT_PATH.'library/IP2Location/IP-COUNTRY.BIN');
      
      $ipAddress = ((strpos($_SERVER['HTTP_HOST'], 'area51') === false) ? $_SERVER['REMOTE_ADDR'] : '84.72.245.26');
      if($strIPAddress != ''){
        $ipAddress = $strIPAddress;
      }
      $countryShort = $ip->getCountryShort($ipAddress);
      
      return $countryShort;
    }
  }
  
  /**
   * reloaded content 
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function reloadedAction(){    
    $this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->getRequest()->getParam('theme', 'default').'/reloaded/');
    $this->renderScript($this->getRequest()->getParam('key', 'empty').'.php');
  }
  
  /**
   * reloaded content 
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function expireCacheAction(){
    $this->core->logger->debug('website->controllers->ContentController->expireCacheAction()');
    $this->_helper->viewRenderer->setNoRender();
    
    $objAuth = Zend_Auth::getInstance();
    
    if($objAuth->hasIdentity()){
      $objWebsite = new Website();
      $objWebsite->expireCache();
    }
  }
  
  /**
   * getModelCategories
   * @return Model_Categories
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function getModelCategories(){
    if (null === $this->objModelCategories) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Categories.php';
      $this->objModelCategories = new Model_Categories();
      $this->objModelCategories->setLanguageId($this->core->sysConfig->languages->default->id);
    }

    return $this->objModelCategories;
  }
}
?>