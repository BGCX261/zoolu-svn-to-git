/**
 * default.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-26: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Default = Class.create({

  initialize: function() {
  
},
  
  /**
   * changeTestMode
   */
  changeTestMode: function(status){
    new Ajax.Request('/zoolu-website/testmode/change', {
      parameters: { TestMode: status },
      evalScripts: true,
      onComplete: function(transport) {         
        window.location.href = window.location.href;
      }.bind(this)
    });
  },
  
  /**
   * addBusyClass
   */
  addBusyClass: function(busyElement, blnDisplay) {
    if($(busyElement)){
      $(busyElement).addClassName('busy');
      if(blnDisplay) $(busyElement).show();
    }
  },

  /**
   * removeBusyClass
   */
  removeBusyClass: function(busyElement, blnDisplay) {
    if($(busyElement)){
      $(busyElement).removeClassName('busy');
      if(blnDisplay) $(busyElement).hide();
    }
  },
  
  /**
   * submitForm
   */
  submitForm: function(formId){
    if($(formId)){      
      /**
       * validation
       */
      this.retValue = true;
      
      $$('.mandatory').each(function(element){
        this.validateInput(element.id);
        if(element.id == 'email'){
          this.validateInputEmail(element.id);
        }
      }.bind(this));

      $$('.mandatory-check').each(function(element){
        this.validateCheckbox(element.id);
      }.bind(this));

      if(this.retValue == true) {
        /*var serializedForm = $(formId).serialize();

        new Ajax.Request($(formId).readAttribute('action'), {
        parameters: serializedForm,
        evalScripts: true,
        onComplete: function(transport) {

        }.bind(this)
        });*/

        $(formId).submit();
      }else{

      }
    }
  },
  
  /**
   * updateLiveSearch
   */
  updateLiveSearch: function(selectedElement){
    if($('searchField') && selectedElement.childNodes[0] && selectedElement.childElements().length > 0){
      var link = selectedElement.childNodes[0].readAttribute('href');
        if(link != ''){
          return self.location.href = link;  
      }
    }
  },
  
  /**
   * search
   */
  search: function(){
    if($('searchField')){
      if(($('searchField').value != '')){
        $('searchForm').submit();
      }
    } 
  },
  
  
  /**
   * validateInput
   */
  validateInput: function(element, baseValue) {
    if(($(element) && $F(element).blank()) || $F(element) == baseValue){
      if($('lbl_'+element)) $('lbl_'+element).addClassName('missing');
      this.retValue = false;
    }else{
      if($('lbl_'+element)) $('lbl_'+element).removeClassName('missing');
    }
  },

  /**
   * validateCheckbox
   */
  validateCheckbox: function(element, baseValue) {
    if($(element) && !($(element).checked)){
      if($('lbl_'+element)) $('lbl_'+element).addClassName('missing');
      this.retValue = false;
    }else{
      if($('lbl_'+element)) $('lbl_'+element).removeClassName('missing');
    }
  },

  /**
   * validateInputEmail
   */
  validateInputEmail: function(element){
    var filter = /^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/;
    if($(element)){
      if(!filter.test($F(element))){
        if($('lbl_'+element)) $('lbl_'+element).addClassName('missing');
        this.retValue = false;
      }else{
        if($('lbl_'+element)) $('lbl_'+element).removeClassName('missing');
      }
    }
  },
  
  /**
   * expireCache
   */
  expireCache: function(elId){
    this.addBusyClass(elId, false);
    new Ajax.Request('/zoolu-website/content/expire-cache', {
      method: 'get',
      evalScripts: true,
      onComplete: function(transport) {
        this.removeBusyClass(elId, false);
        window.location.href = window.location.href;
      }.bind(this)
    });
  }
  
  
});