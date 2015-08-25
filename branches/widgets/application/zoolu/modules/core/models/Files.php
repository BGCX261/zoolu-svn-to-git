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
 * @package    application.zoolu.modules.core.models
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Model_Files
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-10: Thomas Schedler
 * 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class Model_Files {
  
  private $intLanguageId;
  
  /**
   * @var Model_Table_Files
   */
  protected $objFileTable;
  
  /**
   * @var Model_Table_FileTitles
   */
  protected $objFileTitleTable;
  
  /**
   * @var Model_Table_FileAttributes
   */
  protected $objFileAttributeTable;
    
  /**
   * @var Core
   */
  private $core;  
  
  /**
   * Constructor 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }
  
  /**
   * loadFiles 
   * @param integer $intFolderId
   * @param integer $intLimitNumber = -1
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function loadFiles($intFolderId, $intLimitNumber = -1){
    $this->core->logger->debug('core->models->Model_Files->loadFiles('.$intFolderId.','.$intLimitNumber.')');
    
    try{
	    $this->getFileTable();
	    
	    $objSelect = $this->objFileTable->select();   
	    $objSelect->setIntegrityCheck(false);
	    
	    /**
	     * SELECT files.id, files.fileId, files.filename, files.isImage, fileAttributes.xDim, fileAttributes.yDim, fileTitles.title, fileTitles.description,
	     *  CONCAT(users.fname, ' ', users.sname) AS creator, files.created, files.extension, files.mimeType
	     * FROM files
	     * LEFT JOIN fileAttributes ON fileAttributes.idFiles = files.id
	     * LEFT JOIN fileTitles ON fileTitles.idFiles = files.id AND fileTitles.idLanguages = ?
	     * INNER JOIN users ON users.id = files.creator  
	     * WHERE files.idParent = ?
	     */
	    $objSelect->from('files', array('id', 'fileId', 'idParent', 'idParentTypes', 'filename', 'isImage', 'created', 'extension', 'mimeType'));
	    $objSelect->joinLeft('fileAttributes', 'fileAttributes.idFiles = files.id', array('xDim', 'yDim'));
	    $objSelect->joinLeft('fileTitles', 'fileTitles.idFiles = files.id AND fileTitles.idLanguages = '.$this->intLanguageId, array('title', 'description'));
	    $objSelect->join('users', 'users.id = files.creator', array('CONCAT(users.fname, \' \', users.sname) AS creator'));
	    if($intFolderId != ''){
	      $objSelect->where('idParent = ?', $intFolderId);	
	    }
	    if($intLimitNumber != -1 && $intLimitNumber != ''){
	      $objSelect->order('files.created DESC');
	      $objSelect->order('files.id DESC');
	    	$objSelect->limit($intLimitNumber);	
	    }
	    
	    return $this->objFileTable->fetchAll($objSelect); 
	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }  	
  }
  
  /**
   * loadFilesById 
   * @param string $strFileIds
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function loadFilesById($strFileIds){
    $this->core->logger->debug('core->models->Model_Files->loadFilesById('.$strFileIds.')');
    try{
	    $this->getFileTable();
	    
	    $strTmpFileIds = trim($strFileIds, '[]');
	    $arrFileIds = array();
	    $arrFileIds = split('\]\[', $strTmpFileIds);
	    
	    $objSelect = $this->objFileTable->select();   
	    $objSelect->setIntegrityCheck(false);
	    
	    /**
	     * SELECT files.id, files.fileId, files.filename, files.isImage, fileAttributes.xDim, fileAttributes.yDim, fileTitles.title, fileTitles.description,
	     *  CONCAT(users.fname, ' ', users.sname) AS creator, files.created, files.extension, files.mimeType
	     * FROM files
	     * LEFT JOIN fileAttributes ON fileAttributes.idFiles = files.id
	     * LEFT JOIN fileTitles ON fileTitles.idFiles = files.id AND fileTitles.idLanguages = ?
	     * INNER JOIN users ON users.id = files.creator  
	     * WHERE files.id = ? OR files.id = ? OR ...
	     */
	    
	    if(count($arrFileIds) > 0){
	      $strIds = '';
	      foreach($arrFileIds as $intFileId){
	        $strIds .= $intFileId.',';
	      }
	    	
	    	$objSelect->from('files', array('id', 'fileId', 'filename', 'isImage', 'created', 'extension', 'mimeType', 'size'));
	      $objSelect->joinLeft('fileAttributes', 'fileAttributes.idFiles = files.id', array('xDim', 'yDim'));
	      $objSelect->joinLeft('fileTitles', 'fileTitles.idFiles = files.id AND fileTitles.idLanguages = '.$this->intLanguageId, array('title', 'description', 'idLanguages'));
	      $objSelect->join('users', 'users.id = files.creator', array('CONCAT(users.fname, \' \', users.sname) AS creator'));  	
	      $objSelect->where('files.id IN ('.trim($strIds, ',').')');      
	      
	      return $this->objFileTable->fetchAll($objSelect);
	    }
	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }  
  }
  
  public function getAllImageFiles(){
  	$this->core->logger->debug('core->models->Model_Files->getAllImageFiles()');    
    try{
      $this->getFileTable();
      
      $objSelect = $this->objFileTable->select();   
      $objSelect->setIntegrityCheck(false);
      
      /**
       * SELECT files.id, files.fileId, files.filename, files.created, files.extension, files.mimeType, files.size, fileAttributes.xDim, fileAttributes.yDim
       * FROM files
       *  LEFT JOIN fileAttributes ON
       *    fileAttributes.idFiles = files.id
       * WHERE files.isImage = 1
       */
      
      $objSelect->from('files', array('id', 'fileId', 'filename', 'created', 'extension', 'mimeType', 'size'));
      $objSelect->joinLeft('fileAttributes', 'fileAttributes.idFiles = files.id', array('xDim', 'yDim'));   
      $objSelect->where('files.isImage = 1');
      
      return $this->objFileTable->fetchAll($objSelect);
    	
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    } 
  }
  
  /**
   * deleteFiles
   * @param string $strFiledIds
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function deleteFiles($strFileIds){
    $this->core->logger->debug('core->models->Model_Files->deleteFiles('.$strFileIds.')');  	
    try{
	  	
    	$this->getFileTable();
	  	
	  	$strTmpFileIds = trim($strFileIds, '[]');
	    $arrFileIds = array();
	    $arrFileIds = split('\]\[', $strTmpFileIds);
	    
	    $strWhere = '';
	    $intCounter = 0;
	    
	    if(count($arrFileIds) > 0){
	    	foreach($arrFileIds as $intFileId){
	    		if($intFileId != ''){
	    		  $intCounter++;
	    		  if($intCounter == 1){
	    			  $strWhere .= $this->objFileTable->getAdapter()->quoteInto('id = ?', $intFileId);
	    		  }else{
	    		  	$strWhere .= $this->objFileTable->getAdapter()->quoteInto(' OR id = ?', $intFileId);
	    		  }	
	    		}
	    	}
	    }
	    
	    /**
	     * delete files
	     */
	    if($strWhere != ''){
	      return $this->objFileTable->delete($strWhere);	
	    }
	    return false;
	    
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }    
  }
    
  /**
   * getFileTable 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFileTable(){
    
    if($this->objFileTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/Files.php';
      $this->objFileTable = new Model_Table_Files();
    }
    
    return $this->objFileTable;
  }
  
  /**
   * getFileTitleTable 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFileTitleTable(){
    
    if($this->objFileTitleTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/FileTitles.php';
      $this->objFileTitleTable = new Model_Table_FileTitles();
    }
    
    return $this->objFileTitleTable;
  }
  
  /**
   * getFileAttributeTable 
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function getFileAttributeTable(){
    
    if($this->objFileAttributeTable === null){
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/tables/FileAttributes.php';
      $this->objFileAttributeTable = new Model_Table_FileAttributes();
    }
    
    return $this->objFileAttributeTable;
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