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
 * @package    application.zoolu.modules.global.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Global_NavigationController
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-10-28: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class Global_NavigationController extends AuthControllerAction {

	private $intRootLevelId;
  private $intFolderId;

	private $intParentId;
	private $intParentTypeId;

	private $intLanguageId;

  /**
   * @var Model_Folders
   */
  protected $objModelFolders;

  /**
   * init
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   * @return void
   */
  public function init(){
    parent::init();
    Security::get()->addFoldersToAcl($this->getModelFolders());
    Security::get()->addRootLevelsToAcl($this->getModelFolders(), $this->core->sysConfig->modules->global);
  }

  /**
   * indexAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function indexAction(){
    $this->getModelFolders();
    $objRootLevels = $this->objModelFolders->loadAllRootLevelsWithGroups($this->core->sysConfig->modules->global);

    $objRootLevelNavigation = new NavigationTree();    
    if(count($objRootLevels) > 0){
      $intOrder = 0;
      foreach($objRootLevels as $objRootLevel){
        $intOrder++;
        
        if(!$objRootLevelNavigation->hasSubTree($objRootLevel->name)){
          $objNavGroup = new NavigationTree();
          $objNavGroup->setId($objRootLevel->idRootLevelGroups);
          $objNavGroup->setItemId($objRootLevel->name);
          $objNavGroup->setTypeId($objRootLevel->idRootLevelGroups);
          $objNavGroup->setTitle($objRootLevel->rootLevelGroupTitle);
          $objNavGroup->setUrl($objRootLevel->href);
          $objNavGroup->setLanguageId(((int) $objRootLevel->rootLevelGuiLanguageId > 0 ? $objRootLevel->rootLevelGuiLanguageId : $objRootLevel->rootLevelLanguageId));
          
          $objRootLevelNavigation->addTree($objNavGroup, $objRootLevel->name);
        }
        
        $objNavItem = new NavigationItem();
        $objNavItem->setId($objRootLevel->id);
        $objNavItem->setItemId($objRootLevel->name);
        $objNavItem->setTypeId($objRootLevel->idRootLevelTypes);
        $objNavItem->setTitle($objRootLevel->title);
        $objNavItem->setUrl($objRootLevel->href);
        $objNavItem->setOrder($intOrder);
        $objNavItem->setParentId($objRootLevel->idRootLevelGroups);
        $objNavItem->setLanguageId(((int) $objRootLevel->rootLevelGuiLanguageId > 0 ? $objRootLevel->rootLevelGuiLanguageId : $objRootLevel->rootLevelLanguageId));
        
        $objRootLevelNavigation->addToParentTree($objNavItem, $objRootLevel->name.'_'.$objRootLevel->id);        
      }
    }
    
    $this->view->assign('rootLevelNavigation', $objRootLevelNavigation);

    $this->view->assign('rootLevelId', $this->getRequest()->getParam('rootLevelId'));
    $this->view->assign('rootLevelGroupId', $this->getRequest()->getParam('rootLevelGroupId'));
    $this->view->assign('rootLevelGroupKey', $this->getRequest()->getParam('rootLevelGroupKey'));
    
    $strRenderSciprt = ($this->getRequest()->getParam('layoutType') == 'list') ? 'list.phtml' : 'tree.phtml';
    $this->renderScript('navigation/'.$strRenderSciprt);
  }


  /**
   * rootnavigationAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function rootnavigationAction(){
    $this->core->logger->debug('global->controllers->NavigationController->rootnavigationAction()');

    $objRequest = $this->getRequest();
    $intCurrLevel = $objRequest->getParam("currLevel");
    $this->setRootLevelId($objRequest->getParam("rootLevelId"));
    $intRootLevelGroupId = $objRequest->getParam('rootLevelGroupId');
    $strRootLevelGroupKey = $objRequest->getParam('rootLevelGroupKey', 'content');
    
    if(Security::get()->isAllowed(Security::RESOURCE_ROOT_LEVEL_PREFIX.$this->intRootLevelId, Security::PRIVILEGE_VIEW, true, false)){
      /**
       * get navigation
       */
      $this->getModelFolders();
      $objRootelements = $this->objModelFolders->loadGlobalRootNavigation($this->intRootLevelId, $intRootLevelGroupId);
  
      $this->view->assign('rootelements', $objRootelements);
      $this->view->assign('currLevel', $intCurrLevel);
          
      $this->view->assign('folderFormDefaultId', $this->core->sysConfig->form->ids->folders->default);
      $this->view->assign('elementFormDefaultId', $this->core->sysConfig->global_types->$strRootLevelGroupKey->default_formId);
      $this->view->assign('elementTemplateDefaultId', $this->core->sysConfig->global_types->$strRootLevelGroupKey->default_templateId);
      $this->view->assign('elementTypeDefaultId', $this->core->sysConfig->global_types->$strRootLevelGroupKey->id);
      
      $this->view->assign('rootLevelId', $this->intRootLevelId);
      $this->view->assign('rootLevelGroupId', $intRootLevelGroupId);
      $this->view->assign('rootLevelGroupKey', $strRootLevelGroupKey);
      $this->view->assign('return', true);
    }else{
      $this->view->assign('return', false);
    }
  }

  /**
   * childnavigationAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function childnavigationAction(){
    $this->core->logger->debug('global->controllers->NavigationController->childnavigationAction()');

    if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()) {
	    $objRequest = $this->getRequest();
	    $intCurrLevel = $objRequest->getParam("currLevel");
	    $this->setFolderId($objRequest->getParam("folderId"));
	    $this->setRootLevelId($objRequest->getParam("rootLevelId"));
      $intRootLevelGroupId = $objRequest->getParam('rootLevelGroupId');
      $strRootLevelGroupKey = $objRequest->getParam('rootLevelGroupKey', 'content');
      
	    /**
	     * get childnavigation
	     */
	    $this->getModelFolders();
	    $objChildelements = $this->objModelFolders->loadGlobalChildNavigation($this->intFolderId, $intRootLevelGroupId);

	    $this->view->assign('childelements', $objChildelements);
	    $this->view->assign('currLevel', $intCurrLevel);
	    
	    $this->view->assign('rootLevelId', $this->intRootLevelId);
      $this->view->assign('rootLevelGroupId', $intRootLevelGroupId);
      $this->view->assign('rootLevelGroupKey', $strRootLevelGroupKey);
    }
  }

  /**
   * updatepositionAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function updatepositionAction(){
    $this->core->logger->debug('global->controllers->NavigationController->updatepositionAction()');

    $this->getModelFolders();

    if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()) {
      $objRequest = $this->getRequest();
      $intElementId = $objRequest->getParam("id");
      $strElementType = $objRequest->getParam("elementType");
      $intRootLevelTypeId = $objRequest->getParam("rootLevelTypeId");
      $intRootLevelGroupId = $objRequest->getParam("rootLevelGroupId");
      $intSortPosition = $objRequest->getParam("sortPosition");
      $this->setRootLevelId($objRequest->getParam("rootLevelId"));
      $this->setParentId($objRequest->getParam("parentId"));

      $this->objModelFolders->updateSortPosition($intElementId, $strElementType, $intSortPosition, $this->intRootLevelId, $this->intParentId, $intRootLevelTypeId, $intRootLevelGroupId);
    }

    /**
     * no rendering
     */
    $this->_helper->viewRenderer->setNoRender();
  }

  /**
   * getModelFolders
   * @author Thomas Schedler <tsh@massiveart.com>
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
      $this->objModelFolders->setLanguageId($this->core->intZooluLanguageId);
    }

    return $this->objModelFolders;
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

  /**
   * setParentId
   * @param integer $intParentId
   */
  public function setParentId($intParentId){
    $this->intParentId = $intParentId;
  }

  /**
   * getParentId
   * @param integer $intParentId
   */
  public function getParentId(){
    return $this->intParentId;
  }

  /**
   * setParentTypeId
   * @param integer $intParentTypeId
   */
  public function setParentTypeId($intParentTypeId){
    $this->intParentTypeId = $intParentTypeId;
  }

  /**
   * getParentTypeId
   * @param integer $intParentTypeId
   */
  public function getParentTypeId(){
    return $this->intParentTypeId;
  }

  /**
   * setLanguageId
   * @param integer $intLanguageId
   */
  public function setLanguageId($intLanguageId){
    $this->intLanguageId = $intLanguageId;
  }

  /**
   * getLanguageId
   * @param integer $intLanguageId
   */
  public function getLanguageId(){
    return $this->intLanguageId;
  }
}

?>
