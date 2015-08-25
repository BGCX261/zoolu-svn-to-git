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
 * @package    library.massiveart.generic.fields.Template.forms.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * Form_Helper_FormTemplate
 * 
 * Helper to generate a "template" element
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-11: Cornelius Hansjakob
 * 
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 * @package massiveart.forms.helpers
 * @subpackage Form_Helper_FormTemplate
 */

class Form_Helper_FormTemplate extends Zend_View_Helper_FormElement {
  
  /**
   * formTemplate
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @param string $name
   * @param string $value
   * @param array $attribs
   * @param mixed $options
   * @param Zend_Db_Table_Rowset $objTemplatesData
   * @version 1.0
   */
  public function formTemplate($name, $value = null, $attribs = null, $options = null, $objTemplatesData){
    $info = $this->_getInfo($name, $value, $attribs);
    extract($info); // name, value, attribs, options, listsep, disable
    
    // XHTML or HTML end tag
    $endTag = ' />';
    if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
      $endTag= '>';
    }
    
    $strSelectedTemplate = '';
    $strOtherTemplates = '';
    
    if(count($objTemplatesData)){
      foreach($objTemplatesData as $objTemplate){
        if($objTemplate->id == $value){
          $strSelectedTemplate = '
                    <div id="divCurrTemplate"><img src="/zoolu/images/templates/'.$objTemplate->thumbnail.'" width="160"/></div>
                    <div id="divDescTemplate">'.$this->view->escape($objTemplate->title).'</div>
          ';  
        }else{
          $strOtherTemplates .= '
                        <div id="divTemplate'.$objTemplate->id.'" class="tmpElement"><a href="#" onclick="myForm.changeTemplate('.$objTemplate->id.'); return false;" title="'.$this->view->escape($objTemplate->title).'"><img src="/zoolu/images/templates/'.$objTemplate->thumbnail.'" alt="'.$this->view->escape($objTemplate->title).'" width="160"/></a></div>';
        }
      }
    }
    
    // build the element
    $strOutput = '<div id="divTemplateChooser">
                    '.$strSelectedTemplate;
    
    if($strOtherTemplates != ''){
      $strOutput .= '
                    <div id="divChangeTemplate"><a href="#" onclick="myForm.toggleTemplateChooser(); return false;">Template &auml;ndern</a></div>
                    <div class="spacer1"></div>
                    <div id="divAllTemplates" style="display:none;">
                      <div>
                        '.$strOtherTemplates.'
                      </div>
                    </div>';
    }
    
    $strOutput .= '
                    <input type="hidden" value="'.$this->view->escape($value).'" id="'.$this->view->escape($id).'" name="'.$this->view->escape($name).'" '.$this->_htmlAttribs($attribs).$endTag.'      
                  </div>';

    return $strOutput;
  }
  
}

?>