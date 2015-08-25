/**
 * navigation.global.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-03-09: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

Massiveart.Navigation.Global = Class.create(Massiveart.Navigation, {

  initialize: function($super) {
    // initialize superclass
    $super();

    this.constBasePath = '/zoolu/global';

    this.rootLevelType = 'folder';
    this.genListContainer = 'genTableListContainer';
  },
  
  /**
   * addElement
   */
  addElement: function(currLevel){
    $('buttondelete').hide();
    this.showFormContainer();
        
    $(this.genFormContainer).innerHTML = '';
    $('divWidgetMetaInfos').innerHTML = '';
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();
        
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
    myCore.resetTinyMCE(true);
    
    new Ajax.Updater('genFormContainer', '/zoolu/global/element/getaddform', {
      parameters: {
        templateId: elementTemplateDefaultId,
        rootLevelId: this.rootLevelId,
        rootLevelGroupId: this.rootLevelGroupId,
        rootLevelGroupKey: ($('rootLevelGroupKey'+this.rootLevelGroupId)) ? $F('rootLevelGroupKey'+this.rootLevelGroupId) : '',
        parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
        currLevel: currLevel,
        elementTypeId: elementTypeDefaultId,
        elementType: this.constGlobal,
        isStartElement: 0
      },      
      evalScripts: true,     
      onComplete: function() {
        myForm.writeMetaInfos();
        
        $('levelmenu'+currLevel).hide();
        $('addmenu'+currLevel).fade({duration: 0.5});
        myCore.removeBusyClass('divWidgetMetaInfos');
        myCore.removeBusyClass(this.genFormContainer);              
      }.bind(this)
    });    
  },
  
  /**
   * getElementLinkChooser
   */
  getElementLinkChooser: function(currLevel){
    $(myForm.updateOverlayContainer).innerHTML = '';
    
    myCore.putCenter(myForm.updateOverlayContainer+'Wrapper');
    $(myForm.updateOverlayContainer+'Wrapper').show();
  
    this.folderId = myNavigation.folderId;
  
    new Ajax.Updater(myForm.updateOverlayContainer, '/zoolu/global/element/getoverlaysearch', {
      parameters: { currLevel: currLevel },
      evalScripts: true,
      onComplete: function(){
        myCore.putOverlayCenter(myForm.updateOverlayContainer+'Wrapper');
      } 
    });
  }

});