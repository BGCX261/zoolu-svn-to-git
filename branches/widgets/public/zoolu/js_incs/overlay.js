/**
 * overlay.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-11-24: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Massiveart.Overlay = Class.create({
  
  initialize: function() {
    this.updateContainer = 'olContent';
    this.myDraggables = [];
    this.myDroppables = [];
    
    this.areaId;
    this.fieldId;
  },
  
  /**
   * addItemToThumbArea
   * @param string itemId, integer id
   */
  addItemToThumbArea: function(itemId, id){
    //alert(this.areaId + ' :: ' + itemId + ' :: ' + id);
    
    if($(this.areaId) && $(itemId)){
      
      // get the hidden field id
      var fieldId = this.areaId.substring(this.areaId.indexOf('_')+1);
      var iconRemoveId = fieldId+'_remove'+id;
      
      // create new media item container
      var mediaItemContainer = '<div id="'+fieldId+'_mediaitem'+id+'" fileid="'+id+'" class="mediaitem" style="display:none; position:relative;">' + $(itemId).innerHTML + '</div>'; 
      if($('divClear_'+fieldId)) $('divClear_'+fieldId).remove();
      new Insertion.Bottom(this.areaId, mediaItemContainer + '<div id="divClear_'+fieldId+'" class="clear"></div>');
      
      if($('Img'+id)) $('Img'+id).removeAttribute('onclick');
      if($('Remove'+id)) $('Remove'+id).writeAttribute('id', iconRemoveId);
           
      // insert file id to hidden field - only 1 insert is possible
      if($(fieldId).value.indexOf('[' + id + ']') == -1){
        $(fieldId).value = $(fieldId).value + '[' + id + ']';
      } 
      
      $(fieldId+'_mediaitem'+id).appear({ duration: 0.5 });
      $(itemId).fade({ duration: 0.5 });
      
      // add remove method to remove icon
      if($(iconRemoveId)){
        $(iconRemoveId).show();
        $(iconRemoveId).onclick = function(){
          myForm.removeItem(fieldId, fieldId+'_mediaitem'+id, id);
        }
      }
    }
    
  },
  
  /**
   * addItemToListArea
   * @param string itemId, integer id
   */
  addItemToListArea: function(itemId, id){
        
    if($(this.areaId) && $(itemId)){
      
      // get the hidden field id
      var fieldId = this.areaId.substring(this.areaId.indexOf('_')+1);
      var iconRemoveId = fieldId+'_remove'+id;
      
      // create new media item container
      var mediaItemContainer = '<div id="'+fieldId+'_docitem'+id+'" fileid="'+id+'" class="docitem" style="display:none;">' + $(itemId).innerHTML + '</div>'; 
      if($('divClear_'+fieldId)) $('divClear_'+fieldId).remove();
      new Insertion.Bottom(this.areaId, mediaItemContainer + '<div id="divClear_'+fieldId+'" class="clear"></div>');
      
      if($('Doc'+id)) $('Doc'+id).removeAttribute('onclick');
      if($('Remove'+id)) $('Remove'+id).writeAttribute('id', iconRemoveId);
           
      // insert file id to hidden field - only 1 insert is possible
      if($(fieldId).value.indexOf('[' + id + ']') == -1){
        $(fieldId).value = $(fieldId).value + '[' + id + ']';
      } 
      
      $(fieldId+'_docitem'+id).appear({ duration: 0.5 });
      $(itemId).fade({ duration: 0.5 });
      
      // add remove method to remove icon
      if($(iconRemoveId)){
        $(iconRemoveId).show();
        $(iconRemoveId).onclick = function(){
          myForm.removeItem(fieldId, fieldId+'_docitem'+id, id);
        }
      }
    }    
  },
  
  /**
   * selectPage
   * @param integer pageId
   */
  selectPage: function(pageId){
    
    myCore.addBusyClass('overlayGenContent');
    
    if('divLinkedPage_'+this.fieldId){
      new Ajax.Updater('divLinkedPage_'+this.fieldId, '/zoolu/cms/page/linkedpagefield', {
        parameters: { 
         pageId: pageId,
         fieldId: this.fieldId, 
         formId: $F('formId'),
         formVersion: $F('formVersion')
        },      
        evalScripts: true,     
        onComplete: function() {  
          $('overlayGenContentWrapper').hide(); 
          $('overlayBlack75').hide();                
          myCore.removeBusyClass('overlayGenContent'); 
          $('overlayGenContentWrapper').setStyle({width: '410px'});     
        }.bind(this)
      });
    }
  },
  
  /**
   * getNavItem
   * @param integer folderId, integer viewtype
   */
  getNavItem: function(folderId, viewtype){
    
    if($('olsubnav'+folderId)){
      this.toggleSubNavItem(folderId);
      this.getMediaFolderContent(folderId, viewtype); 
    }else{
      if(folderId != ''){
	      var subNavContainer = '<div id="olsubnav'+folderId+'" style="display:none;"></div>'; 
        new Insertion.Bottom('olnavitem'+folderId, subNavContainer);
	      
	      this.toggleSubNavItem(folderId);
	      myCore.addBusyClass('olsubnav'+folderId);
	      
	      new Ajax.Updater('olsubnav'+folderId, '/zoolu/cms/overlay/childnavigation', {
	        parameters: { 
	         folderId: folderId, 
	         viewtype: viewtype
	        },      
	        evalScripts: true,     
	        onComplete: function() {        
	          this.getMediaFolderContent(folderId, viewtype);
	          myCore.removeBusyClass('olsubnav'+folderId);      
	        }.bind(this)
	      });
	    } 
    }
  },
  
  /**
   * getContactNavItem
   * @param integer unitId
   */
  getContactNavItem: function(unitId){
    
    if($('olsubnav'+unitId)){
      this.toggleSubNavItem(unitId);
      this.getUnitFolderContent(unitId); 
    }else{
      if(unitId != ''){
        var subNavContainer = '<div id="olsubnav'+unitId+'" style="display:none;"></div>'; 
        new Insertion.Bottom('olnavitem'+unitId, subNavContainer);
        
        this.toggleSubNavItem(unitId);
        myCore.addBusyClass('olsubnav'+unitId);
        
        new Ajax.Updater('olsubnav'+unitId, '/zoolu/cms/overlay/unitchilds', {
          parameters: { 
           unitId: unitId
          },      
          evalScripts: true,     
          onComplete: function() {        
            this.getUnitFolderContent(unitId);
            myCore.removeBusyClass('olsubnav'+unitId);      
          }.bind(this)
        });
      } 
    }
  },
  
  /**
   * getMediaFolderContent
   * @param integer folderId, integer viewtype
   */
  getMediaFolderContent: function(folderId, viewtype){
    var strAjaxAction = "";
    
    if(folderId != ''){
      $(this.updateContainer).innerHTML = '';
      myCore.addBusyClass(this.updateContainer);
      
      if(viewtype == 1){
        strAjaxAction = '/zoolu/cms/overlay/thumbview';
      } else {
        strAjaxAction = '/zoolu/cms/overlay/listview';
      }
      
      var fieldname = this.areaId.substring(this.areaId.indexOf('_')+1);
      new Ajax.Updater(this.updateContainer, strAjaxAction, {
       parameters: { 
         folderId: folderId, 
         fileIds: $(fieldname).value 
       },
       evalScripts: true,     
       onComplete: function() {        
         myCore.removeBusyClass(this.updateContainer);                    
       }.bind(this)
     });
    }
  },
  
  /**
   * getUnitFolderContent
   * @param integer unitId
   */
  getUnitFolderContent: function(unitId){
    var strAjaxAction = "";
     
    $(this.updateContainer).innerHTML = '';
    myCore.addBusyClass(this.updateContainer);
          
    var fieldname = this.areaId.substring(this.areaId.indexOf('_')+1);
    new Ajax.Updater(this.updateContainer, '/zoolu/cms/overlay/contactlist', {
     parameters: { 
       unitId: unitId, 
       fileIds: $(fieldname).value 
     },
     evalScripts: true,     
     onComplete: function() {        
       myCore.removeBusyClass(this.updateContainer);                    
     }.bind(this)
    });
  },
  
  /**
   * toggleSubNavItem
   * @param integer itemId
   */
  toggleSubNavItem: function(itemId){    
    if($('olsubnav'+itemId)){
      $('olsubnav'+itemId).toggle();
      
      if($('olnavitem'+itemId)){
	      if($('olnavitem'+itemId).down('.icon').hasClassName('img_folder_off')){
	        $('olnavitem'+itemId).down('.icon').removeClassName('img_folder_off');
	        $('olnavitem'+itemId).down('.icon').addClassName('img_folder_on');
	      }else{
	        $('olnavitem'+itemId).down('.icon').removeClassName('img_folder_on');
          $('olnavitem'+itemId).down('.icon').addClassName('img_folder_off');
	      }
	    }
    }         
  }
  
});