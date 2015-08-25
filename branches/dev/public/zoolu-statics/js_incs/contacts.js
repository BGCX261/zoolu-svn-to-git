/**
 * contacts.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2010-01-05: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Massiveart.Contacts = Class.create({

  initialize: function() {
    this.isStartContact = false;
  },
  
  /**
   * changeType
   
  changeType: function(typeId){
	  
    params = $H({elementTypeId: typeId,
                 templateId: $F('templateId'),
                 formId: $F('formId'),
                 formVersion: $F('formVersion'),
                 formTypeId: $F('formTypeId'),
                 id: $F('id'),
                 linkId: ($('linkId')) ? $F('linkId') : -1,
                 languageId: $F('languageId'),
                 currLevel: $F('currLevel'),
                 rootLevelId: $F('rootLevelId'),
                 rootLevelGroupId: $F('rootLevelGroupId'),
                 rootLevelGroupKey: ($('rootLevelGroupKey'+$F('rootLevelGroupId'))) ? $F('rootLevelGroupKey'+$F('rootLevelGroupId')) : '',
                 parentFolderId: $F('parentFolderId'),
                 parentTypeId: $F('parentTypeId'),
                 elementType: $F('elementType'),
                 isStartContact: this.isStartContact});
    
    $(myNavigation.genFormContainer).innerHTML = '';
    
    // loader
    myCore.addBusyClass(myNavigation.genFormContainer);
    myCore.addBusyClass('tdChangeType');    
    myForm.getFormSaveLoader();
    
    myCore.resetTinyMCE(true);
    
    new Ajax.Updater(myForm.updateContainer, '/zoolu/contacts/element/changeType', {
      parameters: params,
      evalScripts: true,
      onComplete: function() { 
        // load medias
        myForm.loadFileFieldsContent('media');
        // load documents
        myForm.loadFileFieldsContent('document');
        // load videos
        myForm.loadFileFieldsContent('video');
        
        $('divMetaInfos').innerHTML = '';
        myCore.removeBusyClass(myNavigation.genFormContainer);
        myCore.removeBusyClass('tdChangeType');
        myForm.cancleFormSaveLoader();
      }.bind(this)
    });
  },*/

  /**
   * resetSearch
   
  resetSearch: function(){
    if($('elementSearchResult') && $('elementSearchValue')){
      $('elementSearchResult').innerHTML = '';
      $('elementSearchValue').value = '';
    }
  },*/

  /**
   * search
   
  search: function(){
    if($('elementSearchResult') && $('elementSearchValue') && !$F('elementSearchValue').blank()){
      $('elementSearchResult').innerHTML = '';
      myCore.addBusyClass('elementSearchResult')
      new Ajax.Updater('elementSearchResult', '/zoolu/contacts/element/overlaysearch', {
        parameters: {searchValue: $F('elementSearchValue'), rootLevelId: myNavigation.rootLevelId },
        evalScripts: true,
        onComplete: function() {
          myCore.removeBusyClass('elementSearchResult');
        }.bind(this)
      });
    }
  },*/

  /**
   * addElementLink
   
  addElementLink: function(elementId){
    if($('elementSearchCurrLevel')){
      currLevel = $F('elementSearchCurrLevel');

      myCore.resetTinyMCE(true);
      
      new Ajax.Request('/zoolu/contacts/element/addelementlink', {
        parameters: {
            templateId: elementTemplateDefaultId,
            rootLevelId: myNavigation.rootLevelId,
            rootLevelGroupId: myNavigation.rootLevelGroupId,
            parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
            currLevel: currLevel,
            elementTypeId: elementTypeDefaultId,
            elementType: myNavigation.constGlobal,
            isStartContact: 0,
            linkId: elementId
        },
        evalScripts: true,
        onComplete: function() {
          myNavigation.updateNavigationLevel(currLevel, $('navlevel'+currLevel).readAttribute('parentid'));
        }.bind(this)
      });
    }
  }*/

});