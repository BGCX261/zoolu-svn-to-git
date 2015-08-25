/**
 * media.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-06: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

Massiveart.Media = Class.create({
  
  initialize: function() { 
    this.formId = 'uploadForm';
    this.editFormId = 'editForm';
        
    this.constThumbContainer = 'divThumbViewContainer';
    this.constListContainer = 'divListViewContainer';
    this.constOverlayGenContent = 'overlayGenContent';
    this.constOverlayMediaWrapper = 'overlayMediaWrapper';
    this.constList = 'list';
    this.constThumb = 'thumb';    
    
    this.intFolderId = 0;
    this.currViewType = 0;
    this.sliderValue = 100;    
    this.constSWFUploadUI = '<div id="buttonplaceholder"></div>' +                            
                            '<div id="divStatus" class="gray666">0 Files Uploaded</div>' +
                            '<input id="btnCancel" type="button" value="Alle abbrechen" disabled="disabled" />' +
                            '<div class="clear"></div>' +
                            '<div id="overlayMediaWrapperUpload" class="mediawrapper"></div>' +
                            '<input type="hidden" id="UploadedFileIds" name="UploadedFileIds" value=""/>' +
                            '<div class="clear"></div>' +
                            '<div class="buttoncancel" onclick="$(\'overlayUpload\').hide(); $(\'overlayBlack75\').hide(); return false;">Abbrechen</div>' +  
                            '<div onclick="myMedia.updateUploadedFiles(); return false;" id="buttoneditsave">' +
                            '  <div class="button25leftOn"></div>' +
                            '  <div class="button25centerOn">' + 
                            '    <img width="13" height="13" src="/zoolu/images/icons/icon_save_black.png" class="iconsave"/>' +
                            '    <div>Speichern</div>' +
                            '  </div>' +
                            '  <div class="button25rightOn"></div>' +
                            '  <div class="clear"></div>' +
                            '</div>' +
                            '<div class="clear"></div>';
  },
  
  /**
   * initThumbHover
   */
  initThumbHover: function(){
    $$('#divThumbViewContainer .tdthumbcontainer').each(function(elDiv){ 
    
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event);        
        if(el.hasClassName('tdthumbcontainer')){
          el.addClassName('hover');
        }else{         
          el.up('.tdthumbcontainer').addClassName('hover');          
        }
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event);        
        if(el.hasClassName('tdthumbcontainer')){
          el.removeClassName('hover');
        }else{         
          el.up('.tdthumbcontainer').removeClassName('hover');         
        }
      }.bind(this));
      
      elDiv.observe('click', function(event){        
        el = Event.element(event);        
        if(el.hasClassName('tdthumbcontainer')){
          myCore.toggleItemSelected(el.id);
        }else{         
          myCore.toggleItemSelected(el.up('.tdthumbcontainer').id);         
        }        
      }.bind(this));
                 
    }.bind(this));
    
  },
  
  /**
   * scaleThumbs
   */
  scaleThumbs: function(scaleValue){
    //console.debug(scaleValue);
    
    var scaleThumbs = document.getElementsByClassName('thumb');
    currSliderValue = scaleValue;
    
    for(i=0; i < scaleThumbs.length; i++){
      newWidth = scaleThumbs[i].readAttribute('startWidth') * scaleValue / 100;
      scaleThumbs[i].style.width = newWidth+'px';
      
      $('divThumbPos'+scaleThumbs[i].id).style.width = newWidth+'px';                                                  
      
      //console.debug(scaleValue);
      
      $('divThumbContainer'+scaleThumbs[i].id).setStyle({width: ((100 * scaleValue / 100) + 10)+'px',
                                                         height: ((100 * scaleValue / 100) + 10)+'px'});
      
      $('tdThumb'+scaleThumbs[i].id).setStyle({width: (100 * scaleValue / 100)+'px',
                                               height: (100 * scaleValue / 100)+'px'});
    } 
  },
  
  /**
   * getMediaFolderContent
   */
  getMediaFolderContent: function(folderId, viewType){
    
    var view;
    var strAjaxAction;
    
    this.sliderValue = Math.round(currSliderValue);
      
    if(typeof(viewType) == 'undefined' || viewType == ''){
      view = this.currViewType;
    }else{
      view = viewType;
      this.currViewType = viewType;
    }
        
    if(view == this.constList){
      updateDiv = this.constListContainer;
      strAjaxAction = '/zoolu/media/view/list';
      this.toggleMediaViewIcons(this.constList);    	  
	  }else{
	    updateDiv = this.constThumbContainer;
	    strAjaxAction = '/zoolu/media/view/thumb';
	    this.toggleMediaViewIcons(this.constThumb); 
	  }
	  
	  $(updateDiv).show();
	  $(this.constThumbContainer).innerHTML = '';
	  $(this.constListContainer).innerHTML = '';
    
    if($('divMediaContainer')) $('divMediaContainer').show();
    if($('divFormContainer')) $('divFormContainer').hide();

    myCore.addBusyClass(updateDiv);
    
    new Ajax.Updater(updateDiv, strAjaxAction, {
      parameters: { 
        folderId: folderId,
        sliderValue: Math.round(currSliderValue) 
      },
      evalScripts: true,     
      onComplete: function() {        
        this.intFolderId = folderId;
        this.initThumbHover();        
        myCore.removeBusyClass(updateDiv);
        myCore.initListHover();
        myCore.initSelectAll();                   
      }.bind(this)
    });

  },
  
  /**
   * getMediaListView
   */
  getMediaListView: function(){
    $(this.constThumbContainer).hide();
    $(this.constListContainer).show();
    if(!($('divListView').readAttribute('class').indexOf('_on') == -1)){
      this.getMediaFolderContent(this.intFolderId, this.constList);
    }
    this.toggleMediaViewIcons(this.constList);
    myCore.initListHover();    
  },
  
  /**
   * getMediaThumbView
   */
  getMediaThumbView: function(){
    $(this.constThumbContainer).show();
    $(this.constListContainer).hide();
    if(!($('divThumbView').readAttribute('class').indexOf('_on') == -1)){
      this.getMediaFolderContent(this.intFolderId, this.constThumb);
    }
    this.toggleMediaViewIcons(this.constThumb);
    this.initThumbHover();    
  },
  
  /**
   * toggleMediaViewIcons
   */
  toggleMediaViewIcons: function(viewType){    
    if(viewType != this.constList){
      $('divThumbView').removeClassName('iconthumbview_on');
	    $('divThumbView').addClassName('iconthumbview');	    
	    $('divListView').removeClassName('iconlistview');
	    $('divListView').addClassName('iconlistview_on');
	    $('mediaslider').show();
    }else{
      $('divThumbView').removeClassName('iconthumbview');
	    $('divThumbView').addClassName('iconthumbview_on');	    
	    $('divListView').removeClassName('iconlistview_on');
	    $('divListView').addClassName('iconlistview');
	    $('mediaslider').hide();
    }    
  },
  
  /**
   * toggleMediaEditMenu
   */
  toggleMediaEditMenu: function(elementId){    
    if($('divMediaEditMenu')){
      Effect.toggle('divMediaEditMenu', 'appear', { delay: 0, duration: 0.3 });
      if($(elementId) && $(elementId).hasClassName('white')){
        $(elementId).removeClassName('white');
      }else{
        $(elementId).addClassName('white');
      }
    }
  },
  
  /**
   * getUploadWidget
   */
  getUploadWidget: function(){
    this.initSWFUpload();
  },
  
  /**
   * initSWFUpload
   */
  initSWFUpload: function(){
    
    $('divSWFUploadUI').update(this.constSWFUploadUI);
        
    var settings = {
      flash_url : "/zoolu/flash/swfupload/swfupload.swf",
      upload_url: "/zoolu/media/upload",
      post_params: {
        PHPSESSID: sessionId, 
        folderId: myNavigation.parentFolderId
      }, 
      file_size_limit : "100 MB",
      file_types : "*.*",
      file_types_description : "All Files",
      file_upload_limit : 100,
      file_queue_limit : 0,
      custom_settings : {
        progressTarget : "overlayMediaWrapperUpload",
        cancelButtonId : "btnCancel"
      },
      debug: false,
  
      // Button Settings
      button_image_url : "/zoolu/images/buttons/button_selectfiles_de.png",
      button_placeholder_id : "buttonplaceholder",
      button_width: 113,
      button_height: 25,
      button_cursor: -2,
      button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
  
      // The event handler functions are defined in handlers.js
      swfupload_loaded_handler : swfUploadLoaded,
      file_queued_handler : fileQueued,
      file_queue_error_handler : fileQueueError,
      file_dialog_complete_handler : fileDialogComplete,
      upload_start_handler : uploadStart,
      upload_progress_handler : uploadProgress,
      upload_error_handler : uploadError,
      upload_success_handler : uploadSuccess,
      upload_complete_handler : uploadComplete,
      queue_complete_handler : queueComplete, // Queue plugin event
      
      // SWFObject settings
      minimum_flash_version : "9.0.28",
      swfupload_pre_load_handler : swfUploadPreLoad,
      swfupload_load_failed_handler : swfUploadLoadFailed
    };

    swfu = new SWFUpload(settings);
    
    myCore.calcMaxOverlayHeight('overlayMediaWrapperUpload', true);
    myCore.putOverlayCenter('overlayUpload');
    $('overlayUpload').show();
    $('overlayBlack75').show();
  },
  
  /**
   * addUploadedFileId
   */
  addUploadedFileId: function(fileId){    
    if($('UploadedFileIds')){ 
      $('UploadedFileIds').value = $('UploadedFileIds').value + '[' + fileId + ']';
    }    
    if($('btnSave')){
      $('btnSave').disabled = false;
    }
  },
  
  /**
   * updateUploadedFiles
   */
  updateUploadedFiles: function(){
    
    if($(this.formId)){
      
      var arrFields = $(this.formId).getElements();
      arrFields.each(function(el){
        if($F(el.id) == fileDefaultDescription){
          $(el.id).value = '';
        }
      }.bind(this));
         
      /**
       * serialize upload form
       */
      var serializedForm = $(this.formId ).serialize();
      var strAjaxAction = $(this.formId).readAttribute('action') + '/save';
      
      new Ajax.Updater(this.constThumbContainer, strAjaxAction, {
        parameters: serializedForm,
        evalScripts: true,
        onComplete: function() {       
          this.getMediaFolderContent(this.intFolderId);
          $('overlayUpload').hide();
          $('overlayBlack75').hide();  
        }.bind(this)
      });
    }   
    
  },
  
  /**
   * getFilesEditForm
   */
  getFilesEditForm: function(){
    $(this.constOverlayGenContent).innerHTML = '';
    
    var strFileIds = this.getStringFileIds();
   
    if(strFileIds != ''){
	    myCore.addBusyClass(this.constOverlayGenContent);
      
      myCore.putCenter('overlayGenContentWrapper');
      
      $('overlayBlack75').show();
      $('overlayGenContentWrapper').show();
      
      new Ajax.Updater(this.constOverlayGenContent, '/zoolu/media/file/geteditform', {
        parameters: { fileIds: strFileIds },
        evalScripts: true,
        onComplete: function() {
          myCore.calcMaxOverlayHeight(this.constOverlayMediaWrapper, true);
          myCore.putOverlayCenter('overlayGenContentWrapper');          
          myCore.removeBusyClass(this.constOverlayGenContent);                    
          this.toggleMediaEditMenu('buttonmediaedittitle');                   
        }.bind(this)
      });
    }   
  },
  
  /**
   * getSingleFileEditForm
   */
  getSingleFileEditForm: function(fileId){
    $(this.constOverlayGenContent).innerHTML = '';
   
    if(fileId != ''){
      myCore.addBusyClass(this.constOverlayGenContent);      
      myCore.putCenter('overlayGenContentWrapper');
      
      $('overlayBlack75').show();
      $('overlayGenContentWrapper').show();
      
      new Ajax.Updater(this.constOverlayGenContent, '/zoolu/media/file/geteditform', {
        parameters: { fileIds: fileId },
        evalScripts: true,
        onComplete: function() {
          myCore.calcMaxOverlayHeight(this.constOverlayMediaWrapper, true);
          myCore.putOverlayCenter('overlayGenContentWrapper');          
          myCore.removeBusyClass(this.constOverlayGenContent);                    
          this.toggleMediaEditMenu('buttonmediaedittitle');                   
        }.bind(this)
      });
    }   
  },
  
  /**
   * editFiles
   */
  editFiles: function(){
    
    if($(this.editFormId)){      
      
      var arrFields = $(this.editFormId).getElements();
      arrFields.each(function(el){
        if($F(el.id) == fileDefaultDescription){
          $(el.id).value = '';
        }
      }.bind(this));
      
      /**
       * serialize generic form
       */
      var serializedForm = $(this.editFormId).serialize();
      
      new Ajax.Request($(this.editFormId).readAttribute('action'), {
        parameters: serializedForm,
        onComplete: function(transport) {          
          this.getMediaFolderContent(this.intFolderId);
          $('overlayBlack75').hide();
          $('overlayGenContentWrapper').hide();
        }.bind(this)
      });
          
    }
    
  },
  
  /**
   * deleteFiles
   */
  deleteFiles: function(){    
    
    var strFileIds = this.getStringFileIds();
    
    if(strFileIds != ''){
      new Ajax.Updater(this.constThumbContainer, '/zoolu/media/file/delete', {
        parameters: { fileIds: strFileIds },
        evalScripts: true,
        onComplete: function() {       
          this.toggleMediaEditMenu('buttonmediaedittitle');
          this.getMediaFolderContent(this.intFolderId);          
        }.bind(this)
      });
    }    
  },
  
  /**
   * getStringFileIds
   */
  getStringFileIds: function(){
    
    var strFileIds = '';
    $$('.contentview .selected').each(function(element){ 
      strFileIds = strFileIds + '[' + element.readAttribute('fileid') + ']';      
    }.bind(this));
    
    return strFileIds;    
  },
  
  /**
   * setFocusTextarea
   * @param string elementId
   */
  setFocusTextarea: function(elementId){
    if($(elementId)){    
      if($(elementId).hasClassName('textarea') == false){
        $(elementId).innerHTML = '';
        $(elementId).addClassName('textarea');   
      }    
    }
  }
  
});