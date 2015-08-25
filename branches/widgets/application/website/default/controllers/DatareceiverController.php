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
   * @var Model_GenericData
   */
  protected $objModelGenericData;
  
  protected $arrFormData = array();
  protected $arrMailRecipients = array();
  
  protected $strUserFName;
  protected $strUserSName;
  protected $strUserMail;
  
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
  	
  	if($this->getRequest()->isPost() && $this->getRequest()->isXmlHttpRequest()) {
	  	$this->arrMailRecipients[] = array('Name'  => $this->core->webConfig->mail->recipient->name,
                                         'Email' => $this->core->webConfig->mail->recipient->address);
  		
  		$this->arrFormData = $this->getRequest()->getPost();
	  	
	  	if(count($this->arrFormData) > 0){
	  	  /**
	  	   * send mail
	  	   */
	  		if(array_key_exists($this->core->webConfig->mail->hiddenfields->mail, $this->arrFormData) && $this->arrFormData[$this->core->webConfig->mail->hiddenfields->mail] == 'true'){
	  	    $this->sendMail();	
	  	  }
	  	  /**
         * save to database
         */
        if(array_key_exists($this->core->webConfig->mail->hiddenfields->database, $this->arrFormData) && $this->arrFormData[$this->core->webConfig->mail->hiddenfields->database] == 'true'){
          $this->insertDatabase();  
        }
	  	}
  	}
  }
  
  /**
   * sendMail
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function sendMail(){
  	$this->core->logger->debug('website->controllers->DatareceiverController->sendMail()');
      
    $mail = new Zend_Mail();
    
    /**
     * config for SMTP with auth
     */
    $config = array('auth'     => 'login',
                    'username' => $this->core->webConfig->mail->params->username,
                    'password' => $this->core->webConfig->mail->params->password);
    
    /**
     * SMTP
     */
    $transport = new Zend_Mail_Transport_Smtp($this->core->webConfig->mail->params->host, $config);
  		
	  $strHtmlBody = '';
	   
	  if(count($this->arrFormData) > 0){
	    $strHtmlBody = '
		    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
				<html>
				<head>
				  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
				  <title></title>
				  <style type="text/css">
				    body { margin:0; padding:0; color:#000; width:100%; height:100%; font-size:11px; font-family:Verdana, Arial, Sans-Serif; background-color:#ffffff; line-height:15px;}
				    input {font-size:11px; font-family:Verdana, Arial, Sans-Serif; }
				    span { line-height:14px; font-size:11px; }
				    img { padding:0; margin:0; border:0; }
				    .tdImg {width:123px; margin:0; padding:0; vertical-align:top; }
				    .divider { margin:0; padding:5px 0 15px 0; width:620px; }
				    h1 { color:#000; font-weight:bold; font-size:16px; font-family:Verdana, Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
				    h2 { color:#000; font-weight:bold; font-size:14px; font-family:Verdana, Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
				    h3 { color:#000; font-weight:bold; font-size:12px; font-family:Verdana, Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
				    a { color:#3366cc; font-size:11px; text-decoration:none; margin:0; padding:0; }
				    a:hover { color:#000; font-size:11px; text-decoration:none; margin:0; padding:0; }
				    p { margin:0 0 10px 0; padding:0; }
				  </style>
				</head>
				<body>
				<table cellpadding="0" cellspacing="0" style="width:650px; margin:auto;">
				   <tr>
				      <td style="padding:20px 15px 20px 15px;">
				         <table border="0" cellpadding="0" cellspacing="0" width="100%">
				            <tr>
				               <td>';
	    // TODO : generic inclusion (now: quick and dirty solution for sportservice)
      foreach($this->arrFormData as $key => $value){
     	  if($value != ''){
	     	  if($key == 'title'){
	          $strHtmlBody .= '<h1>'.utf8_decode($value).'</h1>';  
	        }
	        else if($key == 'idPage' || $key == $this->core->webConfig->mail->hiddenfields->mail || $key == $this->core->webConfig->mail->hiddenfields->database || $key == $this->core->webConfig->mail->hiddenfields->dbtable){
	          // do nothing
	        }else{
	          $strHtmlBody .= '<strong>'.ucfirst(utf8_decode($key)).':</strong> '.utf8_decode($value).'<br/>'; 
	        }	
     	  }      	
      }	
    	    
	    $strHtmlBody .= '</td>
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
    $mail->setSubject('Anmeldung - '.utf8_decode($this->arrFormData['title']));
    /**
     * set html body
     */
    $mail->setBodyHtml($strHtmlBody);
    /**
     * set default FROM address
     */
    $mail->setFrom($this->core->webConfig->mail->from->address, $this->core->webConfig->mail->from->name);
		
    /**
     * set TO address
     */
    if(array_key_exists('mail', $this->arrFormData)){
      $this->strUserFName = $this->arrFormData['fname'];
      $this->strUserSName = $this->arrFormData['sname'];
      $this->strUserMail = $this->arrFormData['mail'];
    }
    
    if(count($this->arrMailRecipients) > 0){
      foreach($this->arrMailRecipients as $arrRecipient){
      	$mail->clearRecipients();
      	$mail->addTo($arrRecipient['Email'], $arrRecipient['Name']);
		    /**
		     * send mail if mail body is not empty
		     */
		    if($strHtmlBody != ''){
		      $mail->send($transport);
		      $this->sendConfirmationMail();
		    }	
      }	
    }
  }
  
  /**
   * sendSuccesMail
   * @author Cornelius Hansjakob <cha@massiveart.com>
   * @version 1.0
   */
  private function sendConfirmationMail(){
    $this->core->logger->debug('website->controllers->DatareceiverController->sendConfirmationMail()');
      
    $mail = new Zend_Mail();
    
    /**
     * config for SMTP with auth
     */
    $config = array('auth'     => 'login',
                    'username' => $this->core->webConfig->mail->params->username,
                    'password' => $this->core->webConfig->mail->params->password);
    
    /**
     * SMTP
     */
    $transport = new Zend_Mail_Transport_Smtp($this->core->webConfig->mail->params->host, $config);
    
    $strHtmlBody = '';
    
    if($this->strUserFName != '' && $this->strUserSName != ''){
      $strHtmlBody = '
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
        <html>
        <head>
          <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
          <title></title>
          <style type="text/css">
            body { margin:0; padding:0; color:#000; width:100%; height:100%; font-size:11px; font-family:Verdana, Arial, Sans-Serif; background-color:#ffffff; line-height:15px;}
            input {font-size:11px; font-family:Verdana, Arial, Sans-Serif; }
            span { line-height:14px; font-size:11px; }
            img { padding:0; margin:0; border:0; }
            .tdImg {width:123px; margin:0; padding:0; vertical-align:top; }
            .divider { margin:0; padding:5px 0 15px 0; width:620px; }
            h1 { color:#000; font-weight:bold; font-size:16px; font-family:Verdana, Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
            h2 { color:#000; font-weight:bold; font-size:14px; font-family:Verdana, Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
            h3 { color:#000; font-weight:bold; font-size:12px; font-family:Verdana, Arial, Sans-Serif; padding:0; margin: 20px 0 15px 0; }
            a { color:#3366cc; font-size:11px; text-decoration:none; margin:0; padding:0; }
            a:hover { color:#000; font-size:11px; text-decoration:none; margin:0; padding:0; }
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
                         Vielen Dank f�r Ihre Anmeldung. Diese ist bei uns eingelangt und wir haben
                         Ihren Namen auf die Teilnehmerliste gesetzt. Bei R�ckfragen oder etwaigen �nderungen
                         wird sich der/die zust�ndige Mitarbeiter/in in den n�chsten Tagen mit Ihnen in
                         Verbindung setzen.<br/>
                         Ansonsten stehen wir Ihnen auch gerne telefonisch unter 05572/24465-400 f�r
                         weitere Ausk�nfte zur Verf�gung.<br/><br/>
                         Mit freundlichen Gr��en<br/>
                         Ihr Sportservice-Team<br/><br/><br/>
                         <strong>Sportservice Vorarlberg</strong>, H�chsterstra�e 82, 6850 Dornbirn<br/>
                         <a href="http://www.sportservice-v.at">www.sportservice-v.at</a>, <a href="mailto:info@sportservice-v.at">info@sportservice-v.at</a>, +43 (0)5572 / 244 65 - 400
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
    $mail->setSubject('Anmeldung - '.utf8_decode($this->arrFormData['title']));
    /**
     * set html body
     */
    $mail->setBodyHtml($strHtmlBody);
    /**
     * set default FROM address
     */
    $mail->setFrom($this->core->webConfig->mail->from->address, $this->core->webConfig->mail->from->name);
      
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
  private function insertDatabase(){
    $this->core->logger->debug('website->controllers->DatareceiverController->insertDatabase()');

    if(count($this->arrFormData) > 0){
      if(array_key_exists($this->core->webConfig->mail->hiddenfields->dbtable, $this->arrFormData)){
        $this->core->logger->debug('dbtable: '.$this->arrFormData[$this->core->webConfig->mail->hiddenfields->dbtable]);
      	
      	$objGenTable = $this->getModelGenericData()->getGenericTable($this->arrFormData[$this->core->webConfig->mail->hiddenfields->dbtable]);
      	
      	$arrTableData = array();
	      foreach($this->arrFormData as $key => $value){
	        if($value != ''){
	          if($key == $this->core->webConfig->mail->hiddenfields->mail || $key == $this->core->webConfig->mail->hiddenfields->database || $key == $this->core->webConfig->mail->hiddenfields->dbtable){
	            // do nothing
	          }else{
	            $arrTableData[$key] = $value; 
	          } 
	        }       
	      }
	      $this->core->logger->debug($arrTableData);	      
	      $objGenTable->insert($arrTableData);
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