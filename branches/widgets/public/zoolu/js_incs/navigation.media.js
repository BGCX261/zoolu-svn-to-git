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
   * @param integer portalId
   */
  selectMediaType: function(portalId){
    this.currLevel = 1;
    
    $(this.genFormContainer).hide();
    $(this.genFormSaveContainer).hide(); 
    $(this.mediaContainer).hide();   
    
    this.makeSelected('portal'+portalId);
    if($(this.preSelectedPortal) && ('portal'+portalId) != this.preSelectedPortal){ 
      this.makeDeselected(this.preSelectedPortal);
    }  
            
    this.preSelectedPortal = 'portal'+portalId;
    this.rootLevelId = portalId;
    
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
        this.initWidgetHover();
        this.initPageHover();
        this.initAddMenuHover();
      }.bind(this)
    });
  }

});