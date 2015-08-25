/*
 * Blog Widget
 * @version 1.0
 */

/*
 * updateCommentList
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function updateCommentList(){
	var comment = '<div style="margin-bottom: 20px; background-color: #CCCCCC; padding: 5px;"><strong><a href="mailto:'+$F('blogWidgetCommentMail')+'">'+$F('blogWidgetCommentName')+'</a></strong>:<br/><i>Gerade eben</i><br/>'+$F('blogWidgetCommentText')+'</div>';
	$('blogWidgetComments').insert({top: comment});
	$('blogWidgetCommentForm').hide();
}

/*
 * widgetBlogAddComment
 * @author Florian Mathis <flo@massiveart.com>
 * @version 1.0
 */
function widgetBlogAddComment(){
	new Ajax.Request('/widget/blog/comment/add', {
	  method: 'post',
      parameters: $('blogWidgetCommentForm').serialize(),
      onSuccess: function(){
		$('blogWidgetCommentInfo').show();
		$('blogWidgetCommentForm').hide();
	  },
	  onComplete: function(){
	    updateCommentList();
	  }
	});
}