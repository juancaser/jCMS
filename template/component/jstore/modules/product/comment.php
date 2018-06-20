<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<?php
$userinfo = get_user($_SESSION['__FRONTEND_USER']['info']->ID);
$product_id = ($product->ID > 0 ? $product->ID : $_REQUEST['product_id']);
$max_comment =  10;
$comments = get_comments('product',$product_id,'ASC','c.commented_date',$max_comment); // Comments
?>
<div class="comments">
    <div class="lists left<?php echo (count($comments) > 0 ? '' : ' comment-lists-empty');?>">
    	<?php if(count($comments) > 0):?>
            <ul id="comments">
        	<?php for($i=0;$i <= count($comments);$i++):$comment = $comments[$i];$meta = (object)unserialize($comment->meta);?>
            	<?php if($comment->comment_message!=''):?>
                    <li class="comment" id="comment<?php echo $comment->ID;?>">
                        <div class="comment-header">
                            <span class="author">
                            	<?php
								$author = ($comment->author !='' ? $comment->author : ($comment->comment_author !='' ? $comment->comment_author : 'Anonymous'));								
								$website = (trim($meta->website)=='NA' ? '' : trim($meta->website));								
								sanitize_author_name($author,$website);
                                ?>
							</span> said:
                        </div>
                        <div class="message-container">
                            <div class="arrow"></div>
                            <div class="message">
	                            <div class="body"><?php
									sanitize_comment_message(html_entity_decode(nl2br($comment->comment_message)));
								?></div>
	                            <div class="footer">
                                	<!--span class="date">Submitted <?php echo get_nicetime($comment->commented_date);?></span-->
                                	<span class="date">Posted <?php sanitize_comment_date($comment->commented_date);?></span>
                                    <div class="control"><a href="javascript:comment_reply('<?php echo $comment->ID;?>');" class="reply">Reply</a></div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </li>
				<?php endif;?>
			<?php endfor;?>
            </ul>
            <?php if(count_comments($product_id,'product') < $max_comment && !isset($_REQUEST['cv'])):?>
				<a href="<?php item_url($page_id)?>?cv=all" style="color:#333333;font-weight:bold;">View all comments &raquo;</a>
			<?php endif;?>
    	<?php else:?>
	        <p style="font-size:16px;font-weight:normal;">No comments. Be the first one to make.</p>
    	<?php endif;?>    
    </div>
    <div class="form right">
    <?php if(!is_user_logged() && get_option('comment_requires_login') == 'yes'):?>
    	<p>Where's the form?. Click <a href="<?php echo store_info('login');?>?redirect=<?php echo rawurlencode(item_url($product_id,false));?>" rel="nofollow">here</a> to login and start commenting. <a href="<?php store_info('register');?>">Not yet a member?</a></p>
	<?php else:?>
    <script type="text/javascript">
	$(document).ready(function(){
		/* Comment textarea*/
		var max_comment = <?php echo $max_comment;?>;
		var max_txt = $('#comment_message').attr('max');
		$('#comment_message').removeAttr('max');
		$('#comment_message_left').val(max_txt);
		$('#comment_message').keyup(function(){
			var len = this.value.length;		
			if(len >= max_txt) {
				this.value = this.value.substring(0, max_txt);
			}
			if(len <= max_txt){
				$('#comment_message_left').val(max_txt - len);
			}
		});
		
		var ajax_url = $('#comment-form').attr('action');
		$('#comment-form').removeAttr('action');
		$('#comment-form').find('.required').focus(function(){
			$(this).removeClass('error');
			$('#comment-notice').fadeOut('slow',function(){$(this).addClass('hide').html('');});
		});
		
	
		$('#comment-form').submit(function(){
			var form = $(this);		
			$('.required').filter(function(){($(this).val() == '' ? $(this).addClass('error') :  '');return;});
			
			if(form.find('.error').length == 0){
				var arg = '';		
				form.find('.comment-fields').each(function(){
					var field = $(this);
					arg += '&' + field.attr('name') + '=' + field.val()
				});
				$.ajax({
					type: 'POST',
					url: ajax_url,
					data: 'action=post_comment' + arg,
					success: function(msg){
						var obj = $.parseJSON(msg);
						if(obj.status=='1' || obj.status=='2'){
							if(obj.status=='1'){
								$('#comment-notice').html('Thank you for posting.').fadeIn('slow',function(){
									$(this).removeClass('hide');
								});
							}else if(obj.status=='2'){
								$('#comment-notice').html('Thank you for posting. Your comment is waiting moderation.').fadeIn('slow',function(){
									$(this).removeClass('hide');
								});
							}
							form.find('.txtfield').val('');
							$('#comment_message_left').val(max_txt);
							
							if(obj.status=='1'){	
								if($('.comments').find('.lists').hasClass('comment-lists-empty') == true){
									$('.comments').find('.lists').html('<ul id="comments"></ul>').removeClass('comment-lists-empty');
								}
								if($('#comments > .comment').length() <= max_comment){
									var html = '<li id="comment' + obj.id + '" style="display:none;"><div class="comment-header"><span class="author">' + (obj.author != '' ? obj.author : 'Anonymous') + '</span> said:</div><div class="message-container"><div class="arrow"></div><div class="message"><div class="body">' + obj.message + '</div><div class="footer"><span class="date">Submitted ' + obj.date + '</span><div class="control"><a href="javascript:comment_reply(\'' + obj.id + '\');" class="reply">Reply</a></div><div class="clear"></div></div></div></div></li>';
									$('#comments').append(html);
									$('#comment' + obj.id).fadeIn('slow',function(){
										$(this).css('display','');
									});
								}
							}
						}
					}
				});
			}
			return false;
		});
	});
    </script>
    	<div id="comment-notice" class="msgbox hide"></div>
    	<form id="comment-form" method="post" action="<?php get_siteinfo('template_directory');?>/ajax.php">
            <input class="comment-fields required" type="hidden" name="comment[comment_type]" value="product" />
            <input class="comment-fields required" type="hidden" name="comment[page_id]" value="<?php echo $product_id;?>" />
        <?php if(is_user_logged()):?>
            <input class="comment-fields required" type="hidden" name="comment[comment_author]" value="<?php echo $userinfo->ID;?>" />
            <input class="comment-fields required" type="hidden" name="comment[meta][email]"  value="<?php echo $userinfo->email_address;?>" />
            <input class="comment-fields" type="hidden" name="comment[meta][website]" value="NA" />
        <?php else:?>
        	<p>
            	<label for="comment_author_name">Name <small>Required</small></label>
                <input class="comment-fields txtfield required" type="text" name="comment[comment_author_name]" id="comment_author_name" value="" />
            </p>
        	<p>
            	<label for="comment_email">Email <small>Required</small></label>
                <input class="comment-fields txtfield required" type="text" name="comment[meta][email]"  id="comment_email" value="" />
            </p>
        	<p>
            	<label for="comment_website">Website <small>Optional</small></label>
                <input class="comment-fields txtfield" type="text" name="comment[meta][website]" id="comment_website" value="" />
            </p>
        <?php endif;?>
        	<p>
            	<label for="comment_message">Message <small>Required</small></label>
                <textarea class="comment-fields txtfield required" name="comment[comment_message]" id="comment_message" max="300"></textarea>
                <div><input type="text" id="comment_message_left" style="width:30px;text-align:center;" value="0" readonly="readonly" />  Characters left</div>
            </p>
        	<p style="padding-top:10px;"><input type="submit" class="button" value="Post Comment" /></p>
        </form>
	<?php endif;?>
    </div>
    <div class="clear"></div>
</div>