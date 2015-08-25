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
 * @package    library.massiveart.documents
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Document Class extends File
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-27: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 * @package massiveart.documents
 * @subpackage Document
 */

class Document extends File {
          
  public function __construct(){
    parent::__construct();
    $this->blnIsDoc = true;
  }
  
  /**
   * upload
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function upload(){
    $this->core->logger->debug('massiveart.documents.Document->upload()');
    
    try{
      if(isset($_FILES[$this->_FILE_NAME]) && $_FILES[$this->_FILE_NAME]['name'] != ''){        
        $arrDocument = $_FILES[$this->_FILE_NAME];       
        
        /**
         * first check DocumentUploadPath
         */
        $this->checkUploadPath();
        
        $arrFileInfos = pathinfo($arrDocument['name']);
        $this->strExtension = strtolower($arrFileInfos['extension']);
        $this->strTitle = $arrFileInfos['filename'];
        $this->dblSize = $arrDocument['size'];
        $this->strMimeType = $arrDocument['type'];
        
        if(is_uploaded_file($arrDocument['tmp_name'])){
        	/**
	         * first check DocumentPublicPath
	         */
	        $this->checkPublicFilePath();
	        copy($arrDocument['tmp_name'], $this->getPublicFilePath().$this->strFileId.'.'.$this->strExtension);
        	move_uploaded_file($arrDocument['tmp_name'], $this->getUploadPath().$this->strFileId.'.'.$this->strExtension);
         
        }        
      }     
    }catch(Exception $exc){
      $this->core->logger->err($exc);
    }
  }

  /**
   * checkPublicFilePath
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function checkPublicFilePath(){
    $this->core->logger->debug('massiveart.documents.Document->checkPublicFilePath('.$this->getPublicFilePath().')');

    if (!is_dir($this->getPublicFilePath())){
      mkdir($this->getPublicFilePath(), 0775, true);
    }
  }
}

?>