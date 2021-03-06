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
 * @package    application.website.default.controllers
 * @copyright  Copyright (c) 2008-2009 HID GmbH (http://www.hid.ag)
 * @license    http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, Version 3
 * @version    $Id: version.php
 */

/**
 * IndexController
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-04-15: Cornelius Hansjakob

 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class IndexController extends Zend_Controller_Action {

  /**
   * @var Core
   */
  private $core;

  /**
   * @var Model_Pages
   */
  private $objModelPages;

  /**
   * @var Model_Folders
   */
  private $objModelFolders;

  /**
   * @var Model_Urls
   */
  private $objModelUrls;

  /**
   * @var Model_Users
   */
  protected $objModelUsers;
  
  /**
   * @var Zend_Cache_Frontend_Output
   */
  private $objCache;
  
  /**
   * @var Zend_Db_Table_Row_Abstract
   */
  private $objTheme; 

  /**
   * @var Page
   */
  private $objPage;

  private $blnCachingStart = false;

  private $blnSearch = false;
    
  private $blnCachingOutput = false;
  
  private $blnPostDispatch = true;
  
  /**
   * default render scirpt
   * @var string
   */
  protected $strRenderScript = 'master.php';

  /**
   * @var string
   */
  protected $strCacheId;
  
  /**
   * @var integer
   */
  private $intLanguageId;

  /**
   * @var string
   */
  private $strLanguageCode;

  /**
   * @var HtmlTranslate
   */
  private $translate;
  
  /**
   * init index controller and get core obj
   */
  public function init(){
    $this->core = Zend_Registry::get('Core');
    
    $this->intLanguageId = $this->core->intLanguageId;
    $this->strLanguageCode = $this->core->strLanguageCode;
    
    //reset action
    if(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/logout'){
      $this->getRequest()->setActionName('logout');
    }elseif(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/login'){
      $this->getRequest()->setActionName('login');
    }elseif(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == '/sitemap.xml'){
      $this->getRequest()->setActionName('sitemap');   
    }
  }
  
  /**
   * preDispatch
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function preDispatch(){
    // trigger client specific dispatch helper
    if($this->core->sysConfig->helpers->client->dispatcher === 'enabled') ClientHelper::get('Dispatcher')->preDispatch($this);
    parent::preDispatch();
  }

  /**
   * postDispatch
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  public function postDispatch(){
    if($this->blnPostDispatch == true){
      // trigger client specific dispatch helper
      if($this->core->sysConfig->helpers->client->dispatcher === 'enabled') ClientHelper::get('Dispatcher')->postDispatch($this);
      
      if(function_exists('tidy_parse_string') && $this->blnCachingOutput == false && $this->getResponse()->getBody() != ''){
        /**
         * Tidy is a binding for the Tidy HTML clean and repair utility which allows 
         * you to not only clean and otherwise manipulate HTML documents, 
         * but also traverse the document tree. 
         */
        $arrConfig = array(
            'indent'        => TRUE,
            'output-xhtml'  => TRUE,
            'wrap'          => 200
        );
        
        $objTidy = tidy_parse_string($this->getResponse()->getBody(), $arrConfig, $this->core->sysConfig->encoding->db);    
        $objTidy->cleanRepair();
        
        $this->getResponse()->setBody($objTidy);
      }
       
      if(isset($this->objCache) && $this->objCache instanceof Zend_Cache_Frontend_Output){
        if($this->blnCachingStart === true){
          $response = $this->getResponse()->getBody();        
          $this->getResponse()->setBody(str_replace("<head>", "<head>
      <!-- This is a ZOOLU cached page (".date('d.m.Y H:i:s').") -->", $response));
          $this->getResponse()->outputBody();
  
          $arrTags = array();
  
          if($this->objPage->getIsStartElement(false) == true)
            $arrTags[] = 'Start'.ucfirst($this->objPage->getType());
  
          $arrTags[] = ucfirst($this->objPage->getType()).'Type_'.$this->objPage->getTypeId();
          $arrTags[] = ucfirst($this->objPage->getType()).'Id_'.$this->objPage->getPageId().'_'.$this->objPage->getLanguageId();
  
          $this->core->logger->debug(var_export($arrTags, true));
          $this->objCache->end($arrTags);
          $this->core->logger->debug('... end caching!');
          exit();
        }
      }
      parent::postDispatch();
    }
  }

  /**
	 * indexAction
	 * @author Cornelius Hansjakob <cha@massiveart.com>
	 * @version 1.0
	 */
  public function indexAction(){
    $this->view->addFilter('PageReplacer');
    
    /**
     * get uri
     */
    $strUrl = $_SERVER['REQUEST_URI'];
    
    if(preg_match('/^\/[a-zA-Z\-]{2,5}\//', $strUrl)){
      $strUrl = preg_replace('/^\/[a-zA-Z\-]{2,5}\//', '', $strUrl);
    }else{
      $strUrl = preg_replace('/^\//', '', $strUrl);      
    }
                
    // load theme
    $this->loadTheme();
    
    // check portal security
    $this->checkPortalSecuirty();
    
    // validate language
    $this->validateLanguage();
        
    // set translate 
    $this->setTranslate();
          
    // init page cache
    $this->initPageCache($strUrl);
    
    /**
     * check if "q" param is in the url for the search
     */
    if(strpos($strUrl, '?q=') !== false){
      $this->blnSearch = true;
      $strUrl = '';
    }
        
    // check, if cached page exists
    if($this->core->sysConfig->cache->page == 'false' ||
       ($this->core->sysConfig->cache->page == 'true' && $this->objCache->test($this->strCacheId) == false) ||
       ($this->core->sysConfig->cache->page == 'true' && isset($_SESSION['sesTestMode']))){

      $this->getModelUrls();
      $this->getModelPages();
      
      if(file_exists(GLOBAL_ROOT_PATH.'client/website/navigation.class.php')){
        require_once(GLOBAL_ROOT_PATH.'client/website/navigation.class.php');
        $objNavigation = new Client_Navigation();
      }else{
        $objNavigation = new Navigation(); 
      }
      $objNavigation->setRootLevelId($this->objTheme->idRootLevels);
      $objNavigation->setLanguageId($this->intLanguageId);

      if(file_exists(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/helpers/NavigationHelper.php')){
        require_once(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/helpers/NavigationHelper.php');
        $strNavigationHelper = ucfirst($this->objTheme->path).'_NavigationHelper';
        $objNavigationHelper = new $strNavigationHelper();
      }else{
        require_once(dirname(__FILE__).'/../helpers/NavigationHelper.php');
        $objNavigationHelper = new NavigationHelper();        
      }
      
      $objNavigationHelper->setNavigation($objNavigation);
      $objNavigationHelper->setTranslate($this->translate);      
      Zend_Registry::set('NavigationHelper', $objNavigationHelper);
      
      $objUrl = $this->objModelUrls->loadByUrl($this->objTheme->idRootLevels, (parse_url($strUrl, PHP_URL_PATH) === null) ? '' : parse_url($strUrl, PHP_URL_PATH));

      if(isset($objUrl->url) && count($objUrl->url) > 0){               
        $objUrlData = $objUrl->url->current(); 
        
        // check if url is main
        if(!$objUrlData->isMain){
          $objMainUrl = $this->objModelUrls->loadUrl($objUrlData->relationId, $objUrlData->version, $objUrlData->idUrlTypes);           
          if(count($objMainUrl) > 0){
            $objMainUrl = $objMainUrl->current();
            $this->getResponse()->setHeader('HTTP/1.1', '301 Moved Permanently');
            $this->getResponse()->setHeader('Status', '301 Moved Permanently');
            $this->getResponse()->setHttpResponseCode(301);
            $this->_redirect('/'.strtolower($objMainUrl->languageCode).'/'.$objMainUrl->url);  
          }
        }

        if($this->core->sysConfig->cache->page == 'true' && !isset($_SESSION['sesTestMode']) && $this->blnSearch == false && (!isset($_POST) || count($_POST) == 0)){
          $this->objCache->start($this->strCacheId);
          $this->blnCachingStart = true;
        }
        
        if(file_exists(GLOBAL_ROOT_PATH.'client/website/page.class.php')){
          require_once(GLOBAL_ROOT_PATH.'client/website/page.class.php');
          $this->objPage = new Client_Page();
        }else{
          $this->objPage = new Page();  
        }
        $this->objPage->setRootLevelId($this->objTheme->idRootLevels);
        $this->objPage->setRootLevelTitle(($this->core->blnIsDefaultLanguage === true ? $this->objTheme->defaultTitle : $this->objTheme->title));
        $this->objPage->setRootLevelGroupId($this->objTheme->idRootLevelGroups);        
        $this->objPage->setPageId($objUrlData->relationId);
        $this->objPage->setPageVersion($objUrlData->version);
        $this->objPage->setLanguageId($objUrlData->idLanguages);
                
        switch($objUrlData->idUrlTypes){
          case $this->core->sysConfig->url_types->page:
            $this->objPage->setType('page');
            $this->objPage->setModelSubPath('cms/models/');
            break;
          case $this->core->sysConfig->url_types->global:
            $this->objPage->setType('global');
            $this->objPage->setModelSubPath('global/models/');
            $this->objPage->setElementLinkId($objUrlData->idLink);
            $this->objPage->setNavParentId($objUrlData->idLinkParent);
            $this->objPage->setPageLinkId($objUrlData->linkId);
            break;
        }        

        /**
         * preset navigation parent properties
         * e.g. is a collection page
         */
        if($objUrlData->idParent !== null){
          $this->objPage->setNavParentId($objUrlData->idParent);
          $this->objPage->setNavParentTypeId($objUrlData->idParentTypes);
        }
        
        /**
         * has base url object 
         * e.g. prduct tree
         */
        if(isset($objUrl->baseUrl)){
          $objNavigation->setBaseUrl($objUrl->baseUrl);
          $this->objPage->setBaseUrl($objUrl->baseUrl);
          $this->objPage->setNavParentId($objUrlData->idLinkParent);
        }

        $this->objPage->loadPage();
                
        /**
         * check status
         */
        if($this->objPage->getStatus() != $this->core->sysConfig->status->live && (!isset($_SESSION['sesTestMode']) || (isset($_SESSION['sesTestMode']) && $_SESSION['sesTestMode'] == false))){
          $this->_redirect('/');          
        }
       
        if($this->objPage->ParentPage() instanceof Page){
          $objNavigation->setPage($this->objPage->ParentPage());
        }else{
          $objNavigation->setPage($this->objPage); 
        } 
        
        /**
         * check page security
         */        
        if($objNavigation->secuirtyZoneCheck()){
          // deactivate caching
          $this->blnCachingStart = false;
          
          $objAuth = Zend_Auth::getInstance();
          if(!Zend_Auth::getInstance()->hasIdentity()){
            $this->_redirect('/login?re='.urlencode($_SERVER['REQUEST_URI']));         
          }else{
            if(!$objNavigation->checkZonePrivileges()){
              $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
              $this->getResponse()->setHeader('Status', '403 Forbidden');
              $this->getResponse()->setHttpResponseCode(401);
              $this->strRenderScript = 'error-403.php';
            }
          }
        }
                
        /**
         * set values for replacers
         */
        Zend_Registry::set('TemplateCss', ($this->objPage->getTemplateId() == $this->core->sysConfig->page_types->page->portal_startpage_templateId) ? '' : '');
        Zend_Registry::set('TemplateJs', ($this->objPage->getTemplateId() == $this->core->sysConfig->page_types->page->headquarters_templateId) ? '<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key='.$this->view->mapsKey.'" type="text/javascript"></script>' : '');
        
        if(file_exists(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/helpers/PageHelper.php')){
          require_once(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/helpers/PageHelper.php');
          $strPageHelper = ucfirst($this->objTheme->path).'_PageHelper';
          $objPageHelper = new $strPageHelper();
        }else{
          require_once(dirname(__FILE__).'/../helpers/PageHelper.php');
          $objPageHelper = new PageHelper();
        }
        $objPageHelper->setTheme($this->objTheme->path);
        
        $objPageHelper->setPage($this->objPage);
        $objPageHelper->setTranslate($this->translate);
        Zend_Registry::set('PageHelper', $objPageHelper);
        
        /**
         * forward to SearchController
         */
        if($this->blnSearch == true){
          $this->_forward('index', 'Search', null, array('rootLevelId' => $this->objPage->getRootLevelId(), 'theme' => $this->objTheme->path));
        }else{
          /**
           * get page template filename
           */
          $this->view->template = $this->objPage->getTemplateFile();
          $this->view->publisher = $this->objPage->getPublisherName();
          $this->view->publishdate = $this->objPage->getPublishDate();
        
          $this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/');
          $this->renderScript($this->strRenderScript);
        }
      }else{    
        // try url with/without slash redirect or error output    
        $this->urlRetryRedirectAndError($strUrl);
      }
    }else{
      $this->blnCachingOutput = true;
      $this->getResponse()->setBody($this->objCache->load($this->strCacheId));
      $this->_helper->viewRenderer->setNoRender();
    }
  }
  
  /**
   * sitemapAction
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function sitemapAction(){
    $this->loadTheme();
  
    $strMainUrl = $this->getModelFolders()->getRootLevelMainUrl($this->objTheme->idRootLevels, $this->core->sysConfig->environments->production);  
        
    if($strMainUrl != ''){
          
      $arrUrlParts = explode('.', $strMainUrl);
      if(count($arrUrlParts) == 2){
        $strMainUrl = str_replace('http://', 'www.', $strMainUrl);
      }else{
        $strMainUrl = str_replace('http://', '', $strMainUrl);
      }
                  
      if(file_exists(GLOBAL_ROOT_PATH.'public/sitemaps/'.$strMainUrl.'/sitemap.xml')){
        $this->_helper->viewRenderer->setNoRender();
        $this->blnPostDispatch = false;
        $this->getResponse()->setHeader('Content-Type', 'text/xml')
                            ->setBody(file_get_contents(GLOBAL_ROOT_PATH.'public/sitemaps/'.$strMainUrl.'/sitemap.xml'));
      }else{
        $this->_redirect('/');
      }
    }else{
      $this->_redirect('/');
    }     
  }
  
  /**
   * loginAction
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function loginAction(){
    $this->loadTheme();
    $this->setTranslate();
    
    $objAuth = Zend_Auth::getInstance();
    if($objAuth->hasIdentity()){
      $this->_redirect($this->getRequest()->getParam('re', '/'));
    }else{

      $this->view->strErrMessage = '';
      $this->view->strErrUsername = '';
      $this->view->strErrPassword = '';

      if($this->_request->isPost()){
  
        /**
         * data from the user
         * strip all HTML and PHP tags from the data
         */
        $objFilter = new Zend_Filter_StripTags();
        $username = $objFilter->filter($this->_request->getPost('username'));
        $password = md5($objFilter->filter($this->_request->getPost('password')));
  
        if(empty($username)){
          $this->view->strErrUsername = $this->core->translate->_('Please_enter_username');
        }else{
  
          $this->core = Zend_Registry::get('Core');
  
          /**
           * setup Zend_Auth for authentication
           */
          if(ClientHelper::get('Authentication')->isActive() == true){
            $objAuthAdapter = ClientHelper::get('Authentication')->getAdapter();
          }else{
            $objAuthAdapter = new Zend_Auth_Adapter_DbTable($this->core->dbh);
            $objAuthAdapter->setTableName('users');
            $objAuthAdapter->setIdentityColumn('username');
            $objAuthAdapter->setCredentialColumn('password');  
          }
          
          /**
           * set the input credential values to authenticate against
           */
          $objAuthAdapter->setIdentity($username);
          $objAuthAdapter->setCredential($password);
  
          /**
           * do the authentication
           */
          $result = $objAuth->authenticate($objAuthAdapter);
  
          switch ($result->getCode()) {
  
            case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
              /**
               * do stuff for nonexistent identity
               */
              $this->view->strErrUsername = $this->core->translate->_('Username_not_found');
              break;
  
            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
              /**
               * do stuff for invalid credential
               */
              $this->view->strErrPassword = $this->core->translate->_('Wrong_password');
              break;
  
            case Zend_Auth_Result::SUCCESS:
              
              if(ClientHelper::get('Authentication')->isActive() == true){
                $objUserData = ClientHelper::get('Authentication')->getUserData();
                $objUserRoleProvider = ClientHelper::get('Authentication')->getUserRoleProvider();
              }else{
                /**
                 * store database row to auth's storage system but not the password
                 */
                $objUserData = $objAuthAdapter->getResultRowObject(array('id', 'idLanguages', 'username', 'fname', 'sname'));
                $objUserData->languageId = $objUserData->idLanguages;
                unset($objUserData->idLanguages);
                
                $objUserRoleProvider = new RoleProvider();
                $arrUserGroups = $this->getModelUsers()->getUserGroups($objUserData->id);
                if(count($arrUserGroups) > 0){
                  foreach($arrUserGroups as $objUserGroup){
                    $objUserRoleProvider->addRole(new Zend_Acl_Role($objUserGroup->key), $objUserGroup->key);
                  }
                }        
              }
              
              $objSecurity = new Security();
              $objSecurity->setRoleProvider($objUserRoleProvider);
              $objSecurity->buildAcl($this->getModelUsers());
              Security::save($objSecurity);
  
              $objUserData->languageCode = null;          
              $arrLanguages = $this->core->zooConfig->languages->language->toArray();
              foreach($arrLanguages as $arrLanguage){
                if($arrLanguage['id'] == $objUserData->languageId){
                  $objUserData->languageCode = $arrLanguage['code'];
                  break;
                }
              }
              
              if($objUserData->languageCode === null){
                $objUserData->languageId = $this->core->zooConfig->languages->default->id;
                $objUserData->languageCode = $this->core->zooConfig->languages->default->code;
              }
              
              $objAuth->getStorage()->write($objUserData);
              $this->_redirect($this->getRequest()->getParam('re', '/'));
              break;
  
            default:
              /**
               * do stuff for other failure
               */
              $this->view->strErrMessage = $this->core->translate->_('Login_failed');
              break;
          }
        }
      }
    }
        
    $this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/');
    $this->renderScript('login.php');
  }
  
  /**
   * logoutAction
   * @author Thomas Schedler <tsh@massiveart.com>
   */
  public function logoutAction(){
    $objAuth = Zend_Auth::getInstance();
    $objAuth->clearIdentity();
    $this->_redirect('/');
  }
  
  /**
   * loadTheme
   * @return void
   */
  private function loadTheme(){

    // set domain
    $strDomain = $_SERVER['SERVER_NAME'];
    
    $objThemeData = $this->getModelFolders()->getThemeByDomain($strDomain);
    
    //if($this->objTheme->rootLevelLanguageId != $this->intLanguageId);
    
    if(count($objThemeData) > 0){
      $this->objTheme = $objThemeData->current();
      
      $this->view->analyticsKey = $this->objTheme->analyticsKey;  
      $this->view->analyticsDomain = $strDomain;
      $this->view->mapsKey = $this->objTheme->mapsKey;
      $this->view->rootLevelId = $this->objTheme->idRootLevels;
      
      if($this->objTheme->localization != ''){
        Zend_Registry::get('Location')->setLocale($this->objTheme->localization);
      }
    }else{
      throw new Exception('Unable to load theme based on the URL "'.$strDomain.'"');
    }
  }
  
  /**
   * checkPortalSecuirty
   * @return void
   */
  private function checkPortalSecuirty(){      
    /**
     * check portal security
     */
    if(isset($this->objTheme) && (int) $this->objTheme->isSecure === 1){
      // deactivate caching
      $this->blnCachingStart = false;
      
      $objAuth = Zend_Auth::getInstance();
      if(!Zend_Auth::getInstance()->hasIdentity()){
        $this->_redirect('/login?re='.urlencode($_SERVER['REQUEST_URI']));         
      }else{
        Security::get()->addRootLevelsToAcl($this->getModelFolders(), $this->core->sysConfig->modules->cms, Security::ZONE_WEBSITE);
        if(!Security::get()->isAllowed(Security::RESOURCE_ROOT_LEVEL_PREFIX.$this->objTheme->idRootLevels, Security::PRIVILEGE_VIEW, false, false, Security::ZONE_WEBSITE) && !Security::get()->isAllowed(Security::RESOURCE_ROOT_LEVEL_PREFIX.$this->objTheme->idRootLevels.'_'.$this->intLanguageId, Security::PRIVILEGE_VIEW, false, false, Security::ZONE_WEBSITE)){
          $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
          $this->getResponse()->setHeader('Status', '403 Forbidden');
          $this->getResponse()->setHttpResponseCode(401);
          $this->strRenderScript = 'error-403.php';
        }
      }
    }  
  }
  
  /**
   * validateLanguage
   * @return void
   */
  private function validateLanguage(){
    if($this->core->blnIsDefaultLanguage === true){
      
      $this->core->intLanguageId = $this->objTheme->idLanguages;
      $this->core->strLanguageCode = strtolower($this->objTheme->languageCode);
      
      // update session language
      $this->core->updateSessionLanguage();
      
      $this->intLanguageId = $this->core->intLanguageId;
      $this->strLanguageCode = $this->core->strLanguageCode;      
    }
        
    $this->view->languageId = $this->intLanguageId;
    $this->view->languageCode = $this->strLanguageCode;
  }
  
  /**
   * urlRetryRedirectAndError
   * @return void
   */
  private function urlRetryRedirectAndError($strUrl){    
    if($strUrl == '' && $this->getRequest()->getParam('re', 'true') == 'true'){
      // reset language 
      
      $this->core->intLanguageId = $this->objTheme->idLanguages;
      $this->core->strLanguageCode = strtolower($this->objTheme->languageCode);
      
      // update session language
      $this->core->updateSessionLanguage();
      
      // redirct
      $this->_redirect('/?re=false');
    }else{
      $strTmpUrl = ((parse_url($strUrl, PHP_URL_PATH) === null) ? '' : parse_url($strUrl, PHP_URL_PATH));
      
      if(($strTmpUrl[strlen($strTmpUrl)-1]) == '/'){            
        $strTmpUrl = rtrim($strTmpUrl, '/');     
      }else if(($strTmpUrl[strlen($strTmpUrl)-1]) != '/'){
        $strTmpUrl = $strTmpUrl.'/';  
      } 
              
      $objUrl = $this->objModelUrls->loadByUrl($this->objTheme->idRootLevels, $strTmpUrl);
      
      if(isset($objUrl->url) && count($objUrl->url) > 0){
        $this->getResponse()->setHeader('HTTP/1.1', '301 Moved Permanently');
        $this->getResponse()->setHeader('Status', '301 Moved Permanently');
        $this->getResponse()->setHttpResponseCode(301);
        $this->_redirect('/'.$this->strLanguageCode.'/'.$strTmpUrl);
      }else{ 
        $this->view->setScriptPath(GLOBAL_ROOT_PATH.'public/website/themes/'.$this->objTheme->path.'/');
        $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
        $this->getResponse()->setHeader('Status', '404 Not Found');
        $this->getResponse()->setHttpResponseCode(404);
        $this->renderScript('error-404.php');  
      }  
    }
  }
  
  /**
   * setTranslate
   * @return void
   */
  private function setTranslate(){
    /**
     * set up zoolu translate obj
     */
    if(file_exists(GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->strLanguageCode.'.mo')){
       $this->translate = new HtmlTranslate('gettext', GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->strLanguageCode.'.mo');  
    }else{
       $this->translate = new HtmlTranslate('gettext', GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->sysConfig->languages->default->code.'.mo');
    }
    
    $this->view->translate = $this->translate;
  }
  
  /**
   * initPageCache
   * @return void
   */
  private function initPageCache($strUrl){
    $this->strCacheId = 'page_'.$this->objTheme->idRootLevels.'_'.strtolower(str_replace('-', '_', $this->strLanguageCode)).'_'.preg_replace('/[^a-zA-Z0-9_]/', '_', $strUrl);
    
    $arrFrontendOptions = array(
      'lifetime' => 604800, // cache lifetime (in seconds), if set to null, the cache is valid forever.
      'automatic_serialization' => true
    );

    $arrBackendOptions = array(
      'cache_dir' => GLOBAL_ROOT_PATH.$this->core->sysConfig->path->cache->pages // Directory where to put the cache files
    );

    // getting a Zend_Cache_Core object
    $this->objCache = Zend_Cache::factory('Output',
                                          'File',
                                          $arrFrontendOptions,
                                          $arrBackendOptions);
  }

  /**
   * fontsizeAction
   * @author Michael Trawetzky <mtr@massiveart.com>
   * @version 1.0
   */
  public function fontsizeAction(){
  	$request = $this->getRequest();
  	$strFontSize = $request->getParam('fontsize');

    $objWebSession = new Zend_Session_Namespace('Website');
    $objWebSession->fontSize = $strFontSize;
    
  	$this->_helper->viewRenderer->setNoRender();
  }
  
  /**
   * getModelPages
   * @return Model_Pages
   * @author Cornelius Hansjakob <cha@massiveart.com>
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
      $this->objModelPages->setLanguageId($this->intLanguageId);
    }

    return $this->objModelPages;
  }

  /**
   * getModelFolders
   * @return Model_Folders
   * @author Cornelius Hansjakob <cha@massiveart.com>
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
      $this->objModelFolders->setLanguageId($this->intLanguageId);
    }

    return $this->objModelFolders;
  }

  /**
   * getModelUrls
   * @return Model_Urls
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelUrls(){
    if (null === $this->objModelUrls) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/Urls.php';
      $this->objModelUrls = new Model_Urls();
      $this->objModelUrls->setLanguageId($this->intLanguageId);
    }

    return $this->objModelUrls;
  }
  
  /**
   * getModelUsers
   * @author Thomas Schedler <tsh@massiveart.com>
   * @version 1.0
   */
  protected function getModelUsers(){
    if (null === $this->objModelUsers) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'users/models/Users.php';
      $this->objModelUsers = new Model_Users();
    }

    return $this->objModelUsers;
  }
}
?>