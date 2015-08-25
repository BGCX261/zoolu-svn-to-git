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
    this.map = null;
    this.geocoder = null;
    this.marker = null;
    
    this.reValue = false;
    
    this.currQ;
    this.currYear;    
    this.newQ;
    this.newYear;    
    this.currEventCalListId = '';
    this.newEventCalListId = '';
    
    this.bannerCounter = 0;
    this.bannerDirection = 'right';
    this.bannerStop = false;
  },
  
  init: function(){
    if($('divScrollLeft')) $('divScrollLeft').observe('click', function(){ this.bannerStop = true; }.bind(this));
    if($('divScrollRight')) $('divScrollRight').observe('click', function(){ this.bannerStop = true; }.bind(this));
    setTimeout('myDefault.bannerCarousel()', 8000);
  },
  
  /**
   * initGmap
   */
  initGmap: function(){
    if($('divEventLocationMap')){
      if(GBrowserIsCompatible()) {
        this.map = new GMap2($('divEventLocationMap'));
        var center = new GLatLng(47.22982711058905, 9.897308349609375);
        this.map.setCenter(center, 7);
        this.geocoder = new GClientGeocoder();
      }
    }
  },
  
  /**
   * listScrollUp
   */
  listScrollUp: function(){
    var elementPosTop = parseInt($('divSidebarList').style.top.replace('px', ''));
    if(elementPosTop < 0){
      new Effect.Move('divSidebarList', { x: 0, y: (elementPosTop + 360), mode: 'absolute' });
    }
  },
  
  /**
   * listScrollDown
   */
  listScrollDown: function(){
    var elementPosTop = parseInt($('divSidebarList').style.top.replace('px', ''));
    var elementHeight = $('divSidebarList').getHeight();
    if(elementPosTop > ((elementHeight*(-1))/2)){
      new Effect.Move('divSidebarList', { x: 0, y: (elementPosTop - 360), mode: 'absolute' });
    }
  },
  
  /**
   * listSlideRight
   */
  listSlideRight: function(currElement, newElement){
    if($(newElement)){
      if(parseInt($(newElement).style.left) <= 680 && parseInt($(newElement).style.left) > 0){
        new Effect.Move(newElement, { x: 0, y: 0, mode: 'absolute', duration: 0.6 });
        new Effect.Move(currElement, { x: -680, y: 0, mode: 'absolute', duration: 0.6 });
      }
    }
  },
  
  /**
   * listSlideLeft
   */
  listSlideLeft: function(currElement, newElement){
    if($(newElement)){
      if(parseInt($(newElement).style.left) >= -680 && parseInt($(newElement).style.left) < 0){
        new Effect.Move(newElement, { x: 0, y: 0, mode: 'absolute', duration: 0.6 });
        new Effect.Move(currElement, { x: 680, y: 0, mode: 'absolute', duration: 0.6 });
      }
    }
  },
  
  /**
   * bannerSlideRight
   */
  bannerSlideRight: function(currElement, newElement){
    if($(newElement)){
      if(parseInt($(newElement).style.left) <= 618 && parseInt($(newElement).style.left) > 0){
        new Effect.Move(newElement, { x: 0, y: 0, mode: 'absolute', duration: 0.6 });
        new Effect.Move(currElement, { x: -618, y: 0, mode: 'absolute', duration: 0.6 });
        this.bannerCounter++;
      }
    }
  },
  
  /**
   * bannerSlideLeft
   */
  bannerSlideLeft: function(currElement, newElement){
    if($(newElement)){
      if(parseInt($(newElement).style.left) >= -618 && parseInt($(newElement).style.left) < 0){
        new Effect.Move(newElement, { x: 0, y: 0, mode: 'absolute', duration: 0.6 });
        new Effect.Move(currElement, { x: 618, y: 0, mode: 'absolute', duration: 0.6 });
        this.bannerCounter--;
      }
    }
  },
  
  /**
   * bannerCarousel
   */
  bannerCarousel: function(){
    if(this.bannerStop == false){
      if($('divDynBanner_' + this.bannerCounter)){
	      if(this.bannerDirection == 'right' && !($('divDynBanner_' + (this.bannerCounter+1)))){
	        this.bannerDirection = 'left';
	      }
	      else if(this.bannerDirection == 'left' && !($('divDynBanner_' + (this.bannerCounter-1)))){
	        this.bannerDirection = 'right';
	      }
	      this.bannerSlide(this.bannerDirection);
	      setTimeout('myDefault.bannerCarousel()', 8000); 
	    } 
    }
  },
  
  /**
   * bannerSlide
   */
  bannerSlide: function(direction){
    if(typeof(direction) == 'undefined') direction = 'right';
    if(direction == 'left'){
      this.bannerSlideLeft('divDynBanner_' + this.bannerCounter, 'divDynBanner_' + (this.bannerCounter-1));
    }else{
      this.bannerSlideRight('divDynBanner_' + this.bannerCounter, 'divDynBanner_' + (this.bannerCounter+1));
    } 
  },   
  
  /**
   * imgGalleryShowAll
   */
  imgGalleryShowAll: function(elementHide){
    if($('divImageGallery')){
      var images = $('divImageGallery').innerHTML;
      $('divImageGallery').remove();
      if($(elementHide)) $(elementHide).hide();
      new Insertion.After($(elementHide), images);
    }
  },
  
  /**
   * toggleElement
   */
  toggleElement: function(elementId, dblDuration){
    if(typeof(dblDuration) == 'undefined'){
      dblDuration = 0.2;   
    }
    if($(elementId)){
      if($(elementId).style.display == 'none'){
        Effect.SlideDown(elementId, {duration: dblDuration});
      }else{
        Effect.SlideUp(elementId, {duration: dblDuration});
      }
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
      
      this.validateInput('fname');
      this.validateInput('sname');
      this.validateInput('mail');
      
      if(this.retValue == true) {
	      if($('noticebox')) $('noticebox').hide();
	      //this.addBusyClass('divFormButton');
	      
	      /**
	       * serialize form
	       */
	      var serializedForm = $(formId).serialize();
	      
	      new Ajax.Request($(formId).readAttribute('action'), {
	        parameters: serializedForm,
	        evalScripts: true,
	        onComplete: function(transport) {         
	          //this.removeBusyClass('divFormButton');
	          this.toggleElement('divEventForm', 0.5);
	          if($('succesbox')){
	            $('succesbox').show();
	            new Effect.Highlight('succesbox', { startcolor: '#ffff99', endcolor: '#ffffff' });
	            setTimeout('$("succesbox").fade()', 1500);
	          }    
	        }.bind(this)
	      });
	    }else{
	      //this.removeBusyClass('divFormButton');
        if($('noticebox')) $('noticebox').show();
	    }
    }
  },
  
  /**
   * validateInput
   */
  validateInput: function(element, baseValue) {
    if(($(element) && $F(element).blank()) || $F(element) == baseValue){
      $(element).addClassName('missinginput');
      this.retValue = false;
    }else{
      $(element).removeClassName('missinginput');
    }
  },
  
  /**
   * showAddress
   */
  showAddress: function(address) {
    if(typeof(address) != 'undefined') {
      if (this.geocoder) {
        var myMap = this.map;
        var myMaker = this.marker;
        this.geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
              myMap.setCenter(point, 14);
              myMaker = new GMarker(point);
              myMap.addOverlay(myMaker);
            }
          }
        );
      }
    }
  },
  
  /**
   * loadNextEvents
   */
  loadNextEvents: function(){
    if(typeof(this.newQ) != 'undefined') this.currQ = this.newQ;
    if(typeof(this.newYear) != 'undefined') this.currYear = this.newYear;    
    this.currEventCalListId = 'divEventCalList_Q' + this.currQ + '_' + this.currYear;
    
    if($(this.currEventCalListId)){
      this.newQ = parseInt(this.currQ) + 1;
      this.newYear = parseInt(this.currYear);
      if(this.newQ > 4){
        this.newQ = 1;
        this.newYear = this.newYear + 1; 
      }
      this.newEventCalListId = 'divEventCalList_Q' + this.newQ + '_' + this.newYear;
      
      // if $(newElementId) exists and isn't empty, don't reload - else ajax update
      if($(this.newEventCalListId) && $(this.newEventCalListId).innerHTML != ''){
        this.getCurrEventListHeadline();
        this.listSlideRight(this.currEventCalListId, this.newEventCalListId);
        this.getCurrEventListHeight(this.newEventCalListId);
      }else{
        var newElementContainer = '<div id="'+this.newEventCalListId+'" style="position:absolute; z-index:10; top:0; left:680px; width:678px;"></div>';
        new Insertion.After(this.currEventCalListId, newElementContainer);
        
        // ajax load events
        if($(this.newEventCalListId)){
          var newLoader = '<div id="divLoader_Q' + this.newQ + '_' + this.newYear + '" style="display:none; position: absolute; top:0; left:0; width:678px; height:100%; background-color:#fff;"></div>';
          $(this.newEventCalListId).innerHTML = newLoader; 
          
          this.addBusyClass('divLoader_Q'+this.currQ+'_'+this.currYear, true);
          new Ajax.Updater(this.newEventCalListId, '/zoolu-website/event/list', {
	          parameters: { 
	            quarter: this.newQ,
              year: this.newYear
	          },
	          insertion: Insertion.Bottom,
	          evalScripts: true,
	          onComplete: function() {         
	            this.removeBusyClass('divLoader_Q'+this.currQ+'_'+this.currYear, true);
	            this.getCurrEventListHeadline();
	            this.listSlideRight(this.currEventCalListId, this.newEventCalListId);
	            this.getCurrEventListHeight(this.newEventCalListId);    
	          }.bind(this)
	        });
        }
      }
    }    
  },
  
  /**
   * loadPrevEvents
   */
  loadPrevEvents: function(){    
    if(typeof(this.newQ) != 'undefined') this.currQ = this.newQ;
    if(typeof(this.newYear) != 'undefined') this.currYear = this.newYear;
    this.currEventCalListId = 'divEventCalList_Q' + this.currQ + '_' + this.currYear;
     
    if($(this.currEventCalListId)){
      this.newQ = parseInt(this.currQ) - 1;
      this.newYear = parseInt(this.currYear);
      if(this.newQ < 1){
        this.newQ = 4;
        this.newYear = this.newYear - 1; 
      }
      this.newEventCalListId = 'divEventCalList_Q' + this.newQ + '_' + this.newYear;
      
      // if $(newElementId) exists and isn't empty, don't reload - else ajax update
      if($(this.newEventCalListId) && $(this.newEventCalListId).innerHTML != ''){
        this.getCurrEventListHeadline();
        this.listSlideLeft(this.currEventCalListId, this.newEventCalListId);
        this.getCurrEventListHeight(this.newEventCalListId);
      }else{
        var newElementContainer = '<div id="'+this.newEventCalListId+'" style="position:absolute; z-index:10; top:0; left:-680px; width:678px;"></div>';
        new Insertion.Before(this.currEventCalListId, newElementContainer);
        
        // ajax load events
        if($(this.newEventCalListId)){
          var newLoader = '<div id="divLoader_Q' + this.newQ + '_' + this.newYear + '" style="display:none; position: absolute; top:0; left:0; width:678px; height:100%; background-color:#fff;"></div>';
          $(this.newEventCalListId).innerHTML = newLoader;
          
          this.addBusyClass('divLoader_Q' + this.currQ + '_' + this.currYear, true);
          new Ajax.Updater(this.newEventCalListId, '/zoolu-website/event/list', {
            parameters: { 
              quarter: this.newQ,
              year: this.newYear
            },
            insertion: Insertion.Bottom,
            evalScripts: true,
            onComplete: function() {         
              this.removeBusyClass('divLoader_Q' + this.currQ + '_' + this.currYear, true);
              this.getCurrEventListHeadline();
              this.listSlideLeft(this.currEventCalListId, this.newEventCalListId);
              this.getCurrEventListHeight(this.newEventCalListId);    
            }.bind(this)
          });
        }
      }
    }
  },
  
  /**
   * getCurrEventListHeight
   */
  getCurrEventListHeight: function(elementId){
    if($(elementId)){
      var newHeight = $(elementId).getHeight(); 
      if($('divEventCalContainer')) $('divEventCalContainer').style.height = newHeight + 'px';
    }
  },
  
  /**
   * getCurrEventListHeadline
   */
  getCurrEventListHeadline: function(){
    if($('divQuarterHeadline_Q' + this.newQ + '_' + this.newYear)){
      if($('divEventHeaderText')) $('divEventHeaderText').innerHTML = $('divQuarterHeadline_Q' + this.newQ + '_' + this.newYear).innerHTML;
    }
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
  }
  
});