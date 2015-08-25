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
 * @package    application.widgets.blog.views.helpers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Blog_View_Helper_Pager
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2010-01-19: Florian Mathis
 * 
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
class Blog_View_Helper_Pager {

   protected $currentPage = 1;
   protected $perPage = 10;
   protected $total = 0;
   protected $startPage = 1;
   protected $endPage = 1;
   protected $pageRange = 10;

   const FORMAT_PREVIOUS = '<a href="%s">&laquo; Vorherige Seite</a>';
   const FORMAT_NEXT = '<a href="%s">N&auml;chste Seite &raquo;</a>';

   protected $baseFormats =
      array(
       'previous'          => self::FORMAT_PREVIOUS,
       'next'              => self::FORMAT_NEXT
      );

  /**
   * Pager object
   * @var Blog_View_Helper_Pager
   */
	static protected $pager;

	/**      
   * init pager and set important information
   *
   * @param integer $total
   * @param integer $perPage
   * @return object pager
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
	public function pager($total = null, $perPage = 10) {
	   	if(isset(self::$pager)) return self::$pager;
	    $page = Zend_Controller_Front::getInstance()->getRequest()->getParam('page');
	
	    if ($page > 0) {
	   		$this->currentPage = $page;
	    }
	
	    $this->total = $total;
	    $this->perPage = $perPage;

	    $this->totalPage = ceil($this->total / $this->perPage);
	     
	    if ($this->totalPage < $this->currentPage) $this->currentPage = 1;
	    $this->endPage = ($this->pageRange * ceil($this->currentPage / $this->pageRange));
	    $this->startPage = ($this->endPage - $this->pageRange) + 1;
	
	    self::$pager = $this;
	
	    return $this;
	}


  /**
   * Display simple Next and Previous Pagination
   *
   * @return string
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function paginate() {
		$this->pager($this->total, $this->perPage);
    $pagination = array();
    $pagination[] = $this->pager()->previous();
    $pagination[] = $this->pager()->next();
    return join(' ', $pagination);
 }
   
  /**
   * Display previous Item  
   *            
   * @return string   
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0 
   */
  public function previous() {
  	$return = null;
    if ($this->currentPage != 1) {
      $url = $this->url($this->currentPage - 1);
      $return = sprintf($this->baseFormats['previous'], $url);
    }
    return $return;
  }

  /**
   * Display next item
   *
   * @return string 
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function next() {
  	$return = null;
    if($this->currentPage != $this->totalPage) {
      $url = $this->url($this->currentPage + 1);
      $return = sprintf($this->baseFormats['next'], $url);
    }
    return $return;
  }

  /**
   * Returns an Url by given options
   *
   * @param  array $urlOptions
   * @return string
   * @author Florian Mathis <flo@massiveart.com>
   * @version 1.0
   */
  public function url($strUrlId) { 
  	$tag = Zend_Controller_Front::getInstance()->getRequest()->getParam('t');
  	if($tag != null) {                                                                                                                         
 			return '?t='.$tag.'&page='.$strUrlId;                
  	} else {
  		return '?page='.$strUrlId;
  	}         
  }
}

?>