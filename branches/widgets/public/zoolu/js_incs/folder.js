/**
 * folder.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-10-14: Thomas Schedler
 *
 * @author Thomas Schedler <tsh@massiveart.com>
 * @version 1.0
 */

Massiveart.Folder = Class.create({

  initialize: function() {
    this.folderId = 0; 
  },
  
  /**
   * getCurrentFolderParentChooser
   */
  getCurrentFolderParentChooser: function(){
    $(myForm.updateOverlayContainer).innerHTML = '';
    
    myCore.putCenter('overlayGenContentWrapper');
    $('overlayGenContentWrapper').show();    
  
    this.folderId = myNavigation.folderId;
  
    new Ajax.Updater(myForm.updateOverlayContainer, '/zoolu/core/folder/foldertree', { 
      parameters: { portalId: myNavigation.rootLevelId, folderId: this.folderId },
      evalScripts: true,
      onComplete: function(){
        myCore.putOverlayCenter('overlayGenContentWrapper');
        //$('overlayBlack75').show();
      } 
    });
  },
  
  selectParentFolder: function(parentFolderId){
    myCore.addBusyClass('overlayGenContent');
  
    new Ajax.Request('/zoolu/core/folder/changeparentfolder', {
      parameters: { 
       folderId: this.folderId,
       parentFolderId: parentFolderId
      },      
      evalScripts: true,     
      onComplete: function() {  
        $('overlayGenContentWrapper').hide(); 
        $('overlayBlack75').hide();                
        
        new Effect.Highlight('folder'+this.folderId, {startcolor: '#ffd300', endcolor: '#ffffff'});
        $('folder'+this.folderId).fade({duration: 0.5});
        $('folder'+this.folderId).remove();
        
        myNavigation.navigation[myNavigation.currLevel - 1] = null;
        
        if($('folder'+parentFolderId)){
          myNavigation.itemId = 'folder'+parentFolderId;            
          myNavigation.selectItem();
        }else{
          if($('navlevel'+myNavigation.currLevel)){
            $('navlevel'+myNavigation.currLevel).innerHTML = '';
          }
          myNavigation.hideCurrentFolder();
        }
        
        myCore.removeBusyClass('overlayGenContent');
      }.bind(this)
    });
  },
  
  selectParentRootFolder: function(rootFolderId){
    myCore.addBusyClass('overlayGenContent');
      
    new Ajax.Request('/zoolu/core/folder/changeparentrootfolder', {
      parameters: { 
       folderId: this.folderId,
       rootFolderId: rootFolderId
      },      
      evalScripts: true,     
      onComplete: function() {  
        $('overlayGenContentWrapper').hide(); 
        $('overlayBlack75').hide();                
        
        new Effect.Highlight('folder'+this.folderId, {startcolor: '#ffd300', endcolor: '#ffffff'});
        $('folder'+this.folderId).fade({duration: 0.5});
        $('folder'+this.folderId).remove();
        
        myNavigation.navigation[myNavigation.currLevel - 1] = null;
        
        myNavigation.selectPortal(rootFolderId);
        
        myCore.removeBusyClass('overlayGenContent');
      }.bind(this)
    });
  },
  
  /**
   * getFolderContentList
   */
  getFolderContentList: function(){
    $(myForm.updateContainer).innerHTML = '';
    $(myForm.updateContainer).show();
    myCore.addBusyClass(myForm.updateContainer);
      
    this.folderId = myNavigation.folderId;

    new Ajax.Updater(myForm.updateContainer, '/zoolu/core/folder/folderlist', { 
      parameters: { 
        portalId: myNavigation.rootLevelId, 
        folderId: this.folderId 
      },
      evalScripts: true,
      onComplete: function(){
        myCore.removeBusyClass(myForm.updateContainer);
      } 
    });
  }
  
});