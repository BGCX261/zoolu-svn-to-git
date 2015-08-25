/**
 * navigation.media.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-02-06: Cornelius Hansjakob
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Massiveart.Navigation.Media = Class.create(Massiveart.Navigation, {

  initialize: function($super) {
    // initialize superclass
    $super();
    
    this.mediaContainer = 'divMediaContainer';
    this.constRequestRootNav = '/zoolu/media/navigation/rootnavigation';
    this.constRequestChildNav = '/zoolu/media/navigation/childnavigation';
    this.constBasePath = '/zoolu/media';
    this.rootLevelType = 'view';
    this.genListContainer = 'divListViewContainer';
  },
  
  /**
   * initModuleMEDIA
   */
  initModuleMEDIA: function(){
    this.loadDashboard();
  },
  
  /**
   * loadDashboard
   */
  loadDashboard: function(){
    $(this.genFormContainer).show();
    myCore.removeBusyClass(this.genFormContainer);
    
    myCore.resetTinyMCE(true);
    
    new Ajax.Updater(this.genFormContainer, '/zoolu/media/view/dashboard', {
      parameters: { },      
      evalScripts: true,     
      onComplete: function() {
        myCore.removeBusyClass(this.genFormContainer);
        myCore.initListHover(false);
      }.bind(this)
    });
  },
  
  /**
   * selectMediaType
   * @param integer rootLevelId
   */
  selectMediaType: function(rootLevelId, viewType){
    this.currLevel = 1;
    
    $(this.genFormContainer).hide();
    $(this.genFormContainer).innerHTML = '';
    $(this.genFormSaveContainer).hide(); 
    $(this.mediaContainer).hide(); 
    
    if(typeof(viewType) != 'undefined' && viewType != ''){
      myMedia.currViewType = viewType;
    }
    
    this.makeSelected('portal'+rootLevelId);
    if($(this.preSelectedPortal) && ('portal'+rootLevelId) != this.preSelectedPortal){ 
      this.makeDeselected(this.preSelectedPortal);
    }
            
    this.preSelectedPortal = 'portal'+rootLevelId;
    this.rootLevelId = rootLevelId;
    
    $('divNaviCenterInner').innerHTML = '';
    this.levelArray = [];
    
    var levelContainer = '<div id="navlevel'+this.currLevel+'" rootlevelid="'+this.rootLevelId+'" parentid="" class="navlevel busy" style="left: '+(201*this.currLevel-201)+'px"></div>'; 
    new Insertion.Bottom('divNaviCenterInner', levelContainer);
    
    if(Prototype.Browser.IE){
      newNavHeight = $('divNaviCenter').getHeight();
      $$('.navlevel').each(function(elDiv){
        $(elDiv).setStyle({height: (newNavHeight-42) + 'px'});
      });
    }
    else if(Prototype.Browser.WebKit){
      newNavHeight = $('divNaviCenter').getHeight();
      $$('.navlevel').each(function(elDiv){
        $(elDiv).setStyle({height: (newNavHeight-40) + 'px'});
      });
    }          
        
    new Ajax.Updater('navlevel'+this.currLevel, this.constRequestRootNav, {
      parameters: { 
        rootLevelId: this.rootLevelId,
        currLevel: this.currLevel},      
      evalScripts: true,     
      onComplete: function() {
        myCore.removeBusyClass('navlevel'+this.currLevel);
        this.levelArray.push(this.currLevel);
        this.initFolderHover();
        this.initPageHover();
        this.initAddMenuHover();
      }.bind(this)
    });
  }

});