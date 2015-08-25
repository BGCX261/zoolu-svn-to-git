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
 * @package    application.zoolu.modules.cms.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * OverlayController
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-24: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Cms_OverlayController extends AuthControllerAction {
	
	private $intRootLevelId;
  private $intFolderId;
  
  private $arrFileIds = array();
	
	/**
   * @var Model_Folders
   */
  protected $objModelFolders;
  
  /**
   * @var Model_Files
   */
  protected $objModelFiles;
	
  /**
   * @var Model_Contacts
   */
  protected $objModelContacts;
  
	/**
   * indexAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function indexAction(){ }

  /**
   * mediaAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function mediaAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->mediaAction()');    
    $this->loadRootNavigation($this->core->sysConfig->modules->media, $this->core->sysConfig->root_level_types->images);    
    $this->view->assign('overlaytitle', 'Medien zuweisen');
    $this->view->assign('viewtype', $this->core->sysConfig->viewtypes->thumb);
    $this->renderScript('overlay/overlay.phtml');       
  }
  
  /**
   * documentAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function documentAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->documentAction()');    
    $this->loadRootNavigation($this->core->sysConfig->modules->media, $this->core->sysConfig->root_level_types->documents);    
    $this->view->assign('overlaytitle', 'Dokumente zuweisen');
    $this->view->assign('viewtype', $this->core->sysConfig->viewtypes->list);
    $this->renderScript('overlay/overlay.phtml');       
  }
  
  /**
   * contactAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function contactAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->contactAction()');    
    
    $this->loadRootContactsAndUnits();
        
    $this->view->assign('overlaytitle', 'Kontakt zuweisen');
    $this->view->assign('viewtype', $this->core->sysConfig->viewtypes->list);          
  }
  
  /**
   * pagetreeAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function pagetreeAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->pagetreeAction()');
    
    $objRequest = $this->getRequest();
    $intPortalId = $objRequest->getParam('portalId');
    
    $this->loadTreeForPortal($intPortalId);
    $this->view->assign('overlaytitle', 'Seite wählen');
  }
  
  /**
   * thumbviewAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function thumbviewAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->thumbviewAction()');	
  	
    $objRequest = $this->getRequest();
    $intFolderId = $objRequest->getParam('folderId');
    $strFileIds = $objRequest->getParam('fileIds');

    $strTmpFileIds = trim($strFileIds, '[]');
    $this->arrFileIds = split('\]\[', $strTmpFileIds);
    
    /**
     * get files
     */
    $this->getModelFiles();
    $objFiles = $this->objModelFiles->loadFiles($intFolderId);
    
    $this->view->assign('objFiles', $objFiles);
    $this->view->assign('arrFileIds', $this->arrFileIds);    
  }
  
  /**
   * listviewAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function listviewAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->listviewAction()'); 
    
    $objRequest = $this->getRequest();
    $intFolderId = $objRequest->getParam('folderId');
    $strFileIds = $objRequest->getParam('fileIds');

    $strTmpFileIds = trim($strFileIds, '[]');
    $this->arrFileIds = split('\]\[', $strTmpFileIds);
    
    /**
     * get files
     */
    $this->getModelFiles();
    $objFiles = $this->objModelFiles->loadFiles($intFolderId);
    
    $this->view->assign('objFiles', $objFiles);
    $this->view->assign('arrFileIds', $this->arrFileIds);
  }
  
  /**
   * contactlistAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function contactlistAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->contactlistAction()'); 
    
    $objRequest = $this->getRequest();
    $intUnitId = $objRequest->getParam('unitId');
    $strFileIds = $objRequest->getParam('fileIds');

    $strTmpFileIds = trim($strFileIds, '[]');
    $this->arrFileIds = split('\]\[', $strTmpFileIds);
    
    /**
     * get contacts
     */
    $this->getModelContacts();
    $objContacts = $this->objModelContacts->loadContactsByUnitId($intUnitId);
    
    $this->view->assign('elements', $objContacts);
    $this->view->assign('fileIds', $this->arrFileIds);
  }
  
  /**
   * childnavigationAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function childnavigationAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->childnavigationAction()');
    
    $this->getModelFolders();
    
    $objRequest = $this->getRequest();
    $this->intFolderId = $objRequest->getParam("folderId");
    $viewtype = $objRequest->getParam("viewtype");
    
    /**
     * get childfolders
     */
    $objChildelements = $this->objModelFolders->loadChildFolders($this->intFolderId);
        
    $this->view->assign('elements', $objChildelements);
    $this->view->assign('intFolderId', $this->intFolderId);
    $this->view->assign('viewtype', $viewtype);
  }
    
  /**
   * unitchildsAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function unitchildsAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->unitchildsAction()');    
    
    $objRequest = $this->getRequest();
    $intUnitId = $objRequest->getParam("unitId");
        
    /**
     * get unit childs
     */
    $this->getModelContacts();
    $objChildUnits = $this->objModelContacts->loadNavigation($intUnitId, true);
        
    $this->view->assign('elements', $objChildUnits);
    $this->view->assign('unitId', $intUnitId);          
  }
  
  /**
   * loadRootNavigation
   * @param integer $intRootLevelModule
   * @param integer $intRootLevelType
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function loadRootNavigation($intRootLevelModule, $intRootLevelType = -1){
    $this->core->logger->debug('cms->controllers->OverlayController->loadRootNavigation('.$intRootLevelModule.', '.$intRootLevelType.')');
    
    $this->getModelFolders();    
    $objMediaRootLevels = $this->objModelFolders->loadAllRootLevels($intRootLevelModule, $intRootLevelType);
 
    if(count($objMediaRootLevels) > 0){
      $objMediaRootLevel = $objMediaRootLevels->current();
      $this->intRootLevelId = $objMediaRootLevel->id;
      $objRootelements = $this->objModelFolders->loadRootFolders($this->intRootLevelId);
      
      $this->view->assign('elements', $objRootelements);
      $this->view->assign('rootLevelId', $this->intRootLevelId);
    }
  }
  
  /**
   * loadRootContactsAndUnits
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function loadRootContactsAndUnits(){
    $this->core->logger->debug('cms->controllers->OverlayController->loadRootContactsAndUnits()');
    
    $this->intRootLevelId = 0; //Root id = 0
    
    $this->getModelContacts();
    $objRootUnits = $this->objModelContacts->loadNavigation($this->intRootLevelId, true);
    $objRootContacts = $this->objModelContacts->loadContactsByUnitId($this->intRootLevelId);

    $this->view->assign('navElements', $objRootUnits);
    $this->view->assign('listElements', $objRootContacts);
    $this->view->assign('rootLevelId', $this->intRootLevelId);
  }
  
  /**
   * loadTreeForPortal
   * @param integer $intPortalId
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function loadTreeForPortal($intPortalId){
    $this->core->logger->debug('cms->controllers->OverlayController->loadTreeForPortal('.$intPortalId.')');
    
    $this->getModelFolders();
    $objPageTree = $this->objModelFolders->loadRootLevelChilds($intPortalId);

    $this->view->assign('elements', $objPageTree);
    $this->view->assign('portalId', $intPortalId);
  }
  
  /**
   * getModelFolders
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelFolders(){
    if (null === $this->objModelFolders) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it 
       * from its modules path location.
       */ 
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Folders.php';
      $this->objModelFolders = new Model_Folders();
      $this->objModelFolders->setLanguageId(1); // TODO : get language id
    }
    
    return $this->objModelFolders;
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
  
  /**
   * getModelContacts
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelContacts(){
    if (null === $this->objModelContacts) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it 
       * from its modules path location.
       */ 
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Contacts.php';
      $this->objModelContacts = new Model_Contacts();
      $this->objModelContacts->setLanguageId(1); // TODO : get language id
    }
    
    return $this->objModelContacts;
  }
  
  /**
   * setRootLevelId
   * @param integer $intRootLevelId
   */
  public function setRootLevelId($intRootLevelId){
    $this->intRootLevelId = $intRootLevelId;  
  }
  
  /**
   * getRootLevelId
   * @param integer $intRootLevelId
   */
  public function getRootLevelId(){
    return $this->intRootLevelId;  
  }
  
  /**
   * setFolderId
   * @param integer $intFolderId
   */
  public function setFolderId($intFolderId){
    $this->intFolderId = $intFolderId;  
  }
  
  /**
   * getFolderId
   * @param integer $intFolderId
   */
  public function getFolderId(){
    return $this->intFolderId;  
  }
	
}

?>