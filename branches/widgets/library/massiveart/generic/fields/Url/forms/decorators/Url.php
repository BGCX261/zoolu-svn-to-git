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
 * @package    library.massiveart.generic.fields.Url.forms.decorators
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * Form_Decorator_Url
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-06: Thomas Schedler
 * 
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class Form_Decorator_Url extends Zend_Form_Decorator_Abstract {
  
  /**
   * @var Core
   */
  private $core;
  
  /**
   * Constructor 
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function __construct($options = null){        
    $this->core = Zend_Registry::get('Core');
    parent::__construct($options);
  } 
  
  /**
   * buildLabel
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function buildLabel(){
    
    $element = $this->getElement();
    $label = $element->getLabel();
    
    if (empty($label)){
      return '';
    }
    
    if ($element->isRequired()) {
      $label .= ' *';
    }
    
    return $element->getView()->formLabel($element->getName(), $label, array('class' => 'fieldtitle')).'<br/>';
  }
  
  /**
   * buildDescription
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function buildDescription(){
    $element = $this->getElement();
    $desc    = $element->getDescription();
    
    if (empty($desc)){
      return '';
    }
    
    return '<div class="description">'.$desc.'</div>';
  }
  
  /**
   * buildUrl
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function buildUrl(){
  	
    $element = $this->getElement();
    $helper  = $element->helper;
            
    $strOutput = $element->getView()->$helper($element->getName(), $element->getValue(), $element->getAttribs(), $element->options);
    
    return $strOutput;
  }
  
  /**
   * buildErrors
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function buildErrors(){
    
    $element  = $this->getElement();
    $messages = $element->getMessages();
    
    if (empty($messages)){
      return '';
    }
    
    return '<div class="errors">'.$element->getView()->formErrors($messages).'</div>';
  }
  
  /**
   * render
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function render($content){
    
    $element = $this->getElement();
       
    if (!$element instanceof Zend_Form_Element) {
      return $content;
    }
        
    if (null === $element->getView()) {
      return $content;
    }

    $separator = $this->getSeparator();
    $placement = $this->getPlacement();
    $label     = $this->buildLabel();
    $url       = $this->buildUrl();
    $errors    = $this->buildErrors();
    $desc      = $this->buildDescription();
    
    $strOutput = '';
    if($url != ''){
      $strOutput = '<div class="field-'.$element->getAttrib('columns').'">';
      $strOutput .= '<div class="field">'
                      .$label
                      .$desc
                      .$url
                      .$errors
                   .'</div>
                   </div>';
    }

    switch ($placement) {
      case (self::PREPEND):
        return $strOutput . $separator . $content;
      case (self::APPEND):
      default:
        return $content . $separator . $strOutput;
    }
  }
  
}

?>