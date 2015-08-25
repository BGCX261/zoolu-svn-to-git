<!-- Content Section -->
  <div class="wrapper content">
    <div class="subwrapper">
      <div class="inner detail">        
        <!-- Main Content -->
				<?php if (isset($this->objEntry->title)): print '<h1>'.$this->objEntry->title.'</h1>'; endif; ?>
				von <em><?php if (isset($this->objEntry->username)): print $this->objEntry->username; endif; ?></em> am
				<?php if (isset($this->objEntry->created)): print date("d.m.Y\, H:i \\U\\h\\r",strtotime($this->objEntry->created)); endif; ?>
				<br/><br/>
				<?php get_image_main('220x', true, false, '660x', 'divImgLeft'); ?>
				
				<?php print $this->objEntry->text; ?>
				
			 <?php get_blog_text_blocks('220x', true, false, '660x'); ?>  
			 
			 <!-- Tags -->
			 <?php 
			 	if(count($this->arrTags) > 0):
			 		print 'Tags: ';
			 		$strTags="";
			 		foreach($this->arrTags AS $tag):
			 			$strTags .= $tag['title']. ', ';
			 		endforeach;	
			 		print trim($strTags, ', ').'<br/><br/>';	
			 	endif;
			 ?>
			 
			 <!-- Comment Form -->
			 <div id="blogWidgetCommentInfo" style="margin: 0px 0px 20px 0px; display: none;">Vielen Dank f&uuml;r Ihren Kommentar.</div>
				<form name="addComment" method="post" id="blogWidgetCommentForm" onsubmit="widgetBlogAddComment(); return false;">
					<strong>Eine Antwort schreiben</strong><br/>
					<input type="hidden" id="idBlogentry" name="idBlogentry" value="<?php print $this->objEntry->blogEntryId; ?>"/>
					<label for="name">Name</label><input type="text" name="blogWidgetCommentName" id="blogWidgetCommentName"/><br/>
					<label for="mail">Mail</label><input type="text" name="blogWidgetCommentMail" id="blogWidgetCommentMail"/><br/>
					<textarea name="blogWidgetCommentText" id="blogWidgetCommentText"></textarea><br/>
					<input type="submit" name="submit" value="Absenden"/>
				</form>
				
				<!-- Comment List -->
				<div id="blogWidgetComments">
					<?php 
						if(count($this->comments) > 0):
							foreach($this->comments AS $comment):
								print '<div style="margin-bottom: 20px; background-color: #CCCCCC; padding: 5px;"><strong><a href="mailto:'.$comment['mail'].'">'. $comment['title'].'</a></strong>:<br/><i>'.date("d.m.Y\, H:i \\U\\h\\r", strtotime($comment['created'])).'</i><br/>'.$comment['text'].'</div>';
							endforeach;
						endif;
					?>
				</div>
        <div class="clear"></div>
      </div>  
    </div>
  </div>