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
 * @package    library.massiveart.generic.fields.Url.data.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * GenericDataHelperWidgetUrl
 *
 * Helper to save and load the "WidgetUrl" element
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-08-19: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 * @package massiveart.generic.data.helpers
 * @subpackage GenericDataHelper_WidgetUrl
 */

require_once(dirname(__FILE__).'/../../../../data/helpers/Abstract.php');

class GenericDataHelper_WidgetUrl extends GenericDataHelperAbstract  {

  /**
   * @var Model
   */
  private $objModelWidgets;

  /**
   * @var Zend_Db_Table_Rowset_Abstract
   */
  private $objUrlReplacers;

  private $strUrl;

  /**
   * save()
   * @param integer $intElementId
   * @param string $strType
   * @param string $strElementId
   * @param integet $intVersion
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function save($intElementId, $strType, $strElementId = null, $intVersion = null){
    try{
      $this->getModelWidgets();
      $objWidgetData = $this->objModelWidgets->loadWidgetInstance($strElementId);
      
      if(count($objWidgetData) > 0){
        $objWidget = $objWidgetData->current();
        $objUrlData = $this->objModelWidgets->loadWidgetUrl($strElementId, $objWidget->version);

        if(count($objUrlData) > 0){
          $objUrl = $objUrlData->current();
          $this->objElement->setValue('/'.strtolower($objUrl->languageCode).'/'.$objUrl->url);
        }else{
          $this->strUrl = '';
					
          $objParentFoldersData = $this->objModelWidgets->loadParentFolders($strElementId);
          if(count($objParentFoldersData) > 0){
            foreach($objParentFoldersData as $objParentFolder){
              if($objParentFolder->isUrlFolder == 1){
                $this->strUrl = $this->makeUrlConform($objParentFolder->title).'/'.$this->strUrl;
              }
            }
          }
          $objFieldData = $this->objElement->Setup()->getModelGenericForm()->loadFieldsAndRegionsByFormId($this->objElement->Setup()->getGenFormId());
          //->loadFieldsWithPropery($this->core->sysConfig->fields->properties->url_field, $this->objElement->Setup()->getGenFormId());
          if(count($objFieldData) > 0){          	
            foreach($objFieldData as $objField){
              if($this->objElement->Setup()->getRegion($objField->regionId)->getField($objField->name)->getValue() != ''){
                $this->strUrl .= $this->makeUrlConform($this->objElement->Setup()->getRegion($objField->regionId)->getField($objField->name)->getValue());
                break;
              }
            }
          }  

          $this->strUrl .= '/';

          $this->strUrl = $this->checkUrlUniqueness($this->strUrl);          
          $this->objModelWidgets->insertWidgetUrl($this->strUrl, $strElementId, $objWidget->version);

          $objUrlData = $this->objModelWidgets->loadWidgetUrl($strElementId, $objWidget->version);
          if(count($objUrlData) > 0){
            $objUrl = $objUrlData->current();
            $this->objElement->setValue('/'.strtolower($objUrl->languageCode).'/'.$objUrl->url);
          }
        }
      }

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * load()
   * @param integer $intElementId
   * @param string $strType
   * @param string $strElementId
   * @param integet $intVersion
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function load($intElementId, $strType, $strElementId = null, $intVersion = null){
    try{
      $this->getModelWidgets();

      $objUrlData = $this->objModelWidgets->loadWidgetUrl($strElementId, $intVersion);

      if(count($objUrlData) > 0){
        $objUrl = $objUrlData->current();
        $this->objElement->setValue('/'.strtolower($objUrl->languageCode).'/'.$objUrl->url);
      }

    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }

  /**
   * makeUrlConform()
   * @param string $strUrlPart
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  private function makeUrlConform($strUrlPart){

    $this->getUrlReplacers();
    $strUrlPart = strtolower($strUrlPart);

    if(count($this->objUrlReplacers) > 0){
      foreach($this->objUrlReplacers as $objUrlReplacer){
        $strUrlPart = str_replace(utf8_encode($objUrlReplacer->from), $objUrlReplacer->to, $strUrlPart);
      }
    }

    $strUrlPart = strtolower($strUrlPart);
    $strUrlPart = urlencode(preg_replace('/([^A-za-z0-9\s-_])/', '_', $strUrlPart));
    $strUrlPart = str_replace('+', '-', $strUrlPart);

    return $strUrlPart;
  }

  /**
   * getUrlReplacers()
   * @param string $strUrlPart
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  private function getUrlReplacers(){
    if($this->objUrlReplacers === null) {
      $this->objUrlReplacers = $this->getModelWidgets()->loadUrlReplacers();
    }
  }

  /**
   * checkUrlUniqueness()
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  private function checkUrlUniqueness($strUrl, $intUrlAddon = 0){
    $this->getModelWidgets();

    $strNewUrl = ($intUrlAddon > 0) ? $strUrl.'-'.$intUrlAddon : $strUrl;
    $objWidgetUrlsData = $this->objModelWidgets->loadWidgetByUrl($this->objElement->Setup()->getRootLevelId(), $strNewUrl);

    if(count($objWidgetUrlsData) > 0){
      return $this->checkUrlUniqueness($strUrl, $intUrlAddon + 1);
    }else{
      return $strNewUrl;
    }
  }

  /**
   * getModelWidgets
   * @return Model_Widgets
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelWidgets(){
    if (null === $this->objModelWidgets) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Widgets.php';
      $this->objModelWidgets = new Model_Widgets();
      $this->objModelWidgets->setLanguageId($this->objElement->Setup()->getLanguageId());
    }

    return $this->objModelWidgets;
  }
}
?>