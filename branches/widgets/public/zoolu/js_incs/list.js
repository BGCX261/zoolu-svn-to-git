/**
 * list.js
 *
 * Version history (please keep backward compatible):
 * 1.0, 2009-11-24: Daniel Rotter
 *
 * @author Daniel Rotter <daniel.rotter@massiveart.com>
 * @version 1.0
 */
var List = Class.create({
  
  initialize: function(deleteAction){
    this.constRequest = deleteAction;
  },
  
  deleteSelectedElements: function(){
    var form = $('genForm');
    var elements = form.getInputs('checkbox', 'listElement');
    for(var i=0;i<elements.size();i++)
    {
      if(elements[i].checked)
      {
        elements[i].parentNode.parentNode.remove();
        new Ajax.Updater('', this.constRequest, {
          parameters: {
            id: elements[i].value
          }
        });
      }
    }
  }
  
});