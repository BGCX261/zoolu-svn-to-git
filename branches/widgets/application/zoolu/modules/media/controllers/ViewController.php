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
 * @package    application.zoolu.modules.core.media.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Media_ViewController
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-10: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Media_ViewController extends AuthControllerAction  {
	
	/**
   * @var Model_Files
   */
  protected $objModelFiles;
	
  /**
   * indexAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function indexAction(){ }
  
  /**
   * thumbAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function thumbAction(){
    $this->core->logger->debug('media->controllers->ViewController->thumbAction()');
    
    $objRequest = $this->getRequest();
    $intFolderId = $objRequest->getParam('folderId');    
    $intSliderValue = $objRequest->getParam('sliderValue');
    
    /**
     * get files
     */
    $this->getModelFiles();
    $objFiles = $this->objModelFiles->loadFiles($intFolderId);
    
    $this->view->assign('objFiles', $objFiles);
    $this->view->assign('sliderValue', $intSliderValue);    	
  }
  
  /**
   * listAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function listAction(){
    $this->core->logger->debug('media->controllers->ViewController->listAction()');

    $objRequest = $this->getRequest();
    $intFolderId = $objRequest->getParam('folderId');    
    
    /**
     * get files
     */
    $this->getModelFiles();
    $objFiles = $this->objModelFiles->loadFiles($intFolderId);
    
    $this->view->assign('objFiles', $objFiles);    
  }
  
  /**
   * dashboardAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function dashboardAction(){ 
    $this->core->logger->debug('media->controllers->ViewController->dashboardAction()');  
    
    try{
      $this->getModelFiles();
      
      if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()) {
        $intLimitNumber = 10;
        
        $objFiles = $this->objModelFiles->loadFiles('', $intLimitNumber);
        
        $this->view->assign('objFiles', $objFiles);
        $this->view->assign('limit', $intLimitNumber);
      }
      
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }
  
  /**
   * getModelFiles
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelFiles(){
    if (null === $this->objModelFiles) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it 
       * from its modules path location.
       */ 
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Files.php';
      $this->objModelFiles = new Model_Files();
      $this->objModelFiles->setLanguageId(1); // TODO : get language id
    }
    
    return $this->objModelFiles;
  }
  
}

?>