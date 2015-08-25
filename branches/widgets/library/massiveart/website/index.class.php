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
 * @package    library.massiveart.website
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * Index
 * 
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-04-15: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 * @package massiveart.website
 * @subpackage Index
 */

class Index {
   
  /**
   * @var Core
   */
  protected $core;
  
  /**
   * @var Model_Pages
   */
  private $objModelPages;
    
  /**
   * Constructor
   */
  public function __construct(){
    $this->core = Zend_Registry::get('Core');
  }
  
  /**
   * getModelPages
   * @param string $strPageId
   * @param integer $intPageVersion
   * @param integer $intLanguageId
   * @return void
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function indexPage($strPageId, $intPageVersion, $intLanguageId){
    try{
      $objPage = new Page();
      $objPage->setPageId($strPageId);
      $objPage->setPageVersion($intPageVersion);
      $objPage->setLanguageId($intLanguageId);
      
      $objPage->load();  
      $objPage->indexPage();
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * indexAllPublicPages
   * @return void
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function indexAllPublicPages(){
    try{
      $this->getModelPages();
      
      $objPagesData = $this->objModelPages->loadAllPublicPages();
      foreach($objPagesData as $objPageData){
        $this->indexPage($objPageData->pageId, $objPageData->version, $objPageData->idLanguages);
      }
    }catch (Exception $exc) {
      $this->core->logger->err($exc);
    }
  }
  
  /**
   * getModelPages
   * @return Model_Pages
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelPages(){
    if (null === $this->objModelPages) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'cms/models/Pages.php';
      $this->objModelPages = new Model_Pages();
    }

    return $this->objModelPages;
  }
}

?>