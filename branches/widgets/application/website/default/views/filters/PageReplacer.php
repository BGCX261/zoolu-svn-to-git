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
 * @package    application.website.default.views
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Zend_View_Filter_PageReplacer implements Zend_Filter_Interface
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

class Zend_View_Filter_PageReplacer implements Zend_Filter_Interface{

  /**
   * @var Zend_View_Interface
   */
  public $view; 
  
  private $response;
  
  /**
   * css
   */
  const PLACEHOLDER_TEMPLATE_CSS = '<%template_css%>';
  const PLACEHOLDER_WIDGET_CSS = '<%widget_css%>';
  
  /**
   * js
   */
  const PLACEHOLDER_TEMPLATE_JS = '<%template_js%>';
  const PLACEHOLDER_WIDGET_JS = '<%widget_js%>';

  /**
   * link
   */
  const PLACEHOLDER_WIDGET_LINK = '<%widget_link%>';
  
  public function filter($value){
    $this->response = $value;
    
    $this->replaceTemplateCssPlaceholder();
    $this->replaceTemplateJsPlaceholder();
    
    $this->replaceWidgetCssPlaceholder();
    $this->replaceWidgetJsPlaceholder();
    
    $this->replaceWidgetLinkPlaceholder();
        
    return $this->response;
  }
  
  /**
   * replaceTemplateCssPlaceholder
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function replaceTemplateCssPlaceholder(){
    if(Zend_Registry::isRegistered('TemplateCss')){
      $this->response = str_replace(self::PLACEHOLDER_TEMPLATE_CSS, Zend_Registry::get('TemplateCss'), $this->response);
    }
  }
  
  /**
   * replaceWidgetCssPlaceholder
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  private function replaceWidgetCssPlaceholder(){
  	if(Zend_Registry::isRegistered('WidgetCss')){
  		$this->response = str_replace(self::PLACEHOLDER_WIDGET_CSS, Zend_Registry::get('WidgetCss'), $this->response);   
  	} else {
  		$this->response = str_replace(self::PLACEHOLDER_WIDGET_CSS, '', $this->response); 
  	}
  }
  
  /**
   * replaceTemplateJsPlaceholder
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function replaceTemplateJsPlaceholder(){
  	if(Zend_Registry::isRegistered('TemplateJs')){
  	  $this->response = str_replace(self::PLACEHOLDER_TEMPLATE_JS, Zend_Registry::get('TemplateJs'), $this->response);
  	} else {
  		$this->response = str_replace(self::PLACEHOLDER_TEMPLATE_JS, '', $this->response);
  	}
  }
  
  /**
   * replaceWidgetJsPlaceholder
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  private function replaceWidgetJsPlaceholder(){
  	if(Zend_Registry::isRegistered('WidgetJs')){
    	$this->response = str_replace(self::PLACEHOLDER_WIDGET_JS, Zend_Registry::get('WidgetJs'), $this->response);
  	} else {
  		$this->response = str_replace(self::PLACEHOLDER_WIDGET_JS, '', $this->response);
  	}
  }
  
  /**
   * replaceWidgetLinkPlaceholder
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  private function replaceWidgetLinkPlaceholder(){
  	if(Zend_Registry::isRegistered('WidgetLink')){
  		$this->response = str_replace(self::PLACEHOLDER_WIDGET_LINK, Zend_Registry::get('WidgetLink'), $this->response);
  	} else {
  		$this->response = str_replace(self::PLACEHOLDER_WIDGET_LINK, '', $this->response);
  	}
  }
  
  /**
   * Set view object
   * 
   * @param  Zend_View_Interface $view 
   * @return void
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function setView(Zend_View_Interface $view){
    $this->view = $view;
  }
}

?>