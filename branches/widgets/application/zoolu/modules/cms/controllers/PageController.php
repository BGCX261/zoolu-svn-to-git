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
 * PageController
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-06: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Cms_PageController extends AuthControllerAction {

  /**
   * @var GenericForm
   */
  var $objForm;

  /**
   * request object instance
   * @var Zend_Controller_Request_Abstract
   */
  protected $objRequest;

  /**
   * @var Model_Pages
   */
  protected $objModelPages;

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
   * @var Model_Templates
   */
  protected $objModelTemplates;

  /**
   * @var Model_GenericForms
   */
  protected $objModelGenericForm;

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
   * The default action
   */
  public function indexAction(){ }

  /**
   * getaddformAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getaddformAction(){
    $this->core->logger->debug('cms->controllers->PageController->getaddformAction()');

    try{
	    $this->getForm($this->core->sysConfig->generic->actions->add);
	    $this->addPageSpecificFormElements();

	    /**
	     * set action
	     */
	    $this->objForm->setAction('/zoolu/cms/page/add');

	    /**
	     * prepare form (add fields and region to the Zend_Form)
	     */
	    $this->objForm->prepareForm();

	    /**
	     * get form title
	     */
	    $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

	    /**
       * output of metainformation to hidden div
       */
      $this->setViewMetaInfos();

	    $this->view->form = $this->objForm;

	    $this->renderScript('page/form.phtml');
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * addAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function addAction(){
    $this->core->logger->debug('cms->controllers->PageController->addAction()');

    try{
	    $this->getForm($this->core->sysConfig->generic->actions->add);
	    $this->addPageSpecificFormElements();

	    if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {

	      $arrFormData = $this->objRequest->getPost();
	      $this->objForm->Setup()->setFieldValues($arrFormData);

	      /**
	       * prepare form (add fields and region to the Zend_Form)
	       */
	      $this->objForm->prepareForm();

	      if($this->objForm->isValid($arrFormData)){
	        /**
	         * set action
	         */
	        $this->objForm->setAction('/zoolu/cms/page/edit');

	        $intPageId = $this->objForm->saveFormData();
	        $this->objForm->Setup()->setElementId($intPageId);
	        $this->objForm->Setup()->setActionType($this->core->sysConfig->generic->actions->edit);
	        $this->objForm->getElement('id')->setValue($intPageId);

	        $this->view->assign('blnShowFormAlert', true);
	      }else{
	        /**
	         * set action
	         */
	        $this->objForm->setAction('/zoolu/cms/page/add');
	        $this->view->assign('blnShowFormAlert', false);
	      }
	    }else{

	    	/**
	       * prepare form (add fields and region to the Zend_Form)
	       */
	      $this->objForm->prepareForm();
	    }

	    /**
       * update special field values
       */
      $this->objForm->updateSpecialFieldValues();

	    /**
	     * get form title
	     */
	    $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

	     /**
       * output of metainformation to hidden div
       */
      $this->setViewMetaInfos();

      $this->view->form = $this->objForm;

	    $this->renderScript('page/form.phtml');
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * geteditformAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function geteditformAction(){
    $this->core->logger->debug('cms->controllers->PageController->geteditformAction()');

    try{
	    $this->getForm($this->core->sysConfig->generic->actions->edit);

	    /**
	     * load generic data
	     */
	    $this->objForm->loadFormData();
      $this->addPageSpecificFormElements();

	    /**
	     * set action
	     */
	    $this->objForm->setAction('/zoolu/cms/page/edit');

	    /**
	     * prepare form (add fields and region to the Zend_Form)
	     */
	    $this->objForm->prepareForm();

	    /**
	     * get form title
	     */
	    $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

	    /**
	     * output of metainformation to hidden div
	     */
	    $this->setViewMetaInfos();

	    $this->view->form = $this->objForm;

	    $this->renderScript('page/form.phtml');
	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * editAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function editAction(){
    $this->core->logger->debug('cms->controllers->PageController->editAction()');

    try{
	    $this->getForm($this->core->sysConfig->generic->actions->edit);
	    $this->addPageSpecificFormElements();

	    /**
	     * get form title
	     */
	    $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

	    if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {

	      $arrFormData = $this->objRequest->getPost();
	      $this->objForm->Setup()->setFieldValues($arrFormData);

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

	    /**
       * update special field values
       */
      $this->objForm->updateSpecialFieldValues();

	    /**
       * set action
       */
      $this->objForm->setAction('/zoolu/cms/page/edit');


      /**
       * output of metainformation to hidden div
       */
      $this->setViewMetaInfos();

      $this->view->form = $this->objForm;

	    $this->renderScript('page/form.phtml');
	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
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
      $this->view->statusOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, (SELECT statusTitles.title AS DISPLAY FROM statusTitles WHERE statusTitles.idStatus = status.id AND statusTitles.idLanguages = '.$this->objForm->Setup()->getLanguageId().') AS DISPLAY FROM status', $this->objForm->Setup()->getStatusId());
      $this->view->creatorOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, CONCAT(fname, \' \', sname) AS DISPLAY FROM users', $this->objForm->Setup()->getCreatorId());

      if($this->objForm->Setup()->getIsStartElement(false) == true){
        $this->view->typeOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, (SELECT pageTypeTitles.title AS DISPLAY FROM pageTypeTitles WHERE pageTypeTitles.idPageTypes = pageTypes.id AND pageTypeTitles.idLanguages = '.$this->objForm->Setup()->getLanguageId().') AS DISPLAY FROM pageTypes WHERE startpage = 1', $this->objForm->Setup()->getElementTypeId());
      }else{
        $this->view->typeOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, (SELECT pageTypeTitles.title AS DISPLAY FROM pageTypeTitles WHERE pageTypeTitles.idPageTypes = pageTypes.id AND pageTypeTitles.idLanguages = '.$this->objForm->Setup()->getLanguageId().') AS DISPLAY FROM pageTypes WHERE page = 1', $this->objForm->Setup()->getElementTypeId());
      }

      $this->view->arrPublishDate = DateTimeHelper::getDateTimeArray($this->objForm->Setup()->getPublishDate());
      $this->view->monthOptions = DateTimeHelper::getOptionsMonth(false, $this->objForm->Setup()->getPublishDate('n'));

      $this->view->blnIsStartPage = $this->objForm->Setup()->getIsStartElement(false);

      if($this->objForm->Setup()->getField('url')) $this->view->pageurl = $this->objForm->Setup()->getField('url')->getValue();

      if($this->objForm->Setup()->getActionType() == $this->core->sysConfig->generic->actions->edit && $this->objForm->Setup()->getElementTypeId() != $this->core->sysConfig->page_types->link->id) $this->view->languageOptions = HtmlOutput::getOptionsOfSQL($this->core, 'SELECT id AS VALUE, languageCode AS DISPLAY FROM languages', $this->objForm->Setup()->getLanguageId());
    }
  }

  /**
   * getfilesAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getfilesAction(){
    $this->core->logger->debug('cms->controllers->PageController->getfilesAction()');

    try{
	    $strFileIds = $this->objRequest->getParam('fileIds');
	    $strFieldName = $this->objRequest->getParam('fileFieldId');
	    $strViewType = $this->objRequest->getParam('viewtype');

	    /**
	     * get files
	     */
	    $this->getModelFiles();
	    $objFiles = $this->objModelFiles->loadFilesById($strFileIds);

	    $this->view->assign('objFiles', $objFiles);
	    $this->view->assign('fieldname', $strFieldName);
	    $this->view->assign('viewtype', $strViewType);
	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * getcontactsAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function getcontactsAction(){
    $this->core->logger->debug('cms->controllers->PageController->getcontactsAction()');

    try{
      $strContactIds = $this->objRequest->getParam('contactIds');
      $strFieldName = $this->objRequest->getParam('fieldId');

      /**
       * get files
       */
      $this->getModelContacts();
      $objContacts = $this->objModelContacts->loadContactsById($strContactIds);

      $this->view->assign('elements', $objContacts);
      $this->view->assign('fieldname', $strFieldName);
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * deleteAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function deleteAction(){
    $this->core->logger->debug('cms->controllers->PageController->deleteAction()');

    try{
	    $this->getModelPages();

	    if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {
		    $this->objModelPages->deletePage($this->objRequest->getParam("id"));

		    $this->view->blnShowFormAlert = true;
	    }

	    $this->renderScript('page/form.phtml');
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * dashboardAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function dashboardAction(){
    $this->core->logger->debug('cms->controllers->PageController->dashboardAction()');

    try{
      $this->getModelFolders();

      if($this->objRequest->isPost() && $this->objRequest->isXmlHttpRequest()) {
        $intRootLevelId = $this->objRequest->getParam('rootLevelId');
        $intLimitNumber = 10;

        $objPages = $this->objModelFolders->loadLimitedRootLevelChilds($intRootLevelId, $intLimitNumber);

        $this->view->assign('objPages', $objPages);
        $this->view->assign('limit', $intLimitNumber);
      }

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * changetemplateAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function changetemplateAction(){
    $this->core->logger->debug('cms->controllers->PageController->changetemplateAction()');

    try{
      $objGenericData = new GenericData();

      $objGenericData->Setup()->setFormId($this->objRequest->getParam("formId"));
      $objGenericData->Setup()->setFormVersion($this->objRequest->getParam("formVersion"));
      $objGenericData->Setup()->setFormTypeId($this->objRequest->getParam("formTypeId"));
      $objGenericData->Setup()->setTemplateId($this->objRequest->getParam("templateId"));
      $objGenericData->Setup()->setElementId($this->objRequest->getParam("id"));
      $objGenericData->Setup()->setActionType($this->core->sysConfig->generic->actions->edit);
      $objGenericData->Setup()->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
      $objGenericData->Setup()->setFormLanguageId(Zend_Auth::getInstance()->getIdentity()->languageId);
      $objGenericData->Setup()->setModelSubPath('cms/models/');

      /**
       * change Template
       */
      $objGenericData->changeTemplate($this->objRequest->getParam("newTemplateId"));

      $this->objRequest->setParam("formId", $objGenericData->Setup()->getFormId());
      $this->objRequest->setParam("templateId", $objGenericData->Setup()->getTemplateId());
      $this->objRequest->setParam("formVersion", $objGenericData->Setup()->getFormVersion());

      $this->getForm($this->core->sysConfig->generic->actions->edit);

      /**
       * load generic data
       */
      $this->objForm->setGenericSetup($objGenericData->Setup());
      $this->addPageSpecificFormElements();

      /**
       * set action
       */
      $this->objForm->setAction('/zoolu/cms/page/edit');

      /**
       * prepare form (add fields and region to the Zend_Form)
       */
      $this->objForm->prepareForm();

      /**
       * get form title
       */
      $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

      /**
       * output of metainformation to hidden div
       */
      $this->setViewMetaInfos();

      $this->view->form = $this->objForm;

      $this->renderScript('page/form.phtml');

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * changelanguageAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function changelanguageAction(){
    $this->core->logger->debug('cms->controllers->PageController->changelanguageAction()');

    try{
      $this->_forward('geteditform');
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * changetypeAction
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function changetypeAction(){
    $this->core->logger->debug('cms->controllers->PageController->changetypeAction()');

    try{

      if($this->objRequest->getParam('pageTypeId') != '' && $this->objRequest->getParam('pageTypeId') > 0){
      	switch($this->objRequest->getParam('pageTypeId')){
        	case $this->core->sysConfig->page_types->page->id :
            $this->objRequest->setParam('formId', '');
            if($this->objRequest->getParam('isStartPage') == 'true' && $this->objRequest->getParam('parentTypeId') == $this->core->sysConfig->parent_types->rootlevel){
              $this->objRequest->setParam('templateId', $this->core->sysConfig->page_types->page->portal_startpage_templateId);
            }else if($this->objRequest->getParam('isStartPage') == 'true'){
              $this->objRequest->setParam('templateId', $this->core->sysConfig->page_types->page->startpage_templateId);
            }else{
              $this->objRequest->setParam('templateId', $this->core->sysConfig->page_types->page->default_templateId);
            }
            break;
          case $this->core->sysConfig->page_types->link->id :
            $this->objRequest->setParam('formId', $this->core->sysConfig->page_types->link->default_formId);
            break;
          case $this->core->sysConfig->page_types->overview->id :
            $this->objRequest->setParam('formId', '');
            $this->objRequest->setParam('templateId', $this->core->sysConfig->page_types->overview->default_templateId);
            break;
          case $this->core->sysConfig->page_types->external->id :
            $this->objRequest->setParam('formId', $this->core->sysConfig->page_types->external->default_formId);
            break;
          case $this->core->sysConfig->page_types->process->id :
            $this->objRequest->setParam('formId', $this->core->sysConfig->page_types->process->default_formId);
            break;
        }
      }

      $this->getForm($this->core->sysConfig->generic->actions->edit);

      /**
       * load generic data
       */
      $this->objForm->loadFormData();

      /**
       * overwrite now the page type
       */
      $this->objForm->Setup()->setElementTypeId($this->objRequest->getParam('pageTypeId'));
      $this->addPageSpecificFormElements();

      /**
       * set action
       */
      $this->objForm->setAction('/zoolu/cms/page/edit');

      /**
       * prepare form (add fields and region to the Zend_Form)
       */
      $this->objForm->prepareForm();

      /**
       * get form title
       */
      $this->view->formtitle = $this->objForm->Setup()->getFormTitle();

      /**
       * output of metainformation to hidden div
       */
      $this->setViewMetaInfos();

      $this->view->form = $this->objForm;

      $this->renderScript('page/form.phtml');

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * linkedpagefieldAction
   * @author Thomas Schedler <cha@massiveart.com>
   * @version 1.0
   */
  public function linkedpagefieldAction(){
    $this->core->logger->debug('cms->controllers->PageController->linkedpagefieldAction()');

    $strFieldId = $this->objRequest->getParam('fieldId');
    $strFormId = $this->objRequest->getParam('formId');
    $intFormVersion = $this->objRequest->getParam('formVersion');
    $intPageId = $this->objRequest->getParam('pageId');

    $objFieldData = $this->getModelGenericForm()->loadFieldByName($strFieldId, $strFormId, $intFormVersion);
    if(count($objFieldData) > 0){
      $objFieldRegionData = $objFieldData->current();

      require_once(GLOBAL_ROOT_PATH.'library/massiveart/generic/elements/generic.element.field.class.php');
      $objField = new GenericElementField();
      $objField->id = $objFieldRegionData->id;
      $objField->title = $objFieldRegionData->title;
      $objField->name = $objFieldRegionData->name;
      $objField->typeId = $objFieldRegionData->idFieldTypes;
      $objField->type = $objFieldRegionData->type;
      $objField->defaultValue = $objFieldRegionData->defaultValue;
      $objField->sqlSelect = $objFieldRegionData->sqlSelect;
      $objField->columns = $objFieldRegionData->columns;
      $objField->order = $objFieldRegionData->order;
      $objField->isCoreField = $objFieldRegionData->isCoreField;
      $objField->isKeyField = $objFieldRegionData->isKeyField;
      $objField->isSaveField = $objFieldRegionData->isSaveField;
      $objField->isRegionTitle = $objFieldRegionData->isRegionTitle;
      $objField->isDependentOn = $objFieldRegionData->isDependentOn;
      $objField->copyValue = $objFieldRegionData->copyValue;
      $objField->decorator = $objFieldRegionData->decorator;
      $objField->isMultiply = $objFieldRegionData->isMultiply;

      $objGenericSetup = new GenericSetup();
      $objGenericSetup->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));

      $objField->setGenericSetup($objGenericSetup);
      $objField->loadLinkPage($intPageId);

      require_once(GLOBAL_ROOT_PATH.'library/massiveart/generic/fields/InternalLink/forms/elements/InternalLink.php');
      $objElement= new Form_Element_InternalLink($strFieldId, array(
          'value' => $objField->getValue(),
          'label' => $objField->title,
          'description' => $objField->description,
          'fieldId' => $objField->id,
          'columns' => $objField->columns,
          'class' => $objField->type,
          'height' => $objField->height,
          'isGenericSaveField' => $objField->isSaveField,
          'isCoreField' => $objField->isCoreField,
          'LanguageId' => $this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id),
          'isEmptyField' => 0,
          'required' => (($objField->isKeyField == 1) ? true : false)
        ));

      $objElement->addPrefixPath('Form_Decorator', GLOBAL_ROOT_PATH.'library/massiveart/generic/forms/decorators/', 'decorator');
      $objElement->setDecorators(array($objField->decorator));

      if(count($objField->getProperties()) > 0){
        foreach($objField->getProperties() as $strProperty => $mixedPropertyValue){
          if(in_array($strProperty, GenericForm::$FIELD_PROPERTIES_TO_IMPART)){
            $objElement->$strProperty = $mixedPropertyValue;
          }
        }
      }

      $objDecorator = $objElement->getDecorator($objField->decorator);
      $objDecorator->setElement($objElement);
      echo $objDecorator->buildInput();
    }

    $this->_helper->viewRenderer->setNoRender();
  }

  /**
   * getForm
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  private function getForm($intActionType = null){
    $this->core->logger->debug('cms->controllers->PageController->getForm('.$intActionType.')');

    try{

      $strFormId = $this->objRequest->getParam("formId");
      $intTemplateId = $this->objRequest->getParam("templateId");

      /**
       * if there is now formId, try to load form template
       */
      if($strFormId == ''){
        if($intTemplateId != ''){
          /**
           * get files
           */
          $this->getModelTemplates();
          $objTemplateData = $this->objModelTemplates->loadTemplateById($intTemplateId);

          if(count($objTemplateData) == 1){
            $objTemplate = $objTemplateData->current();

            /**
             * set form id from template
             */
            $strFormId = $objTemplate->genericFormId;
          }else{
            throw new Exception('Not able to create a form, because there is no form id!');
          }
        }else{
          throw new Exception('Not able to create a form, because there is no form id!');
        }
      }

      $intFormVersion = ($this->objRequest->getParam("formVersion") != '') ? $this->objRequest->getParam("formVersion") : null;
      $intElementId = ($this->objRequest->getParam("id") != '') ? $this->objRequest->getParam("id") : null;

      $objFormHandler = FormHandler::getInstance();
      $objFormHandler->setFormId($strFormId);
      $objFormHandler->setTemplateId($intTemplateId);
      $objFormHandler->setFormVersion($intFormVersion);
      $objFormHandler->setActionType($intActionType);
      $objFormHandler->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
      $objFormHandler->setFormLanguageId(Zend_Auth::getInstance()->getIdentity()->languageId);
      $objFormHandler->setElementId($intElementId);

      $this->objForm = $objFormHandler->getGenericForm();

      /**
       * set page default & specific form values
       */
      $this->objForm->Setup()->setCreatorId((($this->objRequest->getParam("creator") != '') ? $this->objRequest->getParam("creator") : Zend_Auth::getInstance()->getIdentity()->id));
      $this->objForm->Setup()->setStatusId((($this->objRequest->getParam("idStatus") != '') ? $this->objRequest->getParam("idStatus") : $this->core->sysConfig->form->status->default));
      $this->objForm->Setup()->setRootLevelId((($this->objRequest->getParam("rootLevelId") != '') ? $this->objRequest->getParam("rootLevelId") : null));
      $this->objForm->Setup()->setParentId((($this->objRequest->getParam("parentFolderId") != '') ? $this->objRequest->getParam("parentFolderId") : null));
      $this->objForm->Setup()->setIsStartElement((($this->objRequest->getParam("isStartPage") != '') ? $this->objRequest->getParam("isStartPage") : 0));
      $this->objForm->Setup()->setPublishDate((($this->objRequest->getParam("publishDate") != '') ? $this->objRequest->getParam("publishDate") : date('Y-m-d H:i:s')));
      $this->objForm->Setup()->setShowInNavigation((($this->objRequest->getParam("showInNavigation") != '') ? $this->objRequest->getParam("showInNavigation") : 0));
      $this->objForm->Setup()->setElementTypeId((($this->objRequest->getParam("pageTypeId") != '') ? $this->objRequest->getParam("pageTypeId") : $this->core->sysConfig->page_types->page->id));
      $this->objForm->Setup()->setParentTypeId((($this->objRequest->getParam("parentTypeId") != '') ? $this->objRequest->getParam("parentTypeId") : (($this->objRequest->getParam("parentFolderId") != '') ? $this->core->sysConfig->parent_types->folder : $this->core->sysConfig->parent_types->rootlevel)));
      $this->objForm->Setup()->setModelSubPath('cms/models/');

      /**
       * add currlevel hidden field
       */
      $this->objForm->addElement('hidden', 'currLevel', array('value' => $this->objRequest->getParam("currLevel"), 'decorators' => array('Hidden'), 'ignore' => true));

      /**
       * add elementTye hidden field (folder, page, ...)
       */
      $this->objForm->addElement('hidden', 'elementType', array('value' => $this->objRequest->getParam("elementType"), 'decorators' => array('Hidden'), 'ignore' => true));

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
      exit();
    }
  }

  /**
   * addPageSpecificFormElements
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function addPageSpecificFormElements(){
    if(is_object($this->objForm) && $this->objForm instanceof GenericForm){
      /**
       * add page specific hidden fields
       */
      $this->objForm->addElement('hidden', 'creator', array('value' => $this->objForm->Setup()->getCreatorId(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'idStatus', array('value' => $this->objForm->Setup()->getStatusId(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'rootLevelId', array('value' => $this->objForm->Setup()->getRootLevelId(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'rootLevelTypeId', array('value' => $this->objForm->Setup()->getRootLevelTypeId(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'parentFolderId', array('value' => $this->objForm->Setup()->getParentId(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'pageTypeId', array('value' => $this->objForm->Setup()->getElementTypeId(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'isStartPage', array('value' => $this->objForm->Setup()->getIsStartElement(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'publishDate', array('value' => $this->objForm->Setup()->getPublishDate('Y-m-d H:i:s'), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'showInNavigation', array('value' => $this->objForm->Setup()->getShowInNavigation(), 'decorators' => array('Hidden')));
      $this->objForm->addElement('hidden', 'parentTypeId', array('value' => $this->objForm->Setup()->getParentTypeId(), 'decorators' => array('Hidden')));
    }
  }

  /**
   * getModelPages
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelPages(){
    if (null === $this->objModelPages) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Pages.php';
      $this->objModelPages = new Model_Pages();
      $this->objModelPages->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
    }

    return $this->objModelPages;
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
      $this->objModelFolders->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
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
      $this->objModelFiles->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
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
      $this->objModelContacts->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
    }

    return $this->objModelContacts;
  }

  /**
   * getModelTemplates
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelTemplates(){
    if (null === $this->objModelTemplates) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Templates.php';
      $this->objModelTemplates = new Model_Templates();
    }

    return $this->objModelTemplates;
  }

  /**
   * getModelGenericForm
   * @return Model_GenericForms
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelGenericForm(){
    if (null === $this->objModelGenericForm) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/GenericForms.php';
      $this->objModelGenericForm = new Model_GenericForms();
      $this->objModelGenericForm->setLanguageId($this->objRequest->getParam("languageId", $this->core->sysConfig->languages->default->id));
    }

    return $this->objModelGenericForm;
  }
}

?>
