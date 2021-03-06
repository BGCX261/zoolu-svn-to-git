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
 * @package    library.massiveart.generic.forms
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * GenericForm
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-10-23: Cornelius Hansjakob
 * 1.1, 2009-01-16: Thomas Schedler (split into types -> /types/generic.form.type.page.class.php
 *                                                       /types/generic.form.type.folder.class.php)
 * 1.2, 2008-01-19: Thomas Schedler - changed structure and added generic setup object
 * 1.3, 2009-07-22: Florian Mathis, added generic subform and tab content logic
 * 1.4, 2009-07-23: Thomas Schedler
 * 1.5, 2009-07-28: Daniel Rotter - changed the used plugin loader to our own
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.2
 * @package massiveart.generic.forms
 * @subpackage GenericForm
 */

require(dirname(__FILE__).'/generic.subform.class.php');

class GenericForm extends Zend_Form {

	/**
	 * @var Core
	 */
	protected $core;

	const SUB_FROM_ID_PREFIX = 'Tab_';

	/**
   * @var GenericSetup
   */
	protected $setup;
  /**
   * property of the generic setup object
   * @return GenericSetup $setup
   */
  public function Setup(){
    return $this->setup;
  }

  public static $FIELD_PROPERTIES_TO_IMPART = array('tagIds',
                                                    'isRegionTitle',
                                                    'strLinkedPageId',
                                                    'intLinkedPageVersion',
                                                    'strLinkedPageTitle',
                                                    'strLinkedPageUrl',
                                                    'intLinkedPageId',
                                                    'strLinkedPageBreadcrumb',
                                                    'intVideoTypeId',
                                                    'strVideoUserId',
                                                    'strVideoThumb');

	/**
	 * @var Model_GenericForms
	 */
	protected $objModelGenericForm;

  /**
   * @var GenericDataTypeAbstract
   */
  protected $objDataType;

  /**
   * field sub form
   */
  protected $arrFieldSubForm = array();

	/**
	 * Constructor
	 */
	public function __construct($options = null){
		$this->core = Zend_Registry::get('Core');

    /**
		 * Zend_Form
		 */
	  parent::__construct($options);
	  
	  /**
	   * Use our own PluginLoader
	   */
	  $objLoader = new PluginLoader();
		$objLoader->setPluginLoader($this->getPluginLoader(PluginLoader::TYPE_FORM_ELEMENT));
		$objLoader->setPluginType(PluginLoader::TYPE_FORM_ELEMENT);
		$this->setPluginLoader($objLoader, PluginLoader::TYPE_FORM_ELEMENT);
	  

	  /**
	   * new generic setup object
	   */
		$this->setup = new GenericSetup();

		/**
     * clear all decorators
     */
    $this->clearDecorators();

    /**
     * add standard decorators
     */
    $this->addDecorator('TabContainer');
    $this->addDecorator('FormElements');
    $this->addDecorator('Form');

    /**
     * add form prefix path
     */
    $this->addPrefixPath('Form_Decorator', dirname(__FILE__).'/decorators/', 'decorator');

    /**
     * add prefix path for own elements
     */
    //$this->addPrefixPath('Form_Element', dirname(__FILE__).'/elements/', 'element');

    /**
     * elements prefixes
     */
    $this->addElementPrefixPath('Form_Decorator', dirname(__FILE__).'/decorators/', 'decorator');

    /**
     * regions prefixes
     */
    $this->addDisplayGroupPrefixPath('Form_Decorator', dirname(__FILE__).'/decorators/');

    $this->setAttrib('id', 'genForm');
    $this->setAttrib('onsubmit', 'return false;');
	}

	/**
   * initDataTypeObject
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
	public function initDataTypeObject(){

    $this->objDataType = GenericSetup::getDataTypeObject($this->setup->getFormTypeId());

    if($this->objDataType instanceof GenericDataTypeAbstract){
      $this->objDataType->setGenericSetup($this->setup);
    }
	}

	/**
   * loadFormData
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
	public function loadFormData(){
	  $this->core->logger->debug('massiveart->generic->forms->GenericForm->loadFormData()');

	  try{

	    if($this->objDataType instanceof GenericDataTypeAbstract){
	      $this->objDataType->load();
	    }else{
	      throw new Exception('Not able to load form data, because there is no data type object!');
	    }

	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
	}

	/**
   * saveFormData
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function saveFormData(){
    $this->core->logger->debug('massiveart->generic->forms->GenericForm->saveFormData()');

    try{

      if($this->objDataType instanceof GenericDataTypeAbstract){
        return $this->objDataType->save();
      }else{
        throw new Exception('Not able to save form data, because there is no data type object!');
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * updateSpecialFieldValues
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function updateSpecialFieldValues(){
    try{
      if(count($this->setup->SpecialFields()) > 0){
        foreach($this->setup->SpecialFields() as $objField){
          $this->getSubForm(self::SUB_FROM_ID_PREFIX.$this->arrFieldSubForm[$objField->name])->getElement($objField->name)->setValue($objField->getValue());

          if(count($objField->getProperties()) > 0){
            foreach($objField->getProperties() as $strProperty => $mixedPropertyValue){
              if(in_array($strProperty, self::$FIELD_PROPERTIES_TO_IMPART)){
                $this->getSubForm(self::SUB_FROM_ID_PREFIX.$this->arrFieldSubForm[$objField->name])->getElement($objField->name)->$strProperty = $mixedPropertyValue;
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
	 * prepareForm
	 * @author Cornelius Hansjakob <cha@massiveart.com>
	 * @version 1.2
	 */
	public function prepareForm(){
		$this->core->logger->debug('massiveart->generic->forms->GenericForm->prepareForm()');
		try{

			/**
			 * add basic hidden fields
			 */
			$this->addElement('hidden', 'id', array('value' => $this->setup->getElementId(), 'decorators' => array('Hidden')));
			$this->addElement('hidden', 'formId', array('value' => $this->setup->getFormId(), 'decorators' => array('Hidden')));
			$this->addElement('hidden', 'formVersion', array('value' => $this->setup->getFormVersion(), 'decorators' => array('Hidden')));
			$this->addElement('hidden', 'formTypeId', array('value' => $this->setup->getFormTypeId(), 'decorators' => array('Hidden')));
			$this->addElement('hidden', 'formType', array('value' => $this->setup->getFormType(), 'decorators' => array('Hidden')));
			$this->addElement('hidden', 'templateId', array('value' => $this->setup->getTemplateId(), 'decorators' => array('Hidden')));
			$this->addElement('hidden', 'languageId', array('value' => $this->setup->getLanguageId(), 'decorators' => array('Hidden')));

			$arrRegionTitles = array();

			/**
       * go through the tabs and add to the form
       */
			$intSubFormCounter = 0;
      foreach($this->setup->Tabs() as $objTab){
        $intSubFormCounter++;

        $objSubForm = new GenericSubForm();
        $objSubForm->setGenericForm($this);
        $objSubForm->setId($objTab->getTabId());
        $objSubForm->setTitle($objTab->getTabTitle());
        $objSubForm->setHide((($intSubFormCounter == 1) ? false : true));

  			/**
  			 * go through the regions and add to the form
  			 */
  			foreach ($objTab->Regions() as $objRegion) {

  			  $arrRegionFieldElements = array();

  			  if($objRegion->getRegionIsMultiply() == true){

  			    if(count($objRegion->RegionInstanceIds()) > 0){
              foreach($objRegion->RegionInstanceIds() as $intRegionInstanceId){

                /**
                 * go through fields of the region
                 */
                foreach ($objRegion->getFields() as $objField) {
                  $objSubForm->addField($objField, $objRegion->getRegionId(), '_'.$intRegionInstanceId, $intRegionInstanceId);

                  if($objField->isRegionTitle == 1){
                    $arrRegionTitles[$objRegion->getRegionId().'_'.$intRegionInstanceId] = $objField->getInstanceValue($intRegionInstanceId);
                  }

                  /**
                   * add field to region
                   */
                  if(!array_key_exists($objRegion->getRegionId().'_'.$intRegionInstanceId, $arrRegionFieldElements)){
                    $arrRegionFieldElements[$objRegion->getRegionId().'_'.$intRegionInstanceId] = array();
                  }
                  array_push($arrRegionFieldElements[$objRegion->getRegionId().'_'.$intRegionInstanceId], $objField->name.'_'.$intRegionInstanceId);
                }
              }
            }else{
              $intRegionInstanceId = 1;
              $objRegion->addRegionInstanceId($intRegionInstanceId);

              /**
               * go through fields of the region
               */
              foreach ($objRegion->getFields() as $objField) {
                $objSubForm->addField($objField, $objRegion->getRegionId(), '_'.$intRegionInstanceId);

                /**
                 * add field to region
                 */
                if(!array_key_exists($objRegion->getRegionId().'_'.$intRegionInstanceId, $arrRegionFieldElements)){
                  $arrRegionFieldElements[$objRegion->getRegionId().'_'.$intRegionInstanceId] = array();
                }
                array_push($arrRegionFieldElements[$objRegion->getRegionId().'_'.$intRegionInstanceId], $objField->name.'_'.$intRegionInstanceId);
              }
            }

  			    /**
             * go through fields of the region
             */
            foreach ($objRegion->getFields() as $objField) {
              $objSubForm->addField($objField, $objRegion->getRegionId(), '_REPLACE_n', null, true);
              if(!array_key_exists($objRegion->getRegionId().'_REPLACE_n', $arrRegionFieldElements)){
                $arrRegionFieldElements[$objRegion->getRegionId().'_REPLACE_n'] = array();
              }
              array_push($arrRegionFieldElements[$objRegion->getRegionId().'_REPLACE_n'], $objField->name.'_REPLACE_n');
            }

  			  }else{
  			    /**
             * go through fields of the region
             */
            foreach ($objRegion->getFields() as $objField) {
    			    $objSubForm->addField($objField, $objRegion->getRegionId());

              /**
               * add field to region
               */
              array_push($arrRegionFieldElements, $objField->name);
            }
  				}

  				/**
           * add region to form
           */
          $strRegionStyle = '';
          if($objRegion->getRegionIsCollapsed() == false){
            $strRegionStyle = 'display:none;';
          }

          if($objRegion->getRegionIsMultiply() == true){

            $strRegionInstances = '';
            $intRegionCounter = 0;

            foreach($objRegion->RegionInstanceIds() as $intRegionInstanceId){
              $intRegionCounter++;
              $objSubForm->addDisplayGroup($arrRegionFieldElements[$objRegion->getRegionId().'_'.$intRegionInstanceId], $objRegion->getRegionId().'_'.$intRegionInstanceId, array(
                'columns' =>  $objRegion->getRegionCols(),
                'regionTypeId' => $objRegion->getRegionTypeId(),
                'collapsable' => (($objRegion->getRegionCollapsable() == true) ? 1 : 0),
                'position' => $objRegion->getRegionPosition(),
                'regionCounter' => $intRegionCounter,
                'style' => $strRegionStyle,
                'regionId' => $objRegion->getRegionId(),
                'regionExt' => $intRegionInstanceId,
                'isMultiply' => true,
                'regionTitle' => ((array_key_exists($objRegion->getRegionId().'_'.$intRegionInstanceId, $arrRegionTitles)) ? $arrRegionTitles[$objRegion->getRegionId().'_'.$intRegionInstanceId] : '')
              ));

              $objSubForm->getDisplayGroup($objRegion->getRegionId().'_'.$intRegionInstanceId)->setLegend($objRegion->getRegionTitle());
              $objSubForm->getDisplayGroup($objRegion->getRegionId().'_'.$intRegionInstanceId)->setDecorators(array('FormElements','Region'));

              $strRegionInstances .= '['.$intRegionInstanceId.']';
            }

            $objSubForm->addDisplayGroup($arrRegionFieldElements[$objRegion->getRegionId().'_REPLACE_n'], $objRegion->getRegionId().'_REPLACE_n', array(
              'columns' =>  $objRegion->getRegionCols(),
              'regionTypeId' => $objRegion->getRegionTypeId(),
              'collapsable' => (($objRegion->getRegionCollapsable() == true) ? 1 : 0),
              'position' => $objRegion->getRegionPosition(),
              'style' => $strRegionStyle,
              'regionId' => $objRegion->getRegionId(),
              'regionExt' => 'REPLACE_n',
              'isMultiply' => true,
              'isEmptyWidget' => true
            ));

            $objSubForm->getDisplayGroup($objRegion->getRegionId().'_REPLACE_n')->setLegend($objRegion->getRegionTitle());
            $objSubForm->getDisplayGroup($objRegion->getRegionId().'_REPLACE_n')->setDecorators(array('FormElements','Region'));

            $objSubForm->addElement('hidden', 'Region_'.$objRegion->getRegionId().'_Instances', array('value' => $strRegionInstances, 'decorators' => array('Hidden')));
            $objSubForm->addElement('hidden', 'Region_'.$objRegion->getRegionId().'_Order', array('value' => '', 'decorators' => array('Hidden')));
          }else{
      			$objSubForm->addDisplayGroup($arrRegionFieldElements, $objRegion->getRegionId(), array(
              //'order' => $objRegion->getRegionOrder(),
      			  'columns' =>  $objRegion->getRegionCols(),
      			  'regionTypeId' => $objRegion->getRegionTypeId(),
              'collapsable' => (($objRegion->getRegionCollapsable() == true) ? 1 : 0),
              'position' => $objRegion->getRegionPosition(),
              'style' => $strRegionStyle
      			));

      			$objSubForm->getDisplayGroup($objRegion->getRegionId())->setLegend($objRegion->getRegionTitle());
      			$objSubForm->getDisplayGroup($objRegion->getRegionId())->setDecorators(array('FormElements','Region'));
          }
  			}

        $this->addSubForm($objSubForm, self::SUB_FROM_ID_PREFIX.$objSubForm->getId());
      }
		}catch (Exception $exc) {
			$this->core->logger->err($exc);
		}
	}

	/**
   * addField
   * @param GenericElementField $objField
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.2
   */
	protected function addField(GenericElementField &$objField, $intRegionId, $strNameExtension = '', $intRegionInstanceId = null, $blnEmpty = false){
	  try{
      $sqlStmt = array();
      $arrOptions = array();
      /**
       * get array options for select output if sqlSelect is in database
       */
      if($objField->sqlSelect != '' && $objField->sqlSelect){
      	$objReplacer = new Replacer();
      	$sqlSelect = $objReplacer->sqlReplacer($objField->sqlSelect, $this->setup->getLanguageId(), $this->setup->getRootLevelId());
      	$sqlStmt = $this->core->dbh->query($sqlSelect)->fetchAll();
      	if($objField->idFieldTypeGroup == GenericSetup::FIELD_TYPE_SELECT_ID) {
          $arrOptions[''] = 'Bitte wählen';
        }
        foreach($sqlStmt as $arrSql){
          if(array_key_exists('depth', $arrSql)){
            $arrOptions[$arrSql['id']] = array('title' => $arrSql['title'],
                                               'depth' => $arrSql['depth']);
          }else{
            $arrOptions[$arrSql['id']] = $arrSql['title'];
          }
        }
      }

      if($objField->type == GenericSetup::FIELD_TYPE_TEMPLATE){
        $objField->defaultValue = $this->setup->getTemplateId();
      }

      if(!is_null($intRegionInstanceId)){
        $mixedValue = $objField->getInstanceValue($intRegionInstanceId);
      }else{
        $mixedValue = $objField->getValue();
      }

      if($blnEmpty == true){
        $mixedValue = null;
      }

      $strCssClass = '';
      if($objField->isKeyField){
        $strCssClass = ' keyfield';
      }

      /**
       * add field to form
       */
      $this->addElement($objField->type, $objField->name.$strNameExtension, array(
        'value' => $mixedValue,
        'label' => $objField->title,
        'description' => $objField->description,
        'decorators' => array($objField->decorator),
        'fieldId' => $objField->id,
        'columns' => $objField->columns,
        'class' => $objField->type.$strCssClass,
        'height' => $objField->height,
        'isGenericSaveField' => $objField->isSaveField,
        'isCoreField' => $objField->isCoreField,
        'MultiOptions' => $arrOptions,
        'LanguageId' => $this->setup->getLanguageId(),
        'isEmptyField' => (($blnEmpty == true) ? 1 : 0),
        'required' =>  (($objField->isKeyField == 1) ? true : false)
      ));

      $this->getElement($objField->name.$strNameExtension)->regionId = $intRegionId;
      $this->getElement($objField->name.$strNameExtension)->regionExtension = $strNameExtension;
      $this->getElement($objField->name.$strNameExtension)->formTypeId = $this->setup->getFormTypeId();

      if(count($objField->getProperties()) > 0){
        foreach($objField->getProperties() as $strProperty => $mixedPropertyValue){
          if(in_array($strProperty, self::$FIELD_PROPERTIES_TO_IMPART)){
            $this->getElement($objField->name.$strNameExtension)->$strProperty = $mixedPropertyValue;
          }
        }
      }

      /**
       * template specific addons
       */
	    if($objField->type == GenericSetup::FIELD_TYPE_TEMPLATE){
        $this->getElement($objField->name.$strNameExtension)->isStartPage = $this->Setup()->getIsStartElement(false);
        $this->getElement($objField->name.$strNameExtension)->intElementTypeId = $this->Setup()->getElementTypeId();
        $this->getElement($objField->name.$strNameExtension)->intParentTypeId = $this->Setup()->getParentTypeId();
      }

	  }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
	}

  /**
   * setGenericSetup
   * @param GenericSetup $objGenericSetup
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function setGenericSetup(GenericSetup &$objGenericSetup){
    $this->setup = $objGenericSetup;
  }

  /**
   * fieldAddedToSubform
   * @param integer $intSubFormId
   * @param string $strFieldName
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function fieldAddedToSubform($intSubFormId, $strFieldName){
    $this->arrFieldSubForm[$strFieldName] = $intSubFormId;
  }

	/**
	 * getModelGenericForm
	 * @author Cornelius Hansjakob <cha@massiveart.com>
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
		}

		return $this->objModelGenericForm;
	}
}

?>