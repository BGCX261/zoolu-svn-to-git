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
    
    this.activeTabId = null;
    
    this.areaViewType = new Object();
    this.viewtype = null;
    this.lastFolderId = null;
    this.overlayCounter = 0;
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
      //var mediaItemContainer = '<div id="'+fieldId+'_mediaitem_'+id+'" fileid="'+id+'" class="mediaitem" style="display:none; position:relative;">' + $(itemId).innerHTML + '</div>';
      var imgStr = $(itemId).down('img').up().innerHTML;
      imgStr = imgStr.replace('icon32', 'thumb');
      imgStr = imgStr.replace('="32"', '="100"');
      imgStr = imgStr.replace('=32', '=100');
      
      var mediaItemContainer = '<div id="'+fieldId+'_mediaitem_'+id+'" fileid="'+id+'" class="mediaitem" style="display:none; position:relative;"><table><tbody><tr><td>' + imgStr + '</td></tr></tbody></table><div id="'+iconRemoveId+'" class="itemremovethumb" style="display:none;"></div></div>';
      if($('divClear_'+fieldId)) $('divClear_'+fieldId).remove();
      new Insertion.Bottom(this.areaId, mediaItemContainer + '<div id="divClear_'+fieldId+'" class="clear"></div>');
      
      if($('Img'+id)) $('Img'+id).removeAttribute('onclick');
      //if($('Remove'+id)) $('Remove'+id).writeAttribute('id', iconRemoveId);
           
      // insert file id to hidden field - only 1 insert is possible
      if($(fieldId).value.indexOf('[' + id + ']') == -1){
        $(fieldId).value = $(fieldId).value + '[' + id + ']';
      } 
      
      // add the scriptaculous sortable funcionality to the parent container
      myForm.initSortable(fieldId, this.areaId, 'mediaitem', 'div', 'fileid','both'); 
      
      $(fieldId+'_mediaitem_'+id).appear({duration: 0.5});
      $(itemId).fade({duration: 0.5});
      
      // add remove method to remove icon
      if($(iconRemoveId)){
        $(iconRemoveId).show();
        $(iconRemoveId).onclick = function(){
          myForm.removeItem(fieldId, fieldId+'_mediaitem_'+id, id);
        }
      }
    }
    
  },
  
  /**
   * addFileItemToListArea
   * @param string itemId, integer id
   */
  addFileItemToListArea: function(itemId, id){
        
    if($(this.areaId) && $(itemId)){
      
      // get the hidden field id
      var fieldId = this.areaId.substring(this.areaId.indexOf('_')+1);
      var iconRemoveId = fieldId+'_remove'+id;
      
      // create new media item container
      var mediaItemContainer = '<div id="'+fieldId+'_fileitem_'+id+'" fileid="'+id+'" class="fileitem" style="display:none;">' + $(itemId).innerHTML + '</div>'; 
      if($('divClear_'+fieldId)) $('divClear_'+fieldId).remove();
      new Insertion.Bottom(this.areaId, mediaItemContainer + '<div id="divClear_'+fieldId+'" class="clear"></div>');
      
      if($('File'+id)) $('File'+id).removeAttribute('onclick');
      if($('Remove'+id)) $('Remove'+id).writeAttribute('id', iconRemoveId);
           
      // insert file id to hidden field - only 1 insert is possible
      if($(fieldId).value.indexOf('[' + id + ']') == -1){
        $(fieldId).value = $(fieldId).value + '[' + id + ']';
      }
      // add the scriptaculous sortable funcionality to the parent container
      myForm.initSortable(fieldId, this.areaId, 'fileitem', 'div', 'fileid','vertical'); 
            
      $(fieldId+'_fileitem_'+id).appear({duration: 0.5});
      $(itemId).fade({duration: 0.5});
      
      // add remove method to remove icon
      if($(iconRemoveId)){
        $(iconRemoveId).show();
        $(iconRemoveId).onclick = function(){
          myForm.removeItem(fieldId, fieldId+'_fileitem_'+id, id);
        }
      }
    }    
  },
  
  /**
   * addContactItemToListArea
   * @param string itemId, integer id
   */
  addContactItemToListArea: function(itemId, id){
        
    if($(this.areaId) && $(itemId)){
      
      // get the hidden field id
      var fieldId = this.areaId.substring(this.areaId.indexOf('_')+1);
      var iconRemoveId = fieldId+'_remove'+id;
      
      // create new media item container
      var mediaItemContainer = '<div id="'+fieldId+'_contactitem_'+id+'" fileid="'+id+'" class="contactitem" style="display:none;">' + $(itemId).innerHTML + '</div>'; 
      if($('divClear_'+fieldId)) $('divClear_'+fieldId).remove();
      new Insertion.Bottom(this.areaId, mediaItemContainer + '<div id="divClear_'+fieldId+'" class="clear"></div>');
      
      if($('Contact'+id)) $('Contact'+id).removeAttribute('onclick');
      if($('Remove'+id)) $('Remove'+id).writeAttribute('id', iconRemoveId);
           
      // insert file id to hidden field - only 1 insert is possible
      if($(fieldId).value.indexOf('[' + id + ']') == -1){
        $(fieldId).value = $(fieldId).value + '[' + id + ']';
      }
      // add the scriptaculous sortable funcionality to the parent container
      myForm.initSortable(fieldId, this.areaId, 'contactitem', 'div', 'fileid','vertical'); 
            
      $(fieldId+'_contactitem_'+id).appear({duration: 0.5});
      $(itemId).fade({duration: 0.5});
      
      // add remove method to remove icon
      if($(iconRemoveId)){
        $(iconRemoveId).show();
        $(iconRemoveId).onclick = function(){
          myForm.removeItem(fieldId, fieldId+'_contactitem_'+id, id);
        }
      }
    }    
  },

  /**
   * selectDocumentFolders
   */
  selectDocumentFolders: function(){

    if($(this.areaId) && $(this.fieldId+'_Folders') && $('foderCheckboxTreeForm')){
      foldersFieldId = this.fieldId+'_Folders';
      $(this.areaId).innerHTML = '';
      $(foldersFieldId).value = '';

      if($(this.fieldId+'_RootLevel')){
        if($('rootLevelFolderCheckboxTree') && $('rootLevelFolderCheckboxTree').checked){
          $(this.fieldId+'_RootLevel').value = $F('rootLevelFolderCheckboxTree');
          $(this.areaId).update($('rootLevelFolderCheckboxTreeTitle').innerHTML);
        }else{
          $(this.fieldId+'_RootLevel').value = -1;
        }
      }      

      $('foderCheckboxTreeForm').getInputs('checkbox', 'folderCheckboxTree[]').each(function(el) {
        if(el.checked){
          $(foldersFieldId).value = $F(foldersFieldId) + '[' + $F(el) + ']';
          
          if($(this.areaId).innerHTML.blank()){
            $(this.areaId).update($('folderCheckboxTreeTitle-' + $F(el)).innerHTML);
          }else{
            $(this.areaId).update($(this.areaId).innerHTML + ', ' + $('folderCheckboxTreeTitle-' + $F(el)).innerHTML);
          }
        }
      }.bind(this));

      $('overlayGenContentWrapper').hide();
      myForm.loadFileFilterFieldContent(this.fieldId, 'documentFilter');
    }
  },
  
  /**
   * selectPage
   * @param integer idPage
   * @param string pageId
   */
  selectPage: function(idPage, pageId){
    
    myCore.addBusyClass('overlayGenContent');
    
    if('divLinkedPage_'+this.fieldId){
      new Ajax.Updater('divLinkedPage_'+this.fieldId, '/zoolu/cms/page/linkedpagefield', {
        parameters: { 
         pageId: idPage,
         fieldId: this.fieldId, 
         formId: $F('formId'),
         formVersion: $F('formVersion'),
         languageId: $F('languageId'),
         rootLevelLanguageId: ($('rootLevelLanguageId'+myNavigation.rootLevelId)) ? $F('rootLevelLanguageId'+myNavigation.rootLevelId) : ''
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
   * addPageToListArea
   * @param integer idPage
   * @param string itemId
   */
  addPageToListArea: function(id, itemId){
        
    itemElementId = 'olItem'+itemId;
    
    if($(this.areaId) && $(itemElementId)){
      
      // get the hidden field id
      var fieldId = this.areaId.substring(this.areaId.indexOf('_')+1);
      var iconRemoveId = fieldId+'_remove'+id;
      
      // create new media item container
      var mediaItemContainer = '<div id="'+fieldId+'_item_'+itemId+'" itemid="'+itemId+'" class="elementitem" style="display:none;">' + $(itemElementId).innerHTML + '</div>';
      if($('divClear_'+fieldId)) $('divClear_'+fieldId).remove();
      new Insertion.Bottom(this.areaId, mediaItemContainer + '<div id="divClear_'+fieldId+'" class="clear"></div>');
      
      if($('Item'+id)){
        $('Item'+id).removeAttribute('onclick');
        $('Item'+id).removeAttribute('style');
      }
      if($('Remove'+id)) $('Remove'+id).writeAttribute('id', iconRemoveId);
           
      // add the scriptaculous sortable funcionality to the parent container
      myForm.initSortable(fieldId, this.areaId, 'elementitem', 'div', 'itemid', 'vertical');
      
      // insert file id to hidden field - only 1 insert is possible
      if($(fieldId).value.indexOf('[' + itemId + ']') == -1){
        $(fieldId).value = $(fieldId).value + '[' + itemId + ']';
      } 
      
      $(fieldId+'_item_'+itemId).appear({duration: 0.5});
      $(itemElementId).fade({duration: 0.5});
      
      // add remove method to remove icon
      if($(iconRemoveId)){
        $(iconRemoveId).show();
        $(iconRemoveId).onclick = function(){
          myForm.removeItem(fieldId, fieldId+'_item_'+itemId, itemId);
        }
      }
    }    
  },

  /**
   * addElementToListArea
   * @param integer idElement
   * @param string productId
   */
  addElementToListArea: function(id, globalId){
    this.addPageToListArea(id, globalId);
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
	      
	      var languageId = null;
	      if($('languageId')) {
	        languageId = $F('languageId');
	      }
	      
	      new Ajax.Updater('olsubnav'+folderId, '/zoolu/cms/overlay/childnavigation', {
	        parameters: { 
	         folderId: folderId, 
	         viewtype: viewtype,
	         languageId: languageId
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
      this.lastFolderId = folderId;
      
      $(this.updateContainer).innerHTML = '';
      myCore.addBusyClass(this.updateContainer);
      
      /**
       * overrule given view type
       */
      if(this.areaViewType[this.areaId]){
        viewtype = this.areaViewType[this.areaId];
      }      
      
      if(viewtype == 1){
        strAjaxAction = '/zoolu/cms/overlay/thumbview';
      } else {
        strAjaxAction = '/zoolu/cms/overlay/listview';
      }
      
      var languageId = null;
      if($('languageId')) {
        languageId = $F('languageId');
      }
      
      var fieldname = this.areaId.substring(this.areaId.indexOf('_')+1);
      new Ajax.Updater(this.updateContainer, strAjaxAction, {
       parameters: { 
         folderId: folderId, 
         fileIds: $(fieldname).value,
         languageId: languageId
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
	      if($('olnavitem'+itemId).down('.icon').hasClassName('img_folder_on_open')){
	        $('olnavitem'+itemId).down('.icon').removeClassName('img_folder_on_open');	        
	      }else{
          $('olnavitem'+itemId).down('.icon').addClassName('img_folder_on_open');
	      }
	    }
    }         
  },
  
  /**
   * selectTab
   * @param tabId
   */
  selectTab: function(tabId){
    if($('divTab_'+this.activeTabId)){
      $('divTab_'+this.activeTabId).hide();
      $('tabNavItem_'+this.activeTabId).removeClassName('selected');
    }
    $('divTab_'+tabId).show();
    $('tabNavItem_'+tabId).addClassName('selected');
    this.setActiveTab(tabId);
  },
  
  /**
   * selectTab
   */
  setActiveTab: function(tabId){
    this.activeTabId = tabId;    
  },
  
  /**
   * setViewType
   */
  setViewType: function(viewType){
    if(typeof(this.areaId) != 'undefined'){
      this.areaViewType[this.areaId] = viewType;
    }
    
    if(this.lastFolderId !== null) this.getMediaFolderContent(this.lastFolderId, viewType);
    
    this.updateViewTypeIcons(viewType)
  },
  
  /**
   * updateViewTypeIcons
   */
  updateViewTypeIcons: function(viewType){    
    if(typeof(viewType) == 'undefined'){
      if(this.areaViewType[this.areaId]){
        viewType = this.areaViewType[this.areaId];
      }else{
        viewType = 1;
      }
    }
    
    if(viewType == 1){
      $('divThumbView').removeClassName('iconthumbview_on');
      $('divThumbView').addClassName('iconthumbview');      
      $('divListView').removeClassName('iconlistview');
      $('divListView').addClassName('iconlistview_on');      
    }else{
      $('divThumbView').removeClassName('iconthumbview');
      $('divThumbView').addClassName('iconthumbview_on');     
      $('divListView').removeClassName('iconlistview_on');
      $('divListView').addClassName('iconlistview');      
    }
  },
  
  /**
   * saveMaintenance
   */
  saveMaintenance: function(){ 
    if($('maintenanceForm')){
      
      /**
       * serialize generic form
       */
      var serializedForm = $('maintenanceForm').serialize();
      
      myCore.addBusyClass('overlayMaintenanceContent');
      new Ajax.Request('/zoolu/cms/overlay/maintenance', {
        parameters: serializedForm+'&rootLevelId='+myNavigation.rootLevelId+'&operation=save',      
        evalScripts: true,     
        onComplete: function(transport) {
          if(transport.responseText.isJSON()){
            var response = transport.responseText.evalJSON();
            
            if(response.active == true){
              if($('spanMaintenanceStatus_'+myNavigation.rootLevelId)) $('spanMaintenanceStatus_'+myNavigation.rootLevelId).appear();
            }else{
              if($('spanMaintenanceStatus_'+myNavigation.rootLevelId)) $('spanMaintenanceStatus_'+myNavigation.rootLevelId).fade();
            }
          }
          this.closeMaintenanceOverlay();
          myCore.removeBusyClass('overlayMaintenanceContent');
        }.bind(this)
      });  
    }
  },
  
  /**
   * close
   */
  close: function(id){
    if(this.overlayCounter > 1 && id != undefined) {
      $(id).hide();
      this.overlayCounter--;
    } else {
      if($('overlayGenContentWrapper')) $('overlayGenContentWrapper').hide();
      if($('overlayUpload')) $('overlayUpload').hide();
      if($('overlaySingleEdit')) $('overlaySingleEdit').hide();
      if($('overlayBlack75')) $('overlayBlack75').hide();
      if($('overlayGenContent')) $('overlayGenContent').innerHTML = '';
      if($('overlaySingleEditContent')) $('overlaySingleEditContent').innerHTML = '';
      //this.lastFolderId = null;
      this.overlayCounter = 0;
    }
    this.closeMaintenanceOverlay();
  },
  
  /**
   * closeMaintenanceOverlay
   */
  closeMaintenanceOverlay: function(){
    if($('overlayMaintenanceWrapper')) $('overlayMaintenanceWrapper').hide();
    if($('overlayBlack75')) $('overlayBlack75').hide();    
  }
});