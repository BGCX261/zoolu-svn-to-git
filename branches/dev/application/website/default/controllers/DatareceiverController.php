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
 * DatareceiverController
 * 
 * Version history (please keep backward compatible):
 * 1.0, 2008-04-20: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

class DatareceiverController extends Zend_Controller_Action {

  /**
   * @var Core
   */
  private $core;
  
  /**
   * @var HtmlTranslate
   */
  private $translate;
  
  /**
   * @var Model_GenericData
   */
  protected $objModelGenericData;
  
  protected $arrFormData = array();
  protected $arrFileData = array();
  protected $arrMailRecipients = array();
  
  protected $strRedirectUrl;
  
  protected $strSenderName;
  protected $strSenderMail;
  protected $strReceiverName;
  protected $strReceiverMail;
  protected $strMailSubject = '';
  
  protected $strUploadPath;
  protected $strAttachmentFile = '';
  
  protected $strUserFName;
  protected $strUserSName;
  protected $strUserMail;
  
  private $arrFormFields = array();
  
  /**
   * init index controller and get core obj
   */
  public function init(){
    $this->core = Zend_Registry::get('Core');
  }
  
  /**
   * indexAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function indexAction(){
    $this->core->logger->debug('website->controllers->DatareceiverController->indexAction()');

    if($this->getRequest()->isPost()) {
      $this->arrMailRecipients = array('Name'  => $this->core->config->mail->recipient->name,
      'Email' => $this->core->config->mail->recipient->address);

      $this->arrFormData = $this->getRequest()->getPost();
      if(isset($_FILES)){
        $this->arrFileData = $_FILES;
      }

      /**
       * set up zoolu translate obj
       */
      if(file_exists(GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->strLanguageCode.'.mo')){
        $this->translate = new HtmlTranslate('gettext', GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->strLanguageCode.'.mo');
      }else{
        $this->translate = new HtmlTranslate('gettext', GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->sysConfig->languages->default->code.'.mo');
      }

      if(count($this->arrFormData) > 0){
        $this->arrFormFields = array('salutation'         => $this->translate->_('Salutation'),
                                     'title'              => $this->translate->_('Title'),
                                     'fname'              => $this->translate->_('Fname'),
                                     'sname'              => $this->translate->_('Sname'),
                                     'company'            => $this->translate->_('Company'),
                                     'email'              => $this->translate->_('Email'),
                                     'phone'              => $this->translate->_('Phone'),
                                     'fax'                => $this->translate->_('Fax'),
                                     'function'           => $this->translate->_('Function'),
                                     'type'               => $this->translate->_('Type'),
                                     'street'             => $this->translate->_('Street'),
                                     'zip'                => $this->translate->_('Zip'),
                                     'city'               => $this->translate->_('City'),
                                     'state'              => $this->translate->_('State'),
                                     'country'            => $this->translate->_('Country'),
                                     'message'            => $this->translate->_('Message'),
                                     'attachment'         => $this->translate->_('Attachment'),
                                     'checkLegalnotes'    => $this->translate->_('Check_Legalnotes'));

        /**
         * set sender name and e-mail
         */
        if(array_key_exists('sender_name', $this->arrFormData) && array_key_exists('sender_mail', $this->arrFormData)){
          $this->strSenderName = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['sender_name']);
          $this->strSenderMail = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['sender_mail']);
          unset($this->arrFormData['sender_name']);
          unset($this->arrFormData['sender_mail']); 
        }
        
        /**
         * set receiver name and e-mail
         */
        if(array_key_exists('receiver_name', $this->arrFormData) && array_key_exists('receiver_mail', $this->arrFormData)){
          $this->strReceiverName = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['receiver_name']);
          $this->strReceiverMail = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['receiver_mail']);
          
          $this->arrMailRecipients = array('Name'  => $this->strReceiverName,
                                           'Email' => $this->strReceiverMail);
          
          unset($this->arrFormData['receiver_name']);
          unset($this->arrFormData['receiver_mail']);   
        }

        /**
         * set e-mail subject
         */
        if(array_key_exists('subject', $this->arrFormData)){
          $this->strMailSubject = $this->arrFormData['subject'];
        }

        /**
         * set redirect url
         */
        if(array_key_exists('redirectUrl', $this->arrFormData)){
          $this->strRedirectUrl = $this->arrFormData['redirectUrl'];
          unset($this->arrFormData['redirectUrl']); 
        }

        /**
         * send mail
         */
        if($this->core->config->mail->actions->sendmail->client == 'true'){          
          $this->sendMail();  
        }

        /**
         * save to database
         */
        if($this->core->config->mail->actions->database == 'true'){
          $this->insertDatabase();
        }

        $strUrl = (strpos($this->strRedirectUrl,'?') !== false) ? $this->strRedirectUrl.'&send=true' : $this->strRedirectUrl.'?send=true';
	  	  $this->_redirect($strUrl);
      }
    }
  }

  /**
   * sendMail
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function sendMail($blnSpecialForm = false){
    $this->core->logger->debug('website->controllers->DatareceiverController->sendMail()');
    $mail = new Zend_Mail('utf-8');

    /**
     * config for SMTP with auth
     */
    $config = array('auth'     => 'login',
                    'username' => $this->core->config->mail->params->username,
                    'password' => $this->core->config->mail->params->password);

    /**
     * SMTP
     */
    $transport = new Zend_Mail_Transport_Smtp($this->core->config->mail->params->host, $config);
    $strHtmlBody = '';

    if(count($this->arrFormData) > 0){
      $strHtmlBody = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
        <html>
          <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <title></title>
            <style type="text/css">
              body { margin:0; padding:20px; color:#333333; width:100%; height:100%; font-size:12px; font-family: Arial, Sans-Serif; background-color:#ffffff; line-height:16px;}
              span { line-height:15px; font-size:12px; }
              h1 { color:#333333; font-weight:bold; font-size:16px; font-family: Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
              h2 { color:#333333; font-weight:bold; font-size:14px; font-family: Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
              h3 { color:#333333; font-weight:bold; font-size:12px; font-family: Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
              a { color:#000000; font-size:12px; text-decoration:underline; margin:0; padding:0; }
              a:hover { color:#000000; font-size:12px; text-decoration:underline; margin:0; padding:0; }
              p { margin:0 0 10px 0; padding:0; }
            </style>
          </head>
          <body>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td>
                  '.(!$blnSpecialForm ? $this->getEmailBody() : $this->getEmailBodySpecialForm()).'
                </td>
              </tr>
            </table>
          </body>
        </html>';
    }

    /**
     * Adding Attachment to Mail
     */
    if(count($this->arrFileData) > 0){
      foreach($this->arrFileData as $arrFile){        
        if($arrFile['name'] != ''){
          // upload file
          $strFile = $this->upload($arrFile); 
  
          // add file to mail
          $objFile = $mail->createAttachment(file_get_contents($strFile));
          $objFile->type        = $arrFile['type'];
          $objFile->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
          $objFile->encoding    = Zend_Mime::ENCODING_BASE64;         
          $objFile->filename    = $arrFile['name'];
        }            
      }
    }


    /**
     * set mail subject
     */
    $mail->setSubject($this->strMailSubject);
    /**
     * set html body
     */
    $mail->setBodyHtml($strHtmlBody);
    /**
     * set default FROM address
     */
    $mail->setFrom($this->strSenderMail, $this->strSenderName);
		
    /**
     * set TO address
     */
    if(array_key_exists('email', $this->arrFormData)){
      if(array_key_exists('fname', $this->arrFormData)) $this->strUserFName = $this->arrFormData['fname'];
      if(array_key_exists('sname', $this->arrFormData)) $this->strUserSName = $this->arrFormData['sname'];
      $this->strUserMail = $this->arrFormData['email'];
    }

    if(count($this->arrMailRecipients) > 0){
      //foreach($this->arrMailRecipients as $arrRecipient){
    	$mail->clearRecipients();
    	$mail->addTo($this->arrMailRecipients['Email'], $this->arrMailRecipients['Name']);
	    /**
	     * send mail if mail body is not empty
	     */
	    if($strHtmlBody != ''){
	      $mail->send($transport);		      		      
	    }	
      //}
      if($this->core->config->mail->actions->sendmail->confirmation == 'true'){
        $this->sendConfirmationMail();  
      }	
    }
  }

  /**
   * getEmailBody
   * @return string
   */
  private function getEmailBody(){
    $strHtmlBody = '';
      foreach($this->arrFormData as $key => $value){
        if($value != ''){
       	  if($key == 'idRootLevels' || $key == 'idPage' || $key == 'subject'){
       	    // do nothing
       	  }else if($key == 'country'){
            $objSelect = $this->getModelGenericData()->getGenericTable('categories')->select();
            $objSelect->setIntegrityCheck(false);
            $objSelect->from('categories', array('id'));
            $objSelect->join('categoryTitles', 'categoryTitles.idCategories = categories.id AND categoryTitles.idLanguages = '.$this->core->intLanguageId, array('title'));
            $objSelect->where('categories.id = ?', $value);
            $objResult = $this->getModelGenericData()->getGenericTable('categories')->fetchAll($objSelect);
            $objData = $objResult->current();

            $strHtmlBody .= '
                  <strong>'.$this->arrFormFields[$key].':</strong> '.$objData->title.'<br/>';

       	  }else{
            $strHtmlBody .= '
                  <strong>'.$this->arrFormFields[$key].':</strong> '.$value.'<br/>';
          }
        }
      }

      return $strHtmlBody;
  }

  /**
   * getEmailBodySpecialForm
   * @return string
   */
  private function getEmailBodySpecialForm(){
    $strHtmlBody = '';
    $i = 0;

    foreach($this->arrFormData as $key => $value){
      //print headline
      switch($i){
        case 0:
          $strHtmlBody .= '<h2>'.$this->translate->_('subject').'</h2>';
          break;
        case 1:
          $strHtmlBody .= '<h2>'.$this->translate->_('personal_data').'</h2>';
          break;
        case 4:
          $strHtmlBody .= '<h2>'.$this->translate->_('data_from_study_or_laboratory').'</h2>';
          break;
        case 16:
          $strHtmlBody .= '<h2>'.$this->translate->_('billing_information').'</h2>';
          break;
        case 27:
          $strHtmlBody .= '<h2>'.$this->translate->_('bank_details').'</h2>';
          break;
        case 29:
          $strHtmlBody .= '<h2>'.$this->translate->_('hotel_reservation').'</h2>';
          break;
        case 32:
          $strHtmlBody .= '<h2>'.$this->translate->_('data_privacy_statement').'</h2>';
          break;
        default:
          break;
      }

      if($value != ''){
        if($key == 'idRootLevels' || $key == 'idPage'){
          // do nothing
        }else if(is_array($value)){
          $intI = 0;
          foreach($value as $v){
            if($intI != 0){
              $strHtmlBody .= ', ';
            }else{
              $strHtmlBody .= '<strong>'.$this->arrFormFields[$key].':</strong> ';
            }
            $strHtmlBody .= $this->translate->_($v);
            $intI++;
          }
          $strHtmlBody .= '<br />';
        }else if($key == 'subject'){
          $strHtmlBody .= $value.'<br/>';
        }else if($key == 'hotel'){
          $strHtmlBody .= '
            <strong>'.$this->arrFormFields[$key].':</strong> '.$this->translate->_($value).'<br/>';
        }else{
          $strHtmlBody .= '
            <strong>'.$this->arrFormFields[$key].':</strong> '.$value.'<br/>';
        }
      }
      $i++;
    }

    return $strHtmlBody;
  }

  /**
   * sendConfirmationMail
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function sendConfirmationMail(){
    $this->core->logger->debug('website->controllers->DatareceiverController->sendConfirmationMail()');
      
    $mail = new Zend_Mail('utf-8');
    
    /**
     * config for SMTP with auth
     */
    $config = array('auth'     => 'login',
                    'username' => $this->core->config->mail->params->username,
                    'password' => $this->core->config->mail->params->password);
    
    /**
     * SMTP
     */
    $transport = new Zend_Mail_Transport_Smtp($this->core->config->mail->params->host, $config);
    
    $strHtmlBody = '';
    
    if($this->strUserFName != '' && $this->strUserSName != ''){
      $strHtmlBody = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
        <html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <title></title>
          <style type="text/css">
            body { margin:0; padding:20px; color:#333333; width:100%; height:100%; font-size:12px; font-family: Arial, Sans-Serif; background-color:#ffffff; line-height:16px;}
            span { line-height:15px; font-size:12px; }
            h1 { color:#333333; font-weight:bold; font-size:16px; font-family: Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
            h2 { color:#333333; font-weight:bold; font-size:14px; font-family: Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
            h3 { color:#333333; font-weight:bold; font-size:12px; font-family: Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
            a { color:#000000; font-size:12px; text-decoration:underline; margin:0; padding:0; }
            a:hover { color:#000000; font-size:12px; text-decoration:underline; margin:0; padding:0; }
            p { margin:0 0 10px 0; padding:0; }
          </style>
        </head>
        <body>
        <table cellpadding="0" cellspacing="0" style="width:650px; margin:auto;">
           <tr>
              <td style="padding:20px 15px 20px 15px;">
                 <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                       <td>
                         <h2>Sehr geehrte(r) '.$this->strUserFName.' '.$this->strUserSName.'</h2>                         
                       </td>
                    </tr>
                 </table>
              </td>
           </tr>
        </table>
        </body>
        </html>';
    }
      
    /**
     * set mail subject
     */
    $mail->setSubject($this->strMailSubject);
    /**
     * set html body
     */
    $mail->setBodyHtml($strHtmlBody);
    /**
     * set default FROM address
     */
    $mail->setFrom($this->strSenderMail, $this->strSenderName);
      
    if($this->strUserMail != ''){
      $mail->clearRecipients();
      $mail->addTo($this->strUserMail, $this->strUserFName.' '.$this->strUserSName);
      /**
       * send mail if mail body is not empty
       */
      if($strHtmlBody != ''){
        $mail->send($transport);
      }
    }
  }
  
  /**
   * insertDatabase
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function insertDatabase($strDB = ''){
    $this->core->logger->debug('website->controllers->DatareceiverController->insertDatabase()');

    if(count($this->arrFormData) > 0){
      if(isset($this->core->config->mail->database) && $this->core->config->mail->database != ''){        

        if($strDB != ''){
          $objGenTable = $this->getModelGenericData()->getGenericTable($strDB);
        }else{
          $objGenTable = $this->getModelGenericData()->getGenericTable($this->core->config->mail->database);
        }
      	
      	$arrTableData = array();
	      foreach($this->arrFormData as $key => $value){
	        if($value != ''){
	          if($value == 'on'){
	            $arrTableData[$key] = 1;
                  }else if(is_array($value)){
                    $arrTableData[$key] = implode(';', $value);
	          }else{
	            $arrTableData[$key] = $value;  
	          }
	        }       
	      }
	      if($this->strAttachmentFile != ''){
	        $arrTableData['attachment'] = $this->strAttachmentFile;   
	      }    
	      $objGenTable->insert($arrTableData);
      }
    }
  }
  
  /**
   * specialCourseAction
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  public function specialCourseAction(){
    $this->core->logger->debug('website->controllers->DatareceiverController->specialCourseAction()');

    if($this->getRequest()->isPost()) {
    $this->arrMailRecipients = array('Name'  => $this->core->config->mail->recipient->name,
                                     'Email' => $this->core->config->mail->recipient->address);

    $this->arrFormData = $this->getRequest()->getPost();
    if(isset($_FILES)){
      $this->arrFileData = $_FILES;
    }

    /**
     * set up zoolu translate obj
     */
    if(file_exists(GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->strLanguageCode.'.mo')){
      $this->translate = new HtmlTranslate('gettext', GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->strLanguageCode.'.mo');
    }else{
      $this->translate = new HtmlTranslate('gettext', GLOBAL_ROOT_PATH.'application/website/default/language/website-'.$this->core->sysConfig->languages->default->code.'.mo');
    }

    if(count($this->arrFormData) > 0){
      $this->arrFormFields = array('subject'                               => $this->translate->_('subject'),
                                   'full_name'                             => $this->translate->_('full_name'),
                                   'tax_code_of_the_participating_staff'   => $this->translate->_('tax_code_of_the_participating_staff'),
                                   'job'                                   => $this->translate->_('job'),
                                   'company_name_header'                   => $this->translate->_('company_name_header'),
                                   'street'                                => $this->translate->_('street'),
                                   'street_number'                         => $this->translate->_('street_number'),
                                   'zip'                                   => $this->translate->_('zip'),
                                   'city'                                  => $this->translate->_('city'),
                                   'province'                              => $this->translate->_('province'),
                                   'phone'                                 => $this->translate->_('phone'),
                                   'fax'                                   => $this->translate->_('fax'),
                                   'tax_number'                            => $this->translate->_('tax_number'),
                                   'vat_number'                            => $this->translate->_('vat_number'),
                                   'email_internet'                        => $this->translate->_('email_internet'),
                                   'studio'                                => $this->translate->_('studio'),
                                   'b_company_name_or_full_name'           => $this->translate->_('company_name_or_full_name'),
                                   'b_street'                              => $this->translate->_('street'),
                                   'b_street_number'                       => $this->translate->_('street_number'),
                                   'b_zip'                                 => $this->translate->_('zip'),
                                   'b_city'                                => $this->translate->_('city'),
                                   'b_province'                            => $this->translate->_('province'),
                                   'b_phone'                               => $this->translate->_('phone'),
                                   'b_fax'                                 => $this->translate->_('fax'),
                                   'b_tax_number'                          => $this->translate->_('tax_number'),
                                   'b_vat_number'                          => $this->translate->_('vat_number'),
                                   'b_studio'                              => $this->translate->_('studio'),
                                   'bank_name'                             => $this->translate->_('bank_name'),
                                   'iban'                                  => $this->translate->_('iban'),
                                   'hotel'                                 => $this->translate->_('hotel'),
                                   'arrival_date'                          => $this->translate->_('arrival_date'),
                                   'departure_date'                        => $this->translate->_('departure_date'),
                                   'checkLegalnotes'                       => $this->translate->_('Check_Legalnotes'));

      /**
       * set sender name and e-mail
       */
      if(array_key_exists('sender_name', $this->arrFormData) && array_key_exists('sender_mail', $this->arrFormData)){
        $this->strSenderName = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['sender_name']);
        $this->strSenderMail = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['sender_mail']);
        unset($this->arrFormData['sender_name']);
        unset($this->arrFormData['sender_mail']);
      }

      /**
       * set receiver name and e-mail
       */
      if(array_key_exists('receiver_name', $this->arrFormData) && array_key_exists('receiver_mail', $this->arrFormData)){
        $this->strReceiverName = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['receiver_name']);
        $this->strReceiverMail = Crypt::decrypt($this->core, $this->core->config->crypt->key, $this->arrFormData['receiver_mail']);

        $this->arrMailRecipients = array('Name'  => $this->strReceiverName,
                                         'Email' => $this->strReceiverMail);

        unset($this->arrFormData['receiver_name']);
        unset($this->arrFormData['receiver_mail']);
      }

      /**
       * set e-mail subject
       */
      if(array_key_exists('subject', $this->arrFormData)){
        $this->strMailSubject = $this->arrFormData['subject'];
      }

      /**
       * set redirect url
       */
      if(array_key_exists('redirectUrl', $this->arrFormData)){
        $this->strRedirectUrl = $this->arrFormData['redirectUrl'];
        unset($this->arrFormData['redirectUrl']);
      }

      /**
       * send mail
       */
      if($this->core->config->mail->actions->sendmail->client == 'true'){
        $this->sendMail(true);
      }

      /**
       * save to database
       */
      if($this->core->config->mail->actions->database == 'true'){
        $this->insertDatabase('pageRegistrationsSpecialForm');
      }

      $strUrl = (strpos($this->strRedirectUrl,'?') !== false) ? $this->strRedirectUrl.'&send=true' : $this->strRedirectUrl.'?send=true';
      $this->_redirect($strUrl);
      }
    }
  }

  /**
   * upload
   * @return string
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function upload($_FILEDATA){
    if ($_FILEDATA['error'] > 0) {
      $this->core->logger->err('website->controllers->DatareceiverController->upload(): '.$_FILEDATA['error']);
    }else{ 
      $objFile = new File();
      $objFile->setLanguageId($this->core->intLanguageId);
      $objFile->setUploadPath(GLOBAL_ROOT_PATH.$this->core->sysConfig->upload->forms->path->local->private);
      $this->strUploadPath = GLOBAL_ROOT_PATH.$this->core->sysConfig->upload->forms->path->local->private;
      $objFile->checkUploadPath(); 
      
      $arrFileInfo = array();
      $arrFileInfo = pathinfo($this->strUploadPath.$_FILEDATA['name']);
      $strFileName = $arrFileInfo['filename'];
      $strExtension = $arrFileInfo['extension'];

      $strFileName = $objFile->makeFileIdConform($strFileName);
      $strFile = $strFileName.'_'.uniqid().'.'.$strExtension;
      
      if(file_exists($this->strUploadPath.$strFile)) {
        $this->core->logger->err('website->controllers->DatareceiverController->upload(): '.$strFile.' already exists.');
      }else{
        move_uploaded_file($_FILEDATA['tmp_name'], $this->strUploadPath.$strFile);
        $this->strAttachmentFile = $strFile;         
        return $this->strUploadPath.$strFile;
      }
    }
  }
  
  /**
   * getModelGenericData
   * @return Model_GenericData
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  protected function getModelGenericData(){
    if (null === $this->objModelGenericData) {
      /**
       * autoload only handles "library" compoennts.
       * Since this is an application model, we need to require it
       * from its modules path location.
       */
      require_once GLOBAL_ROOT_PATH.$this->core->sysConfig->path->zoolu_modules.'core/models/GenericData.php';
      $this->objModelGenericData = new Model_GenericData();
    }
    return $this->objModelGenericData;
  }
}
?>