/**
 * form.members.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2011-01-05: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Massiveart.Form.Contacts = Class.create(Massiveart.Form, {
  
  initialize: function($super) {
    // initialize superclass
    $super();
  },
  
  /**
   * save
   */
  save: function(){
   if($(this.formId)){      
      
     /**
      * write/save texteditor content to generic form
      */
      if($$('.texteditor')){
        tinyMCE.triggerSave();
        myCore.resetTinyMCE(true);
      }
     
      /**
       * serialize generic form
       */
      var serializedForm = $(this.formId).serialize();
      
      // loader
      this.getFormSaveLoader();
      
      new Ajax.Updater(myNavigation.genTmpContainer, $(this.formId).readAttribute('action'), {
        parameters: serializedForm,
        evalScripts: true,
        onComplete: function(transport) {
          //problem: ajax.updater evalScripts = true was too late
          transport.responseText.evalScripts();
          
          if(this.blnShowFormAlert){
            //saved
            this.getFormSaveSucces();
            
            if(myNavigation.rootLevelType == 'member' || myNavigation.rootLevelType == 'company'){
              
              $(myNavigation.genListContainer).update($(myNavigation.genTmpContainer).innerHTML);
              
              $(myNavigation.genFormContainer).hide();
              $(myNavigation.genFormFunctions).hide();
              
              $(myNavigation.genListContainer).show();
              $(myNavigation.genListFunctions).show();              
              
              myList.getListPage();
            }else{              
              if(myNavigation.rootLevelId != '' && myNavigation.rootLevelId > 0){
                myNavigation.updateNavigationLevel();
                $('buttondelete').show();
              }
              // load medias
              this.loadFileFieldsContent('media');
              // load documents
              this.loadFileFieldsContent('document');
            }
          }else{
            this.getFormSaveError();
            $(myNavigation.genFormContainer).update($(myNavigation.genTmpContainer).innerHTML);
          }
        }.bind(this)
      });
    }
  },
  
  /**
   * deleteElement
   */
  deleteElement: function(){
    if($(this.formId)){
      
      var tmpKey = 'Delete_' + $('formType').getValue();
      if(myCore.translate[tmpKey]){
        var key = tmpKey;
      }else{
        var key = 'Delete_';
      }
      
      myCore.deleteAlertSingleMessage = myCore.translate[key];
      myCore.showDeleteAlertMessage(1);

      $('buttonOk').observe('click', function(event){
        myCore.hideDeleteAlertMessage();
        var strAjaxActionBase = $(this.formId).readAttribute('action').replace('edit', 'delete');
        var elementId = $('id').getValue();
        
        // loader
        this.getFormSaveLoader();
        myCore.resetTinyMCE(true);
        
        if($('formType')){
          navItemId = $F('formType')+elementId;
        }

        new Ajax.Updater(myNavigation.genListContainer, strAjaxActionBase, {
          parameters: { id: elementId },
          evalScripts: true,
          onComplete: function() {
          //deleted
            this.getFormDeleteSucces();
            
            if(myNavigation.rootLevelType == 'member' || myNavigation.rootLevelType == 'company'){
             
              $(myNavigation.genFormContainer).hide();
              $(myNavigation.genFormFunctions).hide();
              
              $(myNavigation.genListContainer).show();
              $(myNavigation.genListFunctions).show();              
              
              myList.getListPage();
            }else{
              if($(navItemId)){
                new Effect.Highlight(navItemId, {startcolor: '#ffd300', endcolor: '#ffffff'});
                $(navItemId).fade({duration: 0.5});
                setTimeout('$("'+navItemId+'").remove()', 500);
              }
              
              
            }
          }.bind(this)
        });
      }.bind(this));

      $('buttonCancel').observe('click', function(event){
        myCore.hideDeleteAlertMessage();
      }.bind(this));
    }
  }
});