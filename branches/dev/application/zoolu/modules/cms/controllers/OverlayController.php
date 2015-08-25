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
  
  /**
   * @var integer
   */
  protected $intItemLanguageId;

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
   * @var Model_RootLevels
   */
  protected $objModelRootLevels;

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
    $this->view->assign('rootLevelType', $this->core->sysConfig->root_level_types->images);
    $this->view->assign('overlaytitle', $this->core->translate->_('Assign_medias'));
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
    $this->view->assign('rootLevelType', $this->core->sysConfig->root_level_types->documents);
    $this->view->assign('overlaytitle', $this->core->translate->_('Assign_documents'));
    $this->view->assign('viewtype', $this->core->sysConfig->viewtypes->list);
    $this->renderScript('overlay/overlay.phtml');
  }
  
  /**
   * videoAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function videoAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->videoAction()');
    $this->loadRootNavigation($this->core->sysConfig->modules->media, $this->core->sysConfig->root_level_types->videos);
    $this->view->assign('rootLevelType', $this->core->sysConfig->root_level_types->videos);
    $this->view->assign('overlaytitle',  $this->core->translate->_('Assign_videos'));
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

    $this->view->assign('overlaytitle', $this->core->translate->_('Assign_contacts'));
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
    $strItemAction = $objRequest->getParam('itemAction', 'myOverlay.selectPage');

    $strPageIds = $objRequest->getParam('itemIds');

    $strTmpPageIds = trim($strPageIds, '[]');
    $arrPageIds = split('\]\[', $strTmpPageIds);


    $this->loadPageTreeForPortal($intPortalId);
    $this->view->assign('overlaytitle', $this->core->translate->_('Select_page'));
    $this->view->assign('itemAction', $strItemAction);
    $this->view->assign('pageIds', $arrPageIds);
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
   * maintenanceAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function maintenanceAction(){
    $this->core->logger->debug('cms->controllers->OverlayController->maintenanceAction()');

    $objRequest = $this->getRequest();
    $this->intRootLevelId = $objRequest->getParam('rootLevelId');
    $strAction = $objRequest->getParam('operation'); 
    
    $this->getModelRootLevels(); 
    
    if($strAction == 'save'){
      /**
       * no rendering
       */
      $this->_helper->viewRenderer->setNoRender();
      
      /**
       * save maintenance properties
       */
      $arrFormData = $objRequest->getPost();
      $this->objModelRootLevels->saveMaintenance($this->intRootLevelId, $arrFormData);
      
      /**
       * check if maintenance is active or not
       */
      $blnIsMaintenance = $this->objModelRootLevels->loadMaintenance($this->intRootLevelId, true);
      
      $arrReturn = array('active' => $blnIsMaintenance);
      $this->_response->setBody(json_encode($arrReturn));
    }else{
      $objReturn = $this->objModelRootLevels->loadMaintenance($this->intRootLevelId); 
      $this->view->assign('return', $objReturn); 
    }
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

    $this->intRootLevelId = 5; 

    $this->getModelContacts();
    $objRootUnits = $this->objModelContacts->loadNavigation($this->intRootLevelId, null, true);
    $objRootContacts = $this->objModelContacts->loadContactsByUnitId($this->intRootLevelId);

    $this->view->assign('navElements', $objRootUnits);
    $this->view->assign('listElements', $objRootContacts);
    $this->view->assign('rootLevelId', $this->intRootLevelId);
  }

  /**
   * loadPageTreeForPortal
   * @param integer $intPortalId
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function loadPageTreeForPortal($intPortalId){
    $this->core->logger->debug('cms->controllers->OverlayController->loadPageTreeForPortal('.$intPortalId.')');

    $this->getModelFolders();
    $intPortalLanguageId = $this->getRequest()->getParam('portalLanguageId');
    if((int) $intPortalLanguageId > 0){
      $this->objModelFolders->setLanguageId($intPortalLanguageId);   
    }
    
    $objPageTree = $this->objModelFolders->loadRootLevelChilds($intPortalId);

    $this->view->assign('elements', $objPageTree);
    $this->view->assign('portalId', $intPortalId);
  }
  
  /**
   * getItemLanguageId
   * @param integer $intActionType
   * @return integer
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0 
   */
  protected function getItemLanguageId($intActionType = null){
    if($this->intItemLanguageId == null){
      if(!$this->getRequest()->getParam("languageId")){
        $this->intItemLanguageId = $this->getRequest()->getParam("rootLevelLanguageId") != '' ? $this->getRequest()->getParam("rootLevelLanguageId") : $this->core->intZooluLanguageId;
        
        $intRootLevelId = $this->getRequest()->getParam("rootLevelId");
        $PRIVILEGE = ($intActionType == $this->core->sysConfig->generic->actions->add) ? Security::PRIVILEGE_ADD : Security::PRIVILEGE_UPDATE;
        
        $arrLanguages = $this->core->config->languages->language->toArray();      
        foreach($arrLanguages as $arrLanguage){
          if(Security::get()->isAllowed(Security::RESOURCE_ROOT_LEVEL_PREFIX.$intRootLevelId.'_'.$arrLanguage['id'], $PRIVILEGE, false, false)){
            $this->intItemLanguageId = $arrLanguage['id']; 
            break;
          }          
        }
        
      }else{
        $this->intItemLanguageId = $this->getRequest()->getParam("languageId");
      }
    }
    
    return $this->intItemLanguageId;
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
      $this->objModelFolders->setLanguageId($this->getItemLanguageId());
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
      $this->objModelFiles->setLanguageId($this->getItemLanguageId());
      $this->objModelFiles->setAlternativLanguageId(Zend_Auth::getInstance()->getIdentity()->languageId);
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
      $this->objModelContacts->setLanguageId($this->core->intZooluLanguageId);
    }

    return $this->objModelContacts;
  }
  
  /**
   * getModelRootLevels
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelRootLevels(){
    if (null === $this->objModelRootLevels) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/RootLevels.php';
      $this->objModelRootLevels = new Model_RootLevels();
      $this->objModelRootLevels->setLanguageId($this->core->intZooluLanguageId);
    }

    return $this->objModelRootLevels;
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