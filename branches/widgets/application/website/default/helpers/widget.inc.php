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
 * @package    application.website.default.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * Widget output functions
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-09-19: Florian Mathis
 *
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */

/**
 * getWidgetObject
 * @return Widget
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function getWidgetObject(){
  return Zend_Registry::get('Widget');  
}

/**
 * get_portal_title
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_portal_title(){
	echo getWidgetObject()->getRootLevelTitle();
}

function get_meta_description(){}
function get_meta_keywords(){}

/**
 * get_zoolu_header
 * @return strHtmlOutput
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_zoolu_header(){
	$strHtmlOutput = '';
  
  if(Zend_Auth::getInstance()->hasIdentity()){
    $strHtmlOutput .= '<div class="divModusContainer">
      <div class="divModusLogo">
        <a href="/zoolu/cms" target="_blank">
          <img src="/zoolu/images/modus/logo_zoolu_modus.gif" alt="ZOOLU" />
        </a>
      </div>
      <div class="divModusAdvice">Hinweis: Im Moment werden auch Seiten mit dem <strong>Status "Test"</strong> dargestellt.</div>
      <div class="divModusLogout"><a href="#">Abmelden</a></div>
      <div class="divModusStatus">Test/Live-Modus: 
        <select id="selTestMode" name="selTestMode" onchange="myDefault.changeTestMode(this.options[this.selectedIndex].value);">
          <option value="on" '.((isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == true) ? ' selected="selected"' : '').'>Aktiv</option>
          <option value="off" '.((isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == true) ? '' : ' selected="selected"').'>Inaktiv</option>
        </select>
      </div>
      <div class="clear"></div>
    </div>';  
  }
     
  echo $strHtmlOutput;
}

/**
 * get_template_file
 * @return strTemplateFile
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_template_file(){
	$strTemplateFile = getWidgetObject()->getTemplateFile();
	if(empty($strTemplateFile)) {
		return 'widgets/'.getWidgetObject()->getWidgetName().'/'.getWidgetObject()->getAction().'.php';
	} else {
		return 'widgets/'.getWidgetObject()->getWidgetName().'/'.getWidgetObject()->getTemplateFile();
	}
}

/**
 * get_widget_instance_id
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function get_widget_instance_id(){
	echo getWidgetObject()->getWidgetInstanceId();
}
 
?>