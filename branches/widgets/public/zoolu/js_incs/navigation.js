/**
 * navigation.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2008-10-14: Cornelius Hansjakob
 * 1.1, 2009-08-06: Florian Mathis, Added Widget Support
 *
 * @author Cornelius Hansjakob <cha@massiveart.com>
 * @version 1.0
 */

Massiveart.Navigation = Class.create({

  initialize: function() {
    this.module;
    
    this.genFormContainer = 'genFormContainer';
    this.genFormSaveContainer = 'genFormSaveContainer';
    
    this.constFolder = 'folder';
    this.constPage = 'page';
    this.constStartPage = 'startpage';
    this.constWidget = 'widget';
    this.constSubWidget = 'subwidget';
    
    this.constRequestRootNav = '/zoolu/cms/navigation/rootnavigation';
    this.constRequestChildNav = '/zoolu/cms/navigation/childnavigation';
    this.constRequestWidgetNav = '/../widget/%WIDGET%/navigation/widgetnavigation';
    this.constRequestWidgetProperties = '/../widget/%WIDGET%/form/getwidgetpropertiesform';
    
    this.rootLevelId = 0;
    this.preSelectedPortal;
    this.rootLevelTypeId = 0;
    
    this.preSelectedItem;
    this.preSelectedItemId = 0;
    this.currLevel = 0;
    this.parentFolderId = 0;
    
    this.levelArray = new Array();
    this.navigation = new Array();
    
    this.topNaviTitle = '';
    
    this.itemId = '';
    
    this.folderId;
    this.widgetId;
    this.type;
    
    this.arrNavigationTreeIds = new Array();
  },
  
  /**
   * initPortalHover
   */
  initPortalHover: function(){
    $$('#divNaviLeftMain div.portal').each(function(elDiv){    
      
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event);
        el.addClassName('hover');
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event);
        el.removeClassName('hover');
      }.bind(this));
      
    }.bind(this));
  },
  
  /**
   * initFolderHover
   */
  initFolderHover: function(){
    $$('div.'+this.constFolder).each(function(elDiv){    
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event);
        if(el.hasClassName(this.constFolder)){
          el.addClassName('hover');
        }else{         
          el.up('.'+this.constFolder).addClassName('hover');          
        }
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event);        
        if(el.hasClassName(this.constFolder)){
          el.removeClassName('hover');
        }else{         
          el.up('.'+this.constFolder).removeClassName('hover');        
        }        
      }.bind(this));
      
    }.bind(this));
  },
  
  /**
   * initWidgetHover
   */
  initWidgetHover: function(){
    $$('div.'+this.constWidget).each(function(elDiv){    
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event);
        if(el.hasClassName(this.constWidget)){
          el.addClassName('hover');
        }else{         
          el.up('.'+this.constWidget).addClassName('hover');          
        }
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event);        
        if(el.hasClassName(this.constWidget)){
          el.removeClassName('hover');
        }else{         
          el.up('.'+this.constWidget).removeClassName('hover');        
        }        
      }.bind(this));
      
    }.bind(this));
  },
  
  /**
   * initPageHover
   */
  initPageHover: function(){
    // page hover
    $$('div.'+this.constPage).each(function(elDiv){    
      
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event);
        if(el.hasClassName(this.constPage)){
          el.addClassName('hover');
        }else{         
          el.up('.'+this.constPage).addClassName('hover');          
        }
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event);        
        if(el.hasClassName(this.constPage)){
          el.removeClassName('hover');
        }else{         
          el.up('.'+this.constPage).removeClassName('hover');        
        }        
      }.bind(this));
      
    }.bind(this));
    
    // startpage hover
    $$('div.'+this.constStartPage).each(function(elDiv){    
      
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event);
        if(el.hasClassName(this.constStartPage)){
          el.addClassName('hover');
        }else{         
          el.up('.'+this.constStartPage).addClassName('hover');          
        }
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event);        
        if(el.hasClassName(this.constStartPage)){
          el.removeClassName('hover');
        }else{         
          el.up('.'+this.constStartPage).removeClassName('hover');        
        }        
      }.bind(this));
    }.bind(this));
  },
  
  /**
   * initAddMenuHover
   */
  initAddMenuHover: function(){
    $$('.levelmenu').each(function(elDiv){    
      elDiv.observe('mouseover', function(event){        
        el = Event.element(event)
        if(el.hasClassName('levelmenu')){
          el.addClassName('addmenuhover');
        }else{         
          el.up('.levelmenu').addClassName('addmenuhover');        
        }
      }.bind(this));
      
      elDiv.observe('mouseout', function(event){        
        el = Event.element(event)        
        if(el.hasClassName('levelmenu')){
          el.removeClassName('addmenuhover');
        }else{         
          el.up('.levelmenu').removeClassName('addmenuhover');        
        }
      }.bind(this));
    }.bind(this));
  },

  /**
   * selectPortal
   * @param integer portalId
   */
  selectPortal: function(portalId){            
    this.currLevel = 1;
    
    this.hideCurrentFolder();
    
    $(this.genFormContainer).hide();
    $(this.genFormSaveContainer).hide();    
    
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
        this.initPageHover();
        this.initAddMenuHover();
        this.initWidgetHover();
        //this.createSortableNavLevel(this.currLevel);
      }.bind(this)
    });
  },
  
  /**
   * selectNavigationItem
   * @param integer parentLevel, string elType, integer itemId
   */
  selectNavigationItem: function(parentLevel, elType, itemId){
    $(this.genFormContainer).hide();
    $(this.genFormSaveContainer).hide();
    
    this.type = 'folder';
    
    var level = parentLevel + 1;    
    var element = elType+itemId;
        
    this.currLevel = level;
    this.currItemId = itemId;
  
    if(this.navigation[parentLevel]){
      this.makeDeselected(this.navigation[parentLevel]);
    }
    
    this.navigation[parentLevel] = element;
    
    if(this.navigation.length > 0){    
      for(var i = 1; i <= this.navigation.length-1; i++){
        if(this.navigation[i] != element){
          this.makeParentSelected(this.navigation[i]);
        }else{
          this.makeSelected(this.navigation[parentLevel]);
        }   
      } 
    }
        
    this.setParentFolderId(itemId);    
    
    if(this.levelArray.indexOf(this.currLevel) == -1){
      this.levelArray.push(this.currLevel);
      
      var levelContainer = '<div id="navlevel'+this.currLevel+'" rootlevelid="'+this.rootLevelId+'" parentid="'+this.getParentFolderId()+'" class="navlevel busy" style="left: '+(201*this.currLevel-201)+'px"></div>'; 
      new Insertion.Bottom('divNaviCenterInner', levelContainer);
      
    }else{
      
      myCore.addBusyClass('navlevel'+this.currLevel);   
      $('navlevel'+this.currLevel).writeAttribute('parentid', this.getParentFolderId());
      
      var levelPos = this.levelArray.indexOf(this.currLevel);
      for(var i = levelPos; i < this.levelArray.length; i++){
        if($('navlevel'+this.levelArray[i])) $('navlevel'+this.levelArray[i]).innerHTML = '';
      }
      
    }
    
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
    
    if(elType == this.constFolder){    
	    new Ajax.Updater('navlevel'+this.currLevel, this.constRequestChildNav, {
	      parameters: { 
	        folderId: this.currItemId,
	        currLevel: this.currLevel
	      },      
	      evalScripts: true,     
	      onComplete: function() {        
	        myCore.removeBusyClass('navlevel'+this.currLevel);
	        this.initFolderHover();
	        this.initPageHover();
	        this.initAddMenuHover();
	        this.initWidgetHover();
          //this.createSortableNavLevel(this.currLevel);
          this.scrollNavigationBar();
          this.updateCurrentFolder();
	      }.bind(this)
	    });
    }
  },
  
  /**
   * loadNavigationTree
   */
  loadNavigationTree: function(itemId, elType){
    alert('In Arbeit!');
    return false;
    
    if(typeof(itemId) != 'undefined' && itemId != ''){
      $('divNaviCenterInner').innerHTML = '';
      myCore.addBusyClass('divNaviCenterInner');
      
      new Ajax.Updater('divNaviCenterInner', '/zoolu/cms/navigation/tree', {
        parameters: { 
          itemId: itemId,
          elementType: elType
        },      
        evalScripts: true,     
        onComplete: function() {        
          myCore.removeBusyClass('divNaviCenterInner');
          this.initFolderHover();
          this.initPageHover();
          this.initWidgetHover();
          this.initAddMenuHover();
          this.scrollNavigationBar();
          this.updateCurrentFolder();
        }.bind(this)
      });
      
    }  
  },
  
  /**
   * scrollNavigationBar
   */
  scrollNavigationBar: function(){
    if($('level'+this.currLevel)){
      var navigationBar = $('divNaviCenterInner');
      var navOffset = Element.cumulativeOffset(navigationBar);
      var navDimension = Element.getDimensions(navigationBar);
      var navPointer = [(navDimension.width + navOffset.left), (navDimension.height + navOffset.top)];
      var navScrollLeft = navigationBar.scrollLeft;
            
      var levelEl = $('level'+this.currLevel);
      var levelOffset = Element.cumulativeOffset(levelEl);
      var levelDimension = Element.getDimensions(levelEl);
      var levelPointer = [(levelDimension.width + levelOffset.left), (levelDimension.height + levelOffset.top)];
      
      
      if(levelPointer[0] > navPointer[0]){
        if((levelPointer[0] - navScrollLeft) > navPointer[0]){
          new Effect.Scroll('divNaviCenterInner', {x: ((levelPointer[0] - navScrollLeft) - navPointer[0]), y: 0, duration: 0.5})
        }  
      }else if(navScrollLeft > 0){
        new Effect.Scroll('divNaviCenterInner', {x: - navScrollLeft, y: 0, duration: 0.5})
      }
    }
    
  },
  
  /**
   * updateCurrentFolder
   */
  updateCurrentFolder: function() {
    this.folderId = this.currItemId;
    this.type = 'folder';
    if($('divFolderRapper')){
      $('divFolderRapper').show();
      $('aFolderTitle').innerHTML = $('divNavigationTitle_folder'+this.folderId).innerHTML;
    } 
  },
  
  /**
   * hideCurrentFolder
   */
  hideCurrentFolder: function() {
    if($('divFolderRapper')){
      $('divFolderRapper').hide();
      $('aFolderTitle').innerHTML = '';
    } 
  },
  
  /**
   * updateCurrentWidget
   */
  updateCurrentWidget: function() {
		this.widgetId = this.currItemId; 
		this.type = 'widget';
		if($('divFolderRapper')){
		  $('divFolderRapper').show();
		  $('aFolderTitle').innerHTML = $('divNavigationTitle_widget'+this.widgetId).innerHTML;
		}
  },
  
  /**
   * getEditFormMainFolder
   */
  getEditFormMainFolder: function(){
	if(this.type == 'widget') {
	  $('divNavigationEditWidget_'+this.widgetId).ondblclick();
	}
	else {	
      if($('divNavigationEdit_'+this.folderId)){
        $('divNavigationEdit_'+this.folderId).ondblclick();
      }
	}
  },
  
  /**
   * getEditFormMainWidget
   */
  getEditFormMainWidget: function(){
	if($('divNavigationEditWidget_'+this.widgetId)){
	  $('divNavigationEditWidget_'+this.widgetId).ondblclick();
	}
  },
  
  /**
   * createSortableNavigation
   */
  createSortableNavLevel: function(level) {
    SortableNavLevel= 'level'+level;
    Sortable.destroy(SortableNavLevel);
    if($(SortableNavLevel)){      
      Sortable.create(SortableNavLevel,{
            tag:'div',
            scroll:SortableNavLevel,
            only: ['folder', 'page'],
            handle:'icon',            
            //constraint: false,
            //ghosting: true,
            containment: SortableNavLevel,
            onUpdate: function(){ 
              Sortable.serialize(SortableNavLevel);
          }
      });
    }
  },
  
  /**
   * selectItem 
   */
  selectItem: function(){    
    if(this.itemId != ''){     
      if($('divNavigationTitle_'+this.itemId)) $('divNavigationTitle_'+this.itemId).onclick();
    }
  },
  
  /**
   * updateNavigationLevel
   * @param integer level, integer parentItemId
   */
  updateNavigationLevel: function(level, parentItemId){
   
    var elementId;
    var currLevel;
    var parentId;
    var elementType = '';
    var instanceId = '';
   
    if(typeof(level) != 'undefined' && level != ''){ 
      currLevel = level;
    }else{
      if($('currLevel')) currLevel = $F('currLevel');
    }
   
    if(typeof(parentItemId) != 'undefined' && parentItemId != ''){
      parentId = parentItemId;
    }else{
      if($('parentFolderId')) parentId = $F('parentFolderId');
    }
   
    if($('elementType') && $F('elementType') != '') elementType = $F('elementType');
    if($('id') && $F('id')) elementId = $F('id');
    if($('widgetInstanceId') && $F('widgetInstanceId') != '') instanceId = $F('widgetInstanceId');

    var strAjaxAction = '';
    var strParams = '';

    if(elementType == this.constSubWidget){
      strAjaxAction = this.constRequestWidgetNav.replace(/%WIDGET%/, 'blog');
      strParams = 'currLevel='+currLevel+'&instanceId='+instanceId;
    }
    else if(parentId != '' && parentId > 0){
      strAjaxAction = this.constRequestChildNav;
      strParams = 'currLevel='+currLevel+'&folderId='+parentId;
    } else {
      strAjaxAction = this.constRequestRootNav;
      strParams = 'currLevel='+currLevel+'&rootLevelId='+this.rootLevelId;
    }
	if(strParams != '' && strAjaxAction != ''){  
	  this.currLevel = currLevel;
	    new Ajax.Updater('navlevel'+currLevel, strAjaxAction, {
	      parameters: strParams,      
	      evalScripts: true,     
	      onComplete: function() {
	        new Effect.Highlight('navlevel'+this.currLevel, {startcolor: '#ffd300', endcolor: '#ffffff'});
	        
	        if(elementType != '' && elementId != '' && $(elementType+elementId)){ 
	          if(this.navigation[currLevel]){
	            this.makeDeselected(this.navigation[currLevel]);
	          }    
	          this.navigation[currLevel] = elementType+elementId;
	   
	          if(this.navigation.length > 0){      
	            for(var i = 1; i <= this.navigation.length-1; i++){
	              if(this.navigation[i] != elementType+elementId){
	                if(currLevel < i){
	                  this.makeDeselected(this.navigation[i]);
	                }else{
	                  this.makeParentSelected(this.navigation[i]);
	                }
	              }else{
	                this.makeSelected(this.navigation[currLevel]);
	              }   
	            } 
	          }        
	          if(this.levelArray.indexOf(currLevel) != -1 && elType == this.constPage){
	            var levelPos = this.levelArray.indexOf(currLevel)+1;
	            for(var i = levelPos; i < this.levelArray.length; i++){
	              if($('navlevel'+this.levelArray[i])) $('navlevel'+this.levelArray[i]).innerHTML = '';
	            }
	          }        
	          this.itemId = elementType+elementId;			//FIXME: Workaround for widgets, should work without, but doesn't
	          if(elementType == this.constFolder || elementType == this.constWidget){
	            this.selectItem();
	          } 
	        }
	              
	        this.initFolderHover();
	        this.initWidgetHover();
	        this.initPageHover();
	        this.initAddMenuHover();    
	      }.bind(this)
	    });      
      }
  },
  
  /**
   * updateSortPosition
   * @param string posElement, string elType, integer level 
   */
  updateSortPosition: function(posElement, elType, level){
    
    var intPosLastUnderscore = posElement.lastIndexOf('_');
    var itemId = posElement.substring(intPosLastUnderscore + 1);
    var parentId = $('navlevel'+level).readAttribute('parentid');
  
    new Ajax.Updater('navlevel'+level, '/zoolu/cms/navigation/updateposition', {
		  parameters: {
		    id: itemId,
		    elementType: elType,
		    sortPosition: $(posElement).getValue(),
		    rootLevelId: this.rootLevelId,
		    parentId: parentId		    
		  },      
		  evalScripts: true,     
		  onComplete: function() {	    
		    if(this.rootLevelId != '' && this.rootLevelId > 0){
          this.updateNavigationLevel(level, parentId);
        }		        
		  }.bind(this)
		});		
    
  },
  
  /**
   * updateBreadcrumb
   */
  updateBreadcrumb: function(){
    var breadcrumb = this.topNaviTitle;
    
    if($('divRootLevelTitle_'+this.rootLevelId)){
      breadcrumb += " &raquo; " + $('divRootLevelTitle_'+this.rootLevelId).innerHTML;
    }
    
    if(this.navigation && this.navigation.length > 0){
      for(var i = 1; i <= this.navigation.length-1; i++){
        if($('divNavigationTitle_'+this.navigation[i])){
          breadcrumb += " &raquo; " + $('divNavigationTitle_'+this.navigation[i]).innerHTML;
        }
      }
    }
    
    $('navtopbreadcrumb').innerHTML = breadcrumb;
  },
  
  /**
   * toggleAddMenuIcon
   * @param integer levelId, string mode
   */
  toggleAddMenuIcon: function(levelId, mode){
    
    if(mode != '' && mode == 'show'){
      $('levelmenu'+levelId).show();
      $('addmenu'+levelId).hide();
      //$('addmenu'+levelId).fade({ duration: 0.5 });
      //setTimeout('$(\'addmenu'+levelId+'\').hide()', 500); 
    }else{      
      $('levelmenu'+levelId).hide();
    }    
  },
  
  /**
   * toggleSortPosBox
   * @param string element
   */
  toggleSortPosBox: function(element){
    
    if($(element).hasClassName('sortactive')){
      $(element).removeClassName('sortactive');
    }else{
      $(element).addClassName('sortactive');
    }  
      
  },
  
  /**
   * showAddMenu
   * @param integer levelId
   */
  showAddMenu: function(levelId){
    currMenuDiv = 'addmenu'+levelId;
    $(currMenuDiv).appear({ duration: 0.5 });    	  
  },
  
  /**
   * addFolder
   * @param integer currLevel
   */
  addFolder: function(currLevel){
    if($('divMediaContainer')) $('divMediaContainer').hide(); 
    if($('buttondelete')) $('buttondelete').hide();   
    this.showFormContainer();
    
    $(this.genFormContainer).innerHTML = '';
    $('divWidgetMetaInfos').innerHTML = '';
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();

    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
        
    new Ajax.Updater('genFormContainer', '/zoolu/core/folder/getaddform', {
      parameters: {
        formId: folderFormDefaultId,
        rootLevelId: this.rootLevelId,
        rootLevelTypeId: this.rootLevelTypeId,
        parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
        currLevel: currLevel,
        elementType: this.constFolder,
        zoolu_module: this.module            
      },      
      evalScripts: true,     
      onComplete: function() {
        myForm.writeMetaInfos();
        
        $('levelmenu'+currLevel).hide();
        $('addmenu'+currLevel).fade({duration: 0.2});
        myCore.removeBusyClass('divWidgetMetaInfos');
        myCore.removeBusyClass(this.genFormContainer);    
      }.bind(this)
    });
    
  },
  
  /**
   * addPage
   * @param integer currLevel
   */
  addPage: function(currLevel){
    $('buttondelete').hide();
    this.showFormContainer();    
        
    $(this.genFormContainer).innerHTML = '';
    $('divWidgetMetaInfos').innerHTML = '';
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();
        
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
    new Ajax.Updater('genFormContainer', '/zoolu/cms/page/getaddform', {
      parameters: {
        templateId: pageTemplateDefaultId,
        rootLevelId: this.rootLevelId,
        parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
        currLevel: currLevel,
        pageTypeId: pageTypeDefaultId,
        elementType: this.constPage,
        isStartPage: 0       
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
   * AddWidget
   * @param integer currLevel
   */
  addWidget: function(currLevel){
    $(myForm.updateOverlayContainer).innerHTML = '';
    
    myCore.putCenter('overlayGenContentWrapper');
    $('overlayGenContentWrapper').show();    
    
    this.folderId = myNavigation.folderId;
    this.type = 'folder';
    
    new Ajax.Updater(myForm.updateOverlayContainer, '/zoolu/cms/widget/widgettree', { 
      parameters: { portalId: myNavigation.rootLevelId, folderId: this.folderId, currLevel: currLevel, elementType: 'widget' },
      evalScripts: true,
      onComplete: function(){
      	$('levelmenu'+currLevel).hide();
        $('addmenu'+currLevel).fade({duration: 0.5});
        myCore.putOverlayCenter('overlayGenContentWrapper');
        //$('overlayBlack75').show();
      } 
    });
  },
  
  /**
   * addWidgetForm
   * @param integer idWidget
   * @param integer parentId
   * @param integer parentType
   */
  addWidgetForm: function(idWidget, parentId, parentType, currLevel, formId){

  	$('buttondelete').hide();
  	this.showFormContainer();
  	
  	$(this.genFormContainer).innerHTML = '';
    $('divWidgetMetaInfos').innerHTML = '';
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();
    
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
  	new Ajax.Updater('genFormContainer', '/zoolu/cms/widget/getaddform', {
      parameters: {
  			parentFolderId: $('navlevel' + currLevel).readAttribute('parentid'),
  			parentType: parentType,
  			idWidget: idWidget,
  			currLevel: currLevel,
  			rootLevelId: this.rootLevelId,
  			formId: formId,
  			instanceId: $('navlevel' + currLevel).readAttribute('widgetInstanceId'),
  			elementType: 'widget'
      },      
      evalScripts: true,     
      onComplete: function() {      	 
        $('overlayGenContentWrapper').hide();
        //$('overlayBlack75').hide();
        myForm.writeMetaInfos();
        myCore.removeBusyClass('divWidgetMetaInfos');
        myCore.removeBusyClass(this.genFormContainer); 
      }.bind(this)
    });
  },
  
  /**
   * addSubWidgetForm
   */
  addSubWidgetForm: function(widgetName, currLevel, widgetInstanceId){
	$('buttondelete').hide();
	this.showFormContainer();
	$(this.genFormContainer).innerHTML = '';
	$('divWidgetMetaInfos').innerHTML = '';
	
	$(this.genFormContainer).show();
	$(this.genFormSaveContainer).show();
	
	myCore.addBusyClass(this.genFormContainer);
	myCore.addBusyClass('divWidgetMetaInfos');
	
	
	var strAjaxAction = '/../widget/%WIDGET%/form/getaddsubwidgetform';
	strAjaxAction = strAjaxAction.replace(/%WIDGET%/, widgetName);

	new Ajax.Updater('genFormContainer', strAjaxAction, {
	  parameters: {
		elementType: 'subwidget',
		currLevel: currLevel,
		widgetInstanceId: widgetInstanceId,
		rootLevelId: this.rootLevelId,
	  },
	  evalScripts: true,
	  onComplete: function() {
		myForm.writeMetaInfos();
	    myCore.removeBusyClass('divWidgetMetaInfos');
	    myCore.removeBusyClass('genFormContainer'); 
	  }.bind(this)
	})
  },
  
  /**
   * addStartPage
   * @param integer currLevel
   */
  addStartPage: function(currLevel){
    $('buttondelete').hide();
    this.showFormContainer();
    
    $('divWidgetMetaInfos').innerHTML = '';
    $(this.genFormContainer).innerHTML = '';
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();
    
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
    new Ajax.Updater('genFormContainer', '/zoolu/cms/page/getaddform', {
      parameters: {
        templateId: pageTemplateDefaultId,
        rootLevelId: this.rootLevelId,
        parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
        currLevel: currLevel,
        pageTypeId: pageTypeDefaultId,
        elementType: this.constStartPage,
        isStartPage: 1
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
   * getWidgetEditForm
   * @param integer widgetInstanceId, integer widgetId, integer formId, integer version
   */
  getWidgetEditForm: function(widgetInstanceId, widgetId, formId, version, instanceId) {
		$(this.genFormContainer).innerHTML = '';
		$('divWidgetMetaInfos').innerHTML = '';
		// hide media container
		if($('divMediaContainer')) $('divMediaContainer').hide();
		
		var element = this.constWidget+widgetInstanceId;
		this.currItemId = widgetInstanceId;
		
		var elType = this.constWidget;
		var currLevel = 0;
		currLevel = parseInt($(element).up().id.substr(5)); 
		
		if(this.navigation[currLevel]){
      this.makeDeselected(this.navigation[currLevel]);
    }    
    this.navigation[currLevel] = element;
    
    if(this.navigation.length > 0){      
      for(var i = 1; i <= this.navigation.length-1; i++){
        if(this.navigation[i] != element){
          if(currLevel < i){
            this.makeDeselected(this.navigation[i]);
          }else{
            this.makeParentSelected(this.navigation[i]);
          }
        }else{
          this.makeSelected(this.navigation[currLevel]);
        }   
      } 
    }
    
    if(this.levelArray.indexOf(currLevel) != -1 && elType == this.constPage){
      var levelPos = this.levelArray.indexOf(currLevel)+1;
      for(var i = levelPos; i < this.levelArray.length; i++){
        if($('navlevel'+this.levelArray[i])) $('navlevel'+this.levelArray[i]).innerHTML = '';
      }
      $('divFolderRapper').hide();
    }
    
    this.showFormContainer();

    if($(element).down('.icon').className.indexOf(this.constStartPage) == -1){
      $('buttondelete').show();
    }else{
      $('buttondelete').hide();
    }
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();    
    
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
    new Ajax.Updater('genFormContainer', '/zoolu/cms/widget/geteditform', {
		  parameters: { 
	    	parentFolderId: $('navlevel' + currLevel).readAttribute('parentid'),
				idWidget: widgetId,
				currLevel: currLevel,
				rootLevelId: this.rootLevelId,
				idWidgetInstance: widgetInstanceId,
				formId: formId,
				instanceId: instanceId,
				elementType: 'widget'
		  },      
		  evalScripts: true,     
		  onComplete: function() {
		    myForm.writeMetaInfos();
		    myCore.removeBusyClass('divWidgetMetaInfos');
		    myCore.removeBusyClass(this.genFormContainer);
		    // load medias
		    myForm.loadFileFieldsContent('media');
		    // load documents
		    myForm.loadFileFieldsContent('document');		
		    // load contacts
		    myForm.loadContactFieldsContent();     
		  }.bind(this)
		});
  },
  
  /**
   * editSubWidgetForm
   * @param integer widgetInstanceId
   */
  editSubWidgetForm: function(subWidgetId, widgetName, widgetInstanceId) {
	  $(this.genFormContainer).innerHTML = '';
	  $('divWidgetMetaInfos').innerHTML = '';
	  // hide media container
	  if($('divMediaContainer')) $('divMediaContainer').hide();
	  
	  var element = this.constSubWidget+subWidgetId;
	  this.currItemId = subWidgetId;
	  
	  var elType = this.constSubWidget;
  	  var currLevel = 0;
	  currLevel = parseInt($(element).up().id.substr(5)); 
	
	  if(this.navigation[currLevel]){
        this.makeDeselected(this.navigation[currLevel]);
      }    
      this.navigation[currLevel] = element;
    
      if(this.navigation.length > 0){      
        for(var i = 1; i <= this.navigation.length-1; i++){
          if(this.navigation[i] != element){
            if(currLevel < i){
              this.makeDeselected(this.navigation[i]);
            }else{
              this.makeParentSelected(this.navigation[i]);
            }
          }else{
            this.makeSelected(this.navigation[currLevel]);
          }   
        } 
      }
    
      if(this.levelArray.indexOf(currLevel) != -1 && elType == this.constPage){
        var levelPos = this.levelArray.indexOf(currLevel)+1;
        for(var i = levelPos; i < this.levelArray.length; i++){
          if($('navlevel'+this.levelArray[i])) $('navlevel'+this.levelArray[i]).innerHTML = '';
        }
        $('divFolderRapper').hide();
      }
    
      this.showFormContainer();

      if($(element).down('.icon').className.indexOf(this.constStartPage) == -1){
        $('buttondelete').show();
      }else{
        $('buttondelete').hide();
      }
    
      $(this.genFormContainer).show();
      $(this.genFormSaveContainer).show();    
    
      myCore.addBusyClass(this.genFormContainer);
      myCore.addBusyClass('divWidgetMetaInfos');
      
      var strAjaxAction = '/widget/%WIDGET%/form/geteditsubwidgetform';
      strAjaxAction = strAjaxAction.replace(/%WIDGET%/, widgetName);
      
      new Ajax.Updater('genFormContainer', strAjaxAction,{
    	parameters: {
    	  subWidgetId: subWidgetId,
    	  elementId: subWidgetId,
    	  currLevel: currLevel,
    	  parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
    	  widgetInstanceId: widgetInstanceId,
    	  elementType: 'subwidget',
    	  rootLevelId: this.rootLevelId
        },
        evalScripts: true,
        onComplete: function() {
          myForm.writeMetaInfos();
          // load medias
          myForm.loadFileFieldsContent('media');
          // load documents
          myForm.loadFileFieldsContent('document');	
    	  myCore.removeBusyClass('divWidgetMetaInfos');
          myCore.removeBusyClass(this.genFormContainer);
        }.bind(this)
      });
  },
  
  /**
   * getEditForm
   * @param integer itemId, string elType, integer formId, integer version, (integer templateId)
   */
  getEditForm: function(itemId, elType, formId, version, templateId){
    $(this.genFormContainer).innerHTML = '';
    $('divWidgetMetaInfos').innerHTML = '';
    // hide media container
    if($('divMediaContainer')) $('divMediaContainer').hide();      
        
    //check if templateId is assigned 
    templateId = (typeof(templateId) != 'undefined') ? templateId : -1;
            
    var element = elType+itemId;
    this.currItemId = itemId;
    
    var currLevel = 0;
    // e.g. level1 - cut level to get currLevel number
    currLevel = parseInt($(element).up().id.substr(5)); 
        
    if(this.navigation[currLevel]){
      this.makeDeselected(this.navigation[currLevel]);
    }    
    this.navigation[currLevel] = element;
    
    if(this.navigation.length > 0){      
      for(var i = 1; i <= this.navigation.length-1; i++){
        if(this.navigation[i] != element){
          if(currLevel < i){
            this.makeDeselected(this.navigation[i]);
          }else{
            this.makeParentSelected(this.navigation[i]);
          }
        }else{
          this.makeSelected(this.navigation[currLevel]);
        }   
      } 
    }
    
    if(this.levelArray.indexOf(currLevel) != -1 && elType == this.constPage){
      var levelPos = this.levelArray.indexOf(currLevel)+1;
      for(var i = levelPos; i < this.levelArray.length; i++){
        if($('navlevel'+this.levelArray[i])) $('navlevel'+this.levelArray[i]).innerHTML = '';
      }
      $('divFolderRapper').hide();
    }
    
    this.showFormContainer();

    if($(element).down('.icon').className.indexOf(this.constStartPage) == -1){
      $('buttondelete').show();
    }else{
      $('buttondelete').hide();
    }
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();
    
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
    strAjaxAction = '';    
    if(elType == this.constFolder){
      strAjaxAction = '/zoolu/core/folder/geteditform';
    }else{
      strAjaxAction = '/zoolu/cms/page/geteditform';
    }
    
    new Ajax.Updater('genFormContainer', strAjaxAction, {
       parameters: { 
         id: itemId,
         formId: formId,         
         formVersion: version,
         templateId: templateId,
         currLevel: currLevel,
         rootLevelId: this.rootLevelId,
         parentFolderId: $('navlevel'+currLevel).readAttribute('parentid'),
         elementType: elType,
         zoolu_module: this.module,
         rootLevelTypeId: this.rootLevelTypeId, 
       },      
       evalScripts: true,     
       onComplete: function() {
         myForm.writeMetaInfos();
         myCore.removeBusyClass('divWidgetMetaInfos');
         myCore.removeBusyClass(this.genFormContainer);
         // load medias
         myForm.loadFileFieldsContent('media');
         // load documents
         myForm.loadFileFieldsContent('document');		
         // load contacts
         myForm.loadContactFieldsContent();     
       }.bind(this)
     });
  },
  
  /**
   * getWidgetPropertiesForm
   * @param string widgetName
   */
  getWidgetPropertiesForm: function(widgetName, idWidgetInstances){
	$(this.genFormContainer).innerHTML = '';
	$('divWidgetMetaInfos').innerHTML = '';
	// hide media container
	if($('divMediaContainer')) $('divMediaContainer').hide();
	
	var element = 'widgetproperties';
	
	currLevel = parseInt($(element).up().id.substr(5));
	
	if(this.navigation[currLevel]){
      this.makeDeselected(this.navigation[currLevel]);
    }    
    this.navigation[currLevel] = element;
    
    if(this.navigation.length > 0){      
      for(var i = 1; i <= this.navigation.length-1; i++){
        if(this.navigation[i] != element){
          if(currLevel < i){
            this.makeDeselected(this.navigation[i]);
          }else{
            this.makeParentSelected(this.navigation[i]);
          }
        }else{
          this.makeSelected(this.navigation[currLevel]);
        }   
      } 
    }
    
    this.showFormContainer();
    
    $(this.genFormContainer).show();
    $(this.genFormSaveContainer).show();
    
    myCore.addBusyClass(this.genFormContainer);
    myCore.addBusyClass('divWidgetMetaInfos');
    
    var strAjaxAction = this.constRequestWidgetProperties.replace(/%WIDGET%/, widgetName);
    
    new Ajax.Updater('genFormContainer', strAjaxAction, {
      parameters: {
    	idWidgetInstances: idWidgetInstances
      },
      evalScript: true,
      onComplete: function(){
    	myForm.writeMetaInfos();
        myCore.removeBusyClass('divWidgetMetaInfos');
        myCore.removeBusyClass(this.genFormContainer);
      }.bind(this)
    });
  },
  
  /**
   * selectWidgetItem
   * @param integer parentLevel, string widgetType, integer itemId, string widgetInstanceId
   */  
  selectWidgetItem: function(parentLevel, widgetType, itemId, widgetInstanceId){
  	$(this.genFormContainer).hide();
    $(this.genFormSaveContainer).hide();
     
		this.currLevel = parseInt(parentLevel) + 1;
		var element = 'widget'+itemId;
		this.currItemId = itemId;
		
		if(this.navigation[parentLevel]){
	    this.makeDeselected(this.navigation[parentLevel]);
	  }
	    
	  this.navigation[parentLevel] = element;
	    
    if(this.navigation.length > 0){    
      for(var i = 1; i <= this.navigation.length-1; i++){
        if(this.navigation[i] != element){
          this.makeParentSelected(this.navigation[i]);
        }else{
          this.makeSelected(this.navigation[parentLevel]);
        }   
      } 
    }
    
    this.setParentFolderId(itemId);
		
	if(this.levelArray.indexOf(this.currLevel) == -1){
	  this.levelArray.push(this.currLevel);
	  
	  var levelContainer = '<div id="navlevel'+this.currLevel+'" rootlevelid="'+this.rootLevelId+'" parentid="'+this.getParentFolderId()+'" widgetInstanceId="'+widgetInstanceId+'" class="navlevel busy" style="left: '+(201*this.currLevel-201)+'px"></div>'; 
	  new Insertion.Bottom('divNaviCenterInner', levelContainer);
	}else{
      myCore.addBusyClass('navlevel'+this.currLevel);   
      $('navlevel'+this.currLevel).writeAttribute('parentid', this.getParentFolderId());
      $('navlevel'+this.currLevel).writeAttribute('widgetInstanceId', widgetInstanceId);
      
      var levelPos = this.levelArray.indexOf(this.currLevel);
      for(var i = levelPos; i < this.levelArray.length; i++){
        if($('navlevel'+this.levelArray[i])) $('navlevel'+this.levelArray[i]).innerHTML = '';
      }
    }
	
	new Ajax.Updater('navlevel'+this.currLevel, this.constRequestWidgetNav.replace(/%WIDGET%/, widgetType), {
	  parameters: {
			currLevel: this.currLevel,
			instanceId: $('navlevel'+this.currLevel).readAttribute('widgetInstanceId'),
			idWidgetInstances: itemId,
			elementType: 'widget'
	  },
	  evalScripts: true,
	  onComplete: function(){
	  	myCore.removeBusyClass('navlevel'+this.currLevel);
	  	this.initFolderHover();
	  	this.initPageHover();
      this.initAddMenuHover();
      this.initWidgetHover();
        
      this.scrollNavigationBar();
      this.updateCurrentWidget();
	  }.bind(this)
	});
  },
  
  /**
   * showFormContainer
   */
  showFormContainer: function(){
    if($('divThumbContainer')) $('divThumbContainer').hide();
    if($('divListContainer')) $('divThumbContainer').hide();
    if($('divFormContainer')) $('divFormContainer').show();
  },
  
  /**
   * makeSelected
   */
  makeSelected: function(element){
    if(element != ''){
      if($(element)){
	      $(element).addClassName('selected');
	      if($(element+'top')) $(element+'top').addClassName('selected');
	      if($(element+'bottom')) $(element+'bottom').addClassName('selected');
	      if($(element+'menu')) $(element+'menu').show();
	      if($(element)) $(element).removeClassName('hover');
      } 
    }
  },
  
  /**
   * makeDeselected
   */
  makeDeselected: function(element) {
    if(element != ''){
      if($(element)){
        $(element).removeClassName('selected');
	      if($(element+'menu')) $(element+'menu').hide();
	      if($(element+'top')) $(element+'top').removeClassName('selected');
	      if($(element+'bottom')) $(element+'bottom').removeClassName('selected');
	      if($(element).hasClassName('pselected')) $(element).removeClassName('pselected');
      }
    }
  },
  
  /**
   * makeParentSelected
   */
  makeParentSelected: function(element){
    if(element != ''){
      if($(element)) {
        $(element).addClassName('pselected');
        $(element).removeClassName('selected');
      }else{
        var parentId = $('navlevel'+(this.currLevel-1)).readAttribute('parentid');
        if($(this.constFolder+parentId)) {
          $(this.constFolder+parentId).addClassName('pselected');
          $(this.constFolder+parentId).removeClassName('selected');
        }
      }
    }
  },
  
  /**
   * setParentFolderId
   * @param integer parentId
   */
  setParentFolderId: function(parentId){
    this.parentFolderId = parentId;   
  },
  
  /**
   * getParentFolderId
   */
  getParentFolderId: function(){
    return this.parentFolderId;
  }
  
});