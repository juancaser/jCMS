<?php
include('backend-load.php'); // Backend bootstrap loader
global $backend_page;
$glb_msg = get_global_mesage('trash_action');

switch($_REQUEST['type']){
	case 'page':
		$title = _l('txt_trashed_page','Trashed Page/Post',false);

		$sql = "SELECT ID,title,CONCAT('page') AS item_type FROM ".$GLOBALS['page_tbl_name']." WHERE status='trash'";
		$trashed_items = jcms_db_get_rows($sql);
		break;
	case 'media':
		$title = _l('txt_trashed_media','Trashed Media',false);
		$sql = "SELECT ID,media_name AS title,CONCAT('media') AS item_type FROM ".$GLOBALS['media_tbl_name']." WHERE trash='1'";
		$trashed_items = jcms_db_get_rows($sql);
		break;
	default:
		$title = _l('txt_trashed_bin','View All Items',false);
		$sql = "SELECT ID,title,CONCAT('page') AS item_type FROM ".$GLOBALS['page_tbl_name']." WHERE status='trash'";
		$t1 = jcms_db_get_rows($sql);
		$trashed_items[] = $t1;
		$sql = "SELECT ID,media_name AS title,CONCAT('media') AS item_type FROM ".$GLOBALS['media_tbl_name']." WHERE trash='1'";
		$t2 = jcms_db_get_rows($sql);
		$trashed_items = array_merge($t1,$t2);
		break;
}
$count = (int) count($trashed_items);
$backend_page = (object) array('title'=>$title);

the_backend_header(); ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('.trash_button').click(function(){
			$('#action').val($(this).attr('id'));
			$('#trash-form').submit();
		});
	});
</script>
<div class="page-wrapper">
    <div class="inner">
		<form id="trash-form" method="post" action="<?php echo BACKEND_DIRECTORY;?>/trash.php">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="25%">
                        <div class="box" style="margin-right:20px;">
                            <div class="title"><?php _l('txt_page_links','What do you want to do?');?></div>
                            <div class="content">
                                <ul>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?type=page"><?php _l('txt_trashed_page','Trashed Page/Post');?></a></li>
                                    <!--li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?type=media"><?php _l('txt_trashed_media','Trashed Media');?></a></li!-->
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php"><?php _l('txt_view_all','View All');?></a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
                    <td width="75%">
                    	<?php _d($glb_msg->message,'<div class="messagebox '.$glb_msg->class.'">','</div>');?>
                        <div id="trashbin">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tr>
                                    <td style="padding-bottom:40px;" class="<?php echo ($count > 0 ? 'full' : 'empty');?>">
                                        <h2><?php echo $title;?></h2>
                                        <p style="margin-bottom:5px;">To restore select the icon and click the restore button.</p>
                                        <p style="margin-bottom:5px;">
                                        	<input type="submit" id="restore"  class="restore trash_button" value="Restore" />&nbsp;
                                            <input type="submit" id="delete" class="delete trash_button" value="Delete" />
										</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div id="trashed-items">
                                            <?php if($count > 0):?>
                                                <ul>
                                                <?php for($i=0;$i < count($trashed_items);$i++): $item = $trashed_items[$i];?>
                                                	<?php if($item->title!=''): ?>
                                                        <li id="<?php echo $item->item_type.'-'.$item->ID;?>" class="<?php echo $item->item_type;?>">
                                                            <a class="item" href="javascript:void(0);">
                                                                <div class="checked"><input type="hidden" class="item_id" value="<?php echo $item->ID;?>" /> </div>
                                                                <div class="title"><?php
                                                                    echo $item->title;
                                                                    if($_REQUEST['opt'] == 'page' && $item->page_key == 'draft'){
                                                                        echo ' [DRAFT]';
                                                                    }																   
                                                                ?></div>
                                                            </a>
                                                        </li>
                                                	<?php endif;?>
                                                <?php endfor;?>
                                                </ul>
                                            <?php else:?>
                                            <p>No <?php echo $title;?></p>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <input type="hidden" id="action" name="action" value="restore" />
            <input type="hidden" name="form" value="trash" />
		</form>
    </div>
</div>
<?php the_backend_footer(); ?>