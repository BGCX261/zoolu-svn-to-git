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
 * @package    library.massiveart.generic.fields.SelectTree.forms.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */
/**
 * Form_Helper_List
 * 
 * Helper to generate a "list" element
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2009-10-08: Daniel Rotter
 * 
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 * @package massiveart.forms.helpers
 * @subpackage Form_Helper_List
 */
class Form_Helper_FormList extends Zend_View_Helper_FormElement
{
	  /**
     * formList    
     * @param string $name
     * @param string $value
     * @param array $attribs
     * @param mixed $options
     * @param string $listsep
     * @return string
     * @author Daniel Rotter <daniel.rotter@massiveart.com>
     * @version 1.0
     */
	public function formList($name, $value = null, $attribs = null, $options = null)
	{
		//FIXME: Making it work with multiple instances on one formular
		$info = $this->_getInfo($name, $value, $attribs, $options);
		extract($info);

		$xhtml='<script type="text/javascript">';
		$xhtml.='myList = new List("/widget/blog/comment/delete");';
		$xhtml.='</script>';
		$xhtml.='<table>';
		
		$xhtml.='<tbody>';
		foreach($options as $id=>$option)
		{
			$xhtml.='<tr>';
			$xhtml.='<td style="vertical-align:top;"><input type="checkbox" value="'.$id.'" name="listElement" /></td>';
			foreach($option as $suboption){
				$xhtml.='<td style="padding-right:20px; vertical-align:top; padding-bottom:20px;">';
				$xhtml.=$suboption;
				$xhtml.='</td>';
			}
			$xhtml.='</tr>';
		}
		$xhtml.='</tbody>';
		$xhtml.= '</table>';
		$xhtml.='<input type="button" name="delete" value="Löschen" style="width:auto;" onclick="myList.deleteSelectedElements()">';
		
		return $xhtml;
	}
}
 ?>