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
 * WidgetController
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-06: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */

class Cms_WidgetController extends AuthControllerAction {
	
	/**
	 * @var PARENT_TYPE_ROOTLEVEL
	 */
	const PARENT_TYPE_ROOTLEVEL = 1;
	
	/**
	 * @var PARENT_TYPE_FOLDER
	 */
	const PARENT_TYPE_FOLDER = 2;
	
	/**
	 * @var Model_Widgets
	 */
	protected $objModelWidgets;
	
	/**
	 * 
	 * @var GenericForm
	 */
	private $objForm;
	
	/**
   * init
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   * @return void
   */
  public function init(){
    parent::init();
    $this->objRequest = $this->getRequest();
  }
  
	/**
	 * widgettreeAction
	 * @author Florian Mathis <flo@massiveart.com
	 * @version 1.0
	 */
	public function widgettreeAction() {
		$this->core->logger->debug('core->controllers->WidgetController->widgettreeAction()');
		
		$intPortalId = $this->objRequest->getParam('portalId');
    $intFolderId = $this->objRequest->getParam('folderId');
    $intCurrLevel = $this->objRequest->getParam('currLevel');
    
    /**
     * Widget is in Root-Tree
     */
    if($intFolderId == '') {
    	$intParentId = $intPortalId;
    	$intParentType = self::PARENT_TYPE_ROOTLEVEL; 
    } else {
    	$intParentId = $intFolderId;
    	$intParentType = self::PARENT_TYPE_FOLDER;
    }

    $this->getModelWidget();
 		$objWidgets = $this->objModelWidgets->loadWidgets();

 		$this->view->assign('overlaytitle', 'Widget wählen');
 		$this->view->assign('elements', $objWidgets);
    $this->view->assign('parentId', $intParentId);
    $this->view->assign('parentType', $intParentType);
    $this->view->assign('currLevel', $intCurrLevel);
	}
	
	/**
	 * addAction
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function addAction() {
		$this->core->logger->debug('cms->controllers->WidgetController->addAction()');
		
		try {
			$this->getForm($this->core->sysConfig->generic->actions->add);
			$this->addWidgetSpecificFormElements();
			
			if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {
				$arrFormData = $this->objRequest->getPost();
				$this->objForm->Setup()->setFieldValues($arrFormData);
				
				$this->objForm->prepareForm();
				
				if($this->objForm->isValid($arrFormData)) {
					$this->objForm->setAction('/zoolu/cms/widget/edit');
					$intWidgetId = $this->objForm->saveFormData();
					$this->objForm->Setup()->setElementId($intWidgetId);
          $this->objForm->Setup()->setActionType($this->core->sysConfig->generic->actions->edit);
          $this->objForm->getElement('id')->setValue($intWidgetId);
          
          $this->view->assign('blnShowFormAlert', true);
				}else {
					$this->objForm->setAction('zoolu/cms/widget/add');
					$this->view->assign('blnShowFormAlert', false);
				}
			}else {
				$this->objForm->prepareForm();
			}
			
			$this->objForm->updateSpecialFieldValues();
			
			$this->view->formtitle = $this->objForm->Setup()->getFormTitle();
			
			$this->setViewMetaInfos();
			
			$this->view->form = $this->objForm;
			$this->renderScript('page/form.phtml');
		}catch(Exception $exc) {
			$this->core->logger->err($exc);
		}
	}
	
	/**
	 * getaddformAction
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	public function getaddformAction() {
		$this->core->logger->debug('cms->controllers->WidgetController->getaddformAction()');
		
		try {
			$this->getForm($this->core->sysConfig->generic->actions->add);
			$this->addWidgetSpecificFormElements();
			
			$this->objForm->setAction('/zoolu/cms/widget/add');
			
			$this->objForm->prepareForm();
			
			$this->view->formtitle = $this->objForm->Setup()->getFormTitle();
			
			/**
       * output of metainformation to hidden div
       */
      $this->setViewMetaInfos();
      $this->view->form = $this->objForm;
      
      $this->renderScript('page/form.phtml');
		}catch(Exception $exc) {
			$this->core->logger->err($exc);
			exit();
		}
	}
	
	/**
   * geteditformAction
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function geteditformAction() {
    $this->core->logger->debug('cms->controllers->WidgetController->geteditformAction()');
    
    try {
      $this->getForm($this->core->sysConfig->generic->actions->edit);

      $this->objForm->loadFormData();

      $this->addWidgetSpecificFormElements();
      
      $this->objForm->setAction('/zoolu/cms/widget/edit');
      $this->objForm->prepareForm();
      $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

      $this->setViewMetaInfos();
      $this->view->form = $this->objForm;
      
      $this->renderScript('page/form.phtml');
    }catch(Exception $exc){
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * editAction
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function editAction() {
    $this->core->logger->debug('cms->controllers->WidgetController->editAction()');
    
    try {
      $this->getForm($this->core->sysConfig->generic->actions->edit);
      $this->addWidgetSpecificFormElements();
      
      $this->view->formtitle = $this->objForm->Setup()->getFormTitle();
      
      if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {

        $arrFormData = $this->objRequest->getPost();
        $this->objForm->Setup()->setFieldValues($arrFormData);
        $this->objForm->Setup()->setElementId($arrFormData['elementId']);
        
        /**
         * prepare form (add fields and region to the Zend_Form)
         */
        $this->objForm->prepareForm();


        if($this->objForm->isValid($arrFormData)){
          $this->objForm->saveFormData();
          $this->view->assign('blnShowFormAlert', true);
        }else{
          $this->view->assign('blnShowFormAlert', false);
        }
      }else{
        /**
         * prepare form (add fields and region to the Zend_Form)
         */
        $this->objForm->prepareForm();
      }
      
      $this->objForm->setAction('/zoolu/cms/widget/edit');
      
      $this->setViewMetaInfos();

      $this->view->form = $this->objForm;

      $this->renderScript('page/form.phtml');
    }catch(Exception $exc){
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * deleteAction
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  public function deleteAction() {
  	$this->core->logger->debug('cms->controllers->WidgetController->deleteAction()');
  	
  	try {
  		$this->getModelWidget();
  		
  		if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {
        $this->objModelWidgets->deleteWidgetInstance($this->objRequest->getParam("id"));

        $this->view->blnShowFormAlert = true;
      }

      $this->renderScript('page/form.phtml');
  	}catch(Exception $exc) {
  		$this->core->logger->err($exc);
  	}
  }
  
  /**
   * addWidgetSpecificFormElements
   * @author Daniel Rotter <daniel.rotter@massiveart.com>
   * @version 1.0
   */
  protected function addWidgetSpecificFormElements(){
    if(is_object($this->objForm) && $this->objForm instanceof GenericForm){
      /**
       * add widget specific hidden fields
       * Here because the Setup isn't updated at the call of getForm()
       */
      $this->objForm->addElement('hidden', 'showInNavigation', array('value' => $this->objForm->Setup()->getShowInNavigation(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'idStatus', array('value' => $this->objForm->Setup()->getStatusId(), 'decorators' => array('Hidden')));
    }
  }
  
	
	/**
	 * getForm
	 * @param number $intActionType
	 * @author Daniel Rotter <daniel.rotter@massiveart.com>
	 * @version 1.0
	 */
	private function getForm($intActionType = null) {
		$this->core->logger->debug('cms->controllers->WidgetController->getForm('.$intActionType.')');
		
		try {
			/**
			 * Get Genericform ID by WidgetId
			 */
			if($this->objRequest->getParam("formId") == '' || $this->objRequest->getParam("formId") == '-1') {
				$objRow = $this->getModelWidget()->getGenericFormByWidgetId($this->objRequest->getParam('idWidget'));
	      $objFormHandler = FormHandler::getInstance();
	      $objFormHandler->setFormId($objRow->genericFormId);
	      $objFormHandler->setFormVersion($objRow->version);
			} else {
				$objRow = $this->getModelWidget()->getGenericFormById($this->objRequest->getParam('formId'));
				$objFormHandler = FormHandler::getInstance();
     	  $objFormHandler->setFormId($objRow->genericFormId);
      	$objFormHandler->setFormVersion($objRow->version);
			}
			
			/**
			 * Get GenericFormId
			 */      
      $objFormHandler->setActionType($intActionType);
      $objFormHandler->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
      $objFormHandler->setFormLanguageId(Zend_Auth::getInstance()->getIdentity()->languageId);
      $objFormHandler->setElementId(($this->objRequest->getParam("idWidgetInstance") != '') ? $this->objRequest->getParam("idWidgetInstance") : null);
      
      $this->objForm = $objFormHandler->getGenericForm();
      
      $this->objForm->Setup()->setCreatorId((($this->objRequest->getParam("creator") != '') ? $this->objRequest->getParam("creator") : Zend_Auth::getInstance()->getIdentity()->id));
      $this->objForm->Setup()->setStatusId((($this->objRequest->getParam("idStatus") != '') ? $this->objRequest->getParam("idStatus") : $this->core->sysConfig->form->status->default));
      $this->objForm->Setup()->setRootLevelId((($this->objRequest->getParam("rootLevelId") != '') ? $this->objRequest->getParam("rootLevelId") : null));
      $this->objForm->Setup()->setParentId((($this->objRequest->getParam("parentFolderId") != '') ? $this->objRequest->getParam("parentFolderId") : null));
      $this->objForm->Setup()->setIsStartElement((($this->objRequest->getParam("isStartPage") != '') ? $this->objRequest->getParam("isStartPage") : 0));
      $this->objForm->Setup()->setPublishDate((($this->objRequest->getParam("publishDate") != '') ? $this->objRequest->getParam("publishDate") : date('Y-m-d H:i:s')));
      $this->objForm->Setup()->setStatusId((($this->objRequest->getParam("idStatus") != '') ? $this->objRequest->getParam("idStatus") : $this->core->sysConfig->form->status->default));
      $this->objForm->Setup()->setShowInNavigation((($this->objRequest->getParam("showInNavigation") != '') ? $this->objRequest->getParam("showInNavigation") : 0));
      $this->objForm->Setup()->setElementTypeId((($this->objRequest->getParam("idWidget") != '') ? $this->objRequest->getParam("idWidget") : 0));
      $this->objForm->Setup()->setParentTypeId((($this->objRequest->getParam("parentType") != '') ? $this->objRequest->getParam("parentType") : (($this->objRequest->getParam("parentFolderId") != '') ? $this->core->sysConfig->parent_types->folder : $this->core->sysConfig->parent_types->rootlevel)));
      $this->objForm->Setup()->setElementId($this->objRequest->getParam('idWidgetInstance'));
      //Generate new widgetInstanceId if addAction is called
      if($intActionType == $this->core->sysConfig->generic->actions->add && $this->objRequest->getParam('instanceId') == ''){
      	$strWidgetInstanceId = uniqid();
      	$this->objForm->Setup()->setWidgetInstanceId($strWidgetInstanceId);
      }else{
      	$this->objForm->Setup()->setWidgetInstanceId($this->objRequest->getParam("instanceId"));
      }
      $this->objForm->Setup()->setModelSubPath('cms/models/');

      $this->objForm->addElement('hidden', 'parentFolderId', array('value' => $this->objRequest->getParam('parentFolderId'), 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'rootLevelId', array('value' => $this->objRequest->getParam('rootLevelId'), 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'currLevel', array('value' => $this->objRequest->getParam('currLevel'), 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'elementType', array('value' => 'widget', 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'parentType', array('value' => $this->objRequest->getParam('parentType'), 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'idWidget', array('value' => $this->objRequest->getParam('idWidget'), 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'elementId', array('value' => $this->objRequest->getParam('idWidgetInstance'), 'decorators' => array('Hidden'), 'ignore' => true));
      $this->objForm->addElement('hidden', 'isStartPage', array('value' => $this->objRequest->getParam('isStartPage'), 'decorators' => array('Hidden')));
      //Use the widgetInstanceId from above, if addAction is called
      if($intActionType == $this->core->sysConfig->generic->actions->add){
        $this->objForm->addElement('hidden', 'instanceId', array('value' => $this->objForm->Setup()->getWidgetInstanceId(), 'decorators' => array('Hidden')));
      }else{
        $this->objForm->addElement('hidden', 'instanceId', array('value' => $this->objRequest->getParam('instanceId'), 'decorators' => array('Hidden')));
      }
      $this->objForm->addElement('hidden', 'idWidgetInstance', array('value' => $this->objRequest->getParam('idWidgetInstance'), 'decorators' => array('Hidden')));
 		}catch(Exception $exc) {
			$this->core->logger->err($exc);
		}
	}
	
	/**
   * getModelWidget
   * @return Model_Widget
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  protected function getModelWidget(){
    if (null === $this->objModelWidgets) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Widgets.php';
      $this->objModelWidgets = new Model_Widgets();
    }

    return $this->objModelWidgets;
  }
  
	/**
   * setViewMetaInfos
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  private function setViewMetaInfos(){
    if(is_object($this->objForm) && $this->objForm instanceof GenericForm){
      $this->view->version = $this->objForm->Setup()->getFormVersion();
      $this->view->publisher = $this->objForm->Setup()->getPublisherName();
      $this->view->showinnavigation = $this->objForm->Setup()->getShowInNavigation();
      $this->view->changeUser = $this->objForm->Setup()->getChangeUserName();
      $this->view->publishDate = $this->objForm->Setup()->getPublishDate('d. M. Y, H:i');
      $this->view->changeDate = $this->objForm->Setup()->getChangeDate('d. M. Y, H:i');
      $this->view->statusOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, (SELECT statusTitles.title AS DISPLAY FROM statusTitles WHERE statusTitles.idStatus = status.id AND statusTitles.idLanguages = '.$this->objForm->Setup()->getFormLanguageId().') AS DISPLAY FROM status', $this->objForm->Setup()->getStatusId());
      $this->view->creatorOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, CONCAT(fname, \' \', sname) AS DISPLAY FROM users', $this->objForm->Setup()->getCreatorId());

      $this->view->arrPublishDate = DateTimeHelper::getDateTimeArray($this->objForm->Setup()->getPublishDate());
      $this->view->monthOptions = DateTimeHelper::getOptionsMonth(false, $this->objForm->Setup()->getPublishDate('n'));

      $this->view->blnIsStartPage = $this->objForm->Setup()->getIsStartElement(false);

      if($this->objForm->Setup()->getField('url')) $this->view->pageurl = $this->objForm->Setup()->getField('url')->getValue();

      if($this->objForm->Setup()->getActionType() == $this->core->sysConfig->generic->actions->edit && $this->objForm->Setup()->getElementTypeId() != $this->core->sysConfig->page_types->link->id) $this->view->languageOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, languageCode AS DISPLAY FROM languages', $this->objForm->Setup()->getFormLanguageId());
    }
  }
}

?>