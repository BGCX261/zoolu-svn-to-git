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
 * Media_FileController
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-21: Cornelius Hansjakob
 *  *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class Media_FileController extends AuthControllerAction  {

	/**
   * @var Model_Folders
   */
  protected $objModelFiles;

  /**
   * indexAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function indexAction(){

  }

  /**
   * geteditformAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function geteditformAction(){
    $this->core->logger->debug('media->controllers->FileController->geteditformAction()');

    if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()){

      $this->getModelFiles();

      $objRequest = $this->getRequest();
      $strFileIds = $objRequest->getParam('fileIds');
      $objFiles = $this->objModelFiles->loadFilesById($strFileIds);

      $this->view->assign('strEditFormAction', '/zoolu/media/file/edit');
      $this->view->assign('strFileIds', $strFileIds);
      $this->view->assign('objFiles', $objFiles);
    }

    $this->renderScript('file/form.phtml');
  }

  /**
   * editAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function editAction(){
    $this->core->logger->debug('media->controllers->FileController->editAction()');

    if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()){

    	$arrFormData = $this->getRequest()->getPost();

      $objFile = new File();
      $objFile->setFileDatas($arrFormData);
      $objFile->updateFileData();
    }

    /**
     * no rendering
     */
    $this->_helper->viewRenderer->setNoRender();
  }

  /**
   * deleteAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function deleteAction(){
    $this->core->logger->debug('media->controllers->FileController->deleteAction()');

    //FIXME where is the file delete ????

    if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()){

    	$this->getModelFiles();

      $objRequest = $this->getRequest();
      $strFileIds = $objRequest->getParam('fileIds');

      $this->objModelFiles->deleteFiles($strFileIds);
    }
  }

  /**
   * getModelFiles
   * @return Model_Files
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelFiles(){
    if (null === $this->objModelFiles) {
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Files.php';
      $this->objModelFiles = new Model_Files();
      $this->objModelFiles->setLanguageId(1); // TODO : get language id
    }

    return $this->objModelFiles;
  }

}

?>