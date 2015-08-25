/**
 * form.properties.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-01-19: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Massiveart.Form.Properties = Class.create(Massiveart.Form, {
  
  initialize: function() {
    this.formId = 'genForm';  
    this.updateContainer = 'genFormContainer';
    this.updateOverlayContainer = 'overlayGenContent';
    
    this.portalId = 0;
    this.preSelectedPortal = '';
    this.selectedPortal = '';
    
    this.preSelectedItem = '';
    this.selectedItem = '';
    this.currLevel = 0;
    
    this.levelArray = [];   
  },
  
  /**
   * save
   */
  save: function(){
      
    if($(this.formId)){
       
      /**
       * serialize generic form
       */
      var serializedForm = $(this.formId).serialize();
      
      // loader
      this.getFormSaveLoader();      
      if($(this.formId).readAttribute('action') != ''){
        new Ajax.Updater(this.updateContainer , $(this.formId).readAttribute('action'), {
	        parameters: serializedForm,
	        evalScripts: true,
	        onComplete: function() {          
	          if($('rootLevelId').getValue() != '' && $('rootLevelId').getValue() > 0){
	            myNavigation.updateNavigationLevel();
	          }                    
	          //saved
	          this.getFormSaveSucces();
	          $('buttondelete').show();  
            
            // load medias
            this.loadFileFieldsContent('media');
            // load documents
            this.loadFileFieldsContent('document');
	        }.bind(this)
	      });
      }      
    }
  },
  
  /**
   * deleteElement
   */
  deleteElement: function(){
    
    if($(this.formId)){
      
      //var intPosLastSlash = $(this.formId).readAttribute('action').lastIndexOf('/');
      //var strAjaxActionBase = $(this.formId).readAttribute('action').substring(0, intPosLastSlash + 1);
      var strAjaxActionBase = $(this.formId).readAttribute('action').replace('edit', 'delete');
      var elementId = $('id').getValue();
      
      // loader
      this.getFormSaveLoader();
      
      if($('formType')){
        navItemId = $F('formType')+elementId;
      }
      
      new Ajax.Updater(this.updateContainer, strAjaxActionBase, {
        parameters: { id: elementId },
        evalScripts: true,
        onComplete: function() {
          //deleted
          this.getFormDeleteSucces();
          
          if($(navItemId)){
            new Effect.Highlight(navItemId, {startcolor: '#ffd300', endcolor: '#ffffff'});
            $(navItemId).fade({duration: 0.5});
            setTimeout('$("'+navItemId+'").remove()', 500);
          }
          
          $(myNavigation.genFormContainer).hide();
          $(myNavigation.genFormSaveContainer).hide(); 
           
        }.bind(this)
      });
    }
  },
  
  /**
   * changeLanguage
   */
  changeLanguage: function(newLanguageId){
    
    myCore.addBusyClass(this.updateContainer);
    
    var intPosLastSlash = $(this.formId).readAttribute('action').lastIndexOf('/');
    var strAjaxActionBase = $(this.formId).readAttribute('action').substring(0, intPosLastSlash + 1);
    
    new Ajax.Updater(this.updateContainer, strAjaxActionBase + 'changeLanguage', {
      parameters: {
        templateId: $F('templateId'),
        formId: $F('formId'),
        formVersion: $F('formVersion'),
        formTypeId: $F('formTypeId'),
        id: $F('id'),
        languageId: newLanguageId,
        currLevel: $F('currLevel'),
        rootLevelId: $F('rootLevelId'),
        parentId: $F('parentId'),
        categoryTypeId: $F('categoryTypeId')                   
      },
      evalScripts: true,
      onComplete: function() {    
        myCore.removeBusyClass(this.updateContainer);
        
        // load medias
        this.loadFileFieldsContent('media');
        // load documents
        this.loadFileFieldsContent('document');
        // load contacts
        this.loadContactFieldsContent();
      }.bind(this)
    });    
    
  }
    
});