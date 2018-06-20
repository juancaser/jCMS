<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access
$userinfo = $_SESSION['__BACKEND_USER']['info'];
if($_REQUEST['view'] == 'pending'){
	$title = 'Pending - Comments';
	$title2 = 'Pending';
}else{
	$title = 'Published - Comments';	
	$title2 = 'Published';
}

$filter = '';
if($_REQUEST['view'] == 'pending'){
	$filter = " cmt.comment_status='pending'";
}else{
	$filter = " cmt.comment_status='published'";
}
$url = BACKEND_DIRECTORY.'/components.php?comp='.JCOMMENTS_ID;
$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_comments AS cmt WHERE".$filter)->_count;
$sql = "SELECT cmt.*,DATE_FORMAT(cmt.commented_date,'%M %e, %Y %h:%i:%s %p') AS date_commented,usr.display_name AS author,usr.email_address AS email FROM #_comments AS cmt LEFT JOIN #_users AS usr ON usr.ID=cmt.comment_author WHERE".$filter." ORDER BY cmt.commented_date DESC";
$comments = jcms_db_pagination($sql,$numrows,2,$url);
$comment_count = count($comments->results);
//print_r($comments->results);
?>
<script type="text/javascript">
	function approve(){
		$('#action').val('approve');
		document.getElementById('item-viewer').submit();
	}
	function _delete(){
		$('#action').val('delete');
		document.getElementById('item-viewer').submit();
	}
</script>
<div id="comments">
	<ul class="tab">
    	<li<?php echo ($_REQUEST['view'] == '' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>">Comments</a></li>
    	<li<?php echo ($_REQUEST['view'] == 'pending' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&view=pending">Pending Moderation</a></li>
    </ul>
    <div class="clear"></div>
    <div class="tab-content">
        <div class="ts-content">

            <form id="item-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 
                <h2 style="padding-bottom:10px;"><?php echo $title2;?></h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Page/Posts</th>
                        <th class="col4">Date</th>
                    </tr>
                   <?php if($comment_count > 0):?>
						<?php for($i=0;$i < $comment_count;$i++): $comment = $comments->results[$i];$meta = (object)unserialize(stripslashes($comment->meta));?>
                            <tr class="list">
                                <td class="col1"><input type="checkbox" name="chk[comment][]" class="chk" value="<?php echo $comment->ID;?>" /></td>
                                <td class="col2">
                                    <div><a href="<?php echo BACKEND_DIRECTORY.'/components.php?comp='.JCOMMENTS_ID.'&mod=view&id='.$comment->ID;?>"><?php echo ($comment->author!=''?$comment->author:($comment->comment_author!=''?$comment->comment_author:'Anonymous'));?></a></div>
                                    <div class="order-info" style="font-size:11px;line-height:1.2em;">Status: <span style="color:#084482;"><?php echo ($comment->comment_status == 'published' ?  'Published' : 'Waiting Moderation');?></span></div>
                                    <div class="order-info" style="font-size:11px;line-height:1.2em;">Email: <span style="color:#084482;"><?php echo ($comment->email!=''?$comment->email:($meta->email!=''?$meta->email:'No Email'));?></span></div>
                                    <div class="order-info" style="font-size:11px;line-height:1.2em;">IP Address: <span style="color:#084482;"><?php echo $comment->ipaddress;?></span></div>
                                </td>
                                <td class="col3"><?php
      								if($comment->comment_type == 'product'){
										echo get_product_info($comment->page_id,array('title'))->title;
									}
                                ?></td>
                                <td class="col4"><?php echo $comment->date_commented;?></td>
                            </tr>
                        <?php endfor;?>
                    <?php else:?>
                        <tr>
                            <td colspan="4">No comments found.</td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Page/Posts</th>
                        <th class="col4">Date</th>
                    </tr>
                </table>                
               	<div style="margin-top:5px;" class="left">
                	<input type="button" value="DELETE" onclick="_delete();" />
					<?php if($_REQUEST['view'] == 'pending'):?>
                    <input id="button" type="button" value="APPROVE" onclick="approve();" />
                    <input type="hidden" name="view" value="pending" />
                    <?php endif;?>
                </div>
                <div id="pagination" class="right" style="margin-top:5px;">
                    <?php echo $comments->pages; ?>
                </div>
                <div class="clear"></div>                
                <input type="hidden" name="comp" value="<?php echo JCOMMENTS_ID;?>" />                
                <input type="hidden" id="action" name="action" value="delete" />
            </form>
        </div>
    </div>
</div>
