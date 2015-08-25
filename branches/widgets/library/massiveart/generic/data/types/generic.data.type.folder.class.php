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
 * @package    library.massiveart.generic.data.types
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * GenericDataTypeFolder extends GenericDataTypeAbstract
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-01-16: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 * @package massiveart.generic.data.types
 * @subpackage GenericDataTypeFolder
 */

require_once(dirname(__FILE__).'/generic.data.type.abstract.class.php');

class GenericDataTypeFolder extends GenericDataTypeAbstract {

  /**
   * @var Model_Folders
   */
  protected $objModelFolders;

	/**
	 * save
	 * @author Thomas Schedler <tsh@massiveart.com>
	 * @version 1.0
	 */
	public function save(){
		$this->core->logger->debug('massiveart->generic->data->GenericDataTypeFolder->save()');
		try{

		  $this->getModelFolders()->setLanguageId($this->setup->getLanguageId());

			$intUserId = Zend_Auth::getInstance()->getIdentity()->id;

			/**
			 * add|edit|newVersion core and instance data
			 */
      switch($this->setup->getActionType()){
				case $this->core->sysConfig->generic->actions->add :

					$strFolderId = uniqid();
					$intFolderVersion = 1;
					$intSortPosition = GenericSetup::DEFAULT_SORT_POSITION;

					if($this->setup->getParentId() != '' && $this->setup->getParentId() > 0){
            $objNaviData = $this->getModelFolders()->loadChildNavigation($this->setup->getParentId());
          }else{
            $objNaviData = $this->getModelFolders()->loadRootNavigation($this->setup->getRootLevelId());
          }
          $intSortPosition = count($objNaviData);

					$arrMainData = array('idGenericForms'   => $this->setup->getGenFormId(),
                               'idFolderTypes'    => $this->core->sysConfig->folder_types->folder,
                               'folderId'         => $strFolderId,
                               'version'          => $intFolderVersion,
                               'sortPosition'     => $intSortPosition,
					                     'sortTimestamp'    => date('Y-m-d H:i:s'),
                               'idUsers'          => $intUserId,
                               'creator'          => $this->setup->getCreatorId(),
                               'created'          => date('Y-m-d H:i:s'),
                               'idStatus'         => $this->setup->getStatusId(),
					                     'isUrlFolder'      => $this->setup->getUrlFolder(),
					                     'showInNavigation' => $this->setup->getShowInNavigation());

					/**
           * add folder node to the "Nested Set Model"
           */
          $this->setup->setElementId($this->objModelFolders->addFolderNode($this->setup->getRootLevelId(),
                                                                           $this->setup->getParentId(),
                                                                           $arrMainData));

					$this->insertCoreData('folder', $strFolderId, $intFolderVersion);
					$this->insertInstanceData('folder', array('Id' => $strFolderId, 'Version' => $intFolderVersion));

					break;
				case $this->core->sysConfig->generic->actions->edit :

					$objSelect = $this->objModelFolders->getFolderTable()->select();
					$objSelect->from('folders', array('folderId', 'version'));
					$objSelect->where('id = ?', $this->setup->getElementId());

					$objRowSet = $this->objModelFolders->getFolderTable()->fetchAll($objSelect);

					if(count($objRowSet) == 1){
						$objFolder = $objRowSet->current();

						$strWhere = $this->objModelFolders->getFolderTable()->getAdapter()->quoteInto('folderId = ?', $objFolder->folderId);
						$strWhere .= $this->objModelFolders->getFolderTable()->getAdapter()->quoteInto(' AND version = ?', $objFolder->version);

						$this->core->logger->debug('save(): creator: '.$this->setup->getCreatorId().' - idStatus: '.$this->setup->getStatusId().' - isUrlFolder: '.$this->setup->getUrlFolder());

						$this->objModelFolders->getFolderTable()->update(array('idUsers'          => $intUserId,
      		                                                         'creator'          => $this->setup->getCreatorId(),
      		                                                         'idStatus'         => $this->setup->getStatusId(),
						                                                       'isUrlFolder'      => $this->setup->getUrlFolder(),
						                                                       'showInNavigation' => $this->setup->getShowInNavigation(),
						                                                       'changed'          => date('Y-m-d H:i:s')), $strWhere);

						$this->updateCoreData('folder', $objFolder->folderId, $objFolder->version);
						$this->updateInstanceData('folder', $objFolder->folderId, $objFolder->version);

					}
					break;
			}


			return $this->setup->getElementId();
		}catch (Exception $exc) {
			$this->core->logger->err($exc);
		}
	}

	/**
	 * load
	 * @author Thomas Schedler <tsh@massiveart.com>
	 * @version 1.0
	 */
	public function load(){
		$this->core->logger->debug('massiveart->generic->data->GenericDataTypeFolder->load()');
		try {

			$objFoldersData = $this->getModelFolders()->loadFolder($this->setup->getElementId());

			if(count($objFoldersData) > 0){
				$objFolderData = $objFoldersData->current();

				/**
				 * set some metainformations of current page to get them in the output
				 */
				$this->setup->setMetaInformation($objFolderData);
				$this->setup->setUrlFolder($objFolderData->isUrlFolder);

        $this->core->logger->debug('load(): creator: '.$this->setup->getCreatorId().' - idStatus: '.$this->setup->getStatusId().' - isUrlFolder: '.$this->setup->getUrlFolder());

        if(count($this->setup->CoreFields()) > 0){
					/**
					 * for each core field, try to select the secondary table
					 */
          foreach($this->setup->CoreFields() as $strField => $objField){

          	$objGenTable = $this->getModelGenericData()->getGenericTable('folder'.((substr($strField, strlen($strField) - 1) == 'y') ? ucfirst(rtrim($strField, 'y')).'ies' : ucfirst($strField).'s'));
						$objSelect = $objGenTable->select();

						$objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), array($strField));
						$objSelect->where('folderId = ?', $objFolderData->folderId);
						$objSelect->where('version = ?', $objFolderData->version);
						$objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

						$arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();

						foreach ($arrGenFormsData as $arrRowGenFormData) {
							foreach ($arrRowGenFormData as $column => $value) {
							if($column == $strField){
                  $objField->setValue($value);
                }else{
                  $objField->$column = $value;
                }
							}
						}
					}
				}

				if(count($this->setup->InstanceFields()) > 0){
					$objGenTable = $this->getModelGenericData()->getGenericTable('folder-'.$this->setup->getFormId().'-'.$this->setup->getFormVersion().'-Instances');
					$objSelect = $objGenTable->select();

					$arrSelectFields = array();

					/**
					 * for each instance field, add to select array data array
					 */
					foreach($this->setup->InstanceFields() as $strField => $objField){
						$arrSelectFields[] = $strField;
					}

					$objSelect->from($objGenTable->info(Zend_Db_Table_Abstract::NAME), $arrSelectFields);
					$objSelect->where('folderId = ?', $objFolderData->folderId);
					$objSelect->where('version = ?', $objFolderData->version);
          $objSelect->where('idLanguages = ?', $this->Setup()->getLanguageId());

					$arrGenFormsData = $objGenTable->fetchAll($objSelect)->toArray();

					foreach ($arrGenFormsData as $arrRowGenFormData) {
						foreach ($arrRowGenFormData as $column => $value) {
						  if(is_array(json_decode($value))){
                $this->setup->getInstanceField($column)->setValue(json_decode($value));
              }else{
                $this->setup->getInstanceField($column)->setValue($value);
              }
						}
					}
				}
			}
		}catch (Exception $exc) {
			$this->core->logger->err($exc);
		}

	}

  /**
   * getModelFolders
   * @return Model_Folders
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
    }

    return $this->objModelFolders;
  }
}

?>