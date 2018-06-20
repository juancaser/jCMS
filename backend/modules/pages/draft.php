<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__USER']['info'];

// get Current parent Page
if($_REQUEST['id']!=''){
	$parent = get_page($_REQUEST['id']);
}

$title = 'Draft Posts/Pages';
$filter = '';
?>
<form id="page-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/pages.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="content-2" style="padding:0;">
            	<?php load_documentations(); ?>
            	<h2><?php echo $title;?></h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date</th>
                    </tr>	 
					<?php				
					$url = BACKEND_DIRECTORY.'/pages.php?mod=draft'.($_REQUEST['id'] > 0 ? '&id='.$_REQUEST['id'] : '');					

					$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.status='draft'")->_count;
                    $sql = "SELECT pg.ID,pg.title,u.display_name AS author,DATE_FORMAT(pg.date_modified,'%Y/%m/%d') AS modified_date FROM ".$GLOBALS['page_tbl_name']." AS pg LEFT JOIN ".$GLOBALS['users_tbl_name']." AS u ON u.ID=pg.author WHERE pg.status='draft'";
					$posts = jcms_db_pagination($sql,$numrows,15,$url);
					$post_count = count($posts->results);
                    ?>
                    <?php if($post_count > 0):?>
	                    <?php $oe = 'odd';?>
						<?php for($i=0;$i < $post_count;$i++): $post = $posts->results[$i];?>
                            <tr class="list <?php echo $oe;?>">
                                <td class="col1"><input type="checkbox" name="chk[]" class="chk" value="<?php echo $post->ID;?>" /></td>
                                <td class="col2">
                                    <span class="title"><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?id=<?php echo $post->ID;?>"><?php echo $post->title;?></a></span>
                                    <div class="author"><strong>Author:</strong> <em><?php echo $post->author;?></em></div>
                                    <div class="control">
                                        <a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=edit&id=<?php echo $post->ID;?>">Edit</a> | 
                                        <a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?form=page&action=trashed&chk[]=<?php echo $post->ID;?>">Trashed</a>
                                     </div>
                                </td>
                                <td class="col3"><?php echo $post->modified_date;?>
                                    <div class="status">Last Modified</div>
                                </td>
                            </tr>
                            <?php $oe = ($oe == 'odd' ? 'even' : 'odd');?>
                        <?php endfor;?>
                    <?php else:?>
                        <tr class="notify">
                            <td colspan="3">No draft posts or page found.</td>
                        </tr>
                    <?php endif;?>
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date</th>
                    </tr>
                </table>
                 <div style="margin-top:5px;" class="left"><?php if($_REQUEST['id']!=''): ?>
                <?php
				$back = BACKEND_DIRECTORY.'/pages.php?mod=draft';
				if($parent->parent_page > 0){
					$back = $back.'&id='.$parent->parent_page;
				}
                ?>                
                <input type="button" value="Back" onclick="window.location='<?php echo $back;?>';" />
                <?php endif; ?>&nbsp;<input type="submit" value="Delete" /></div>
                <div id="pagination" class="right" style="margin-top:5px;">
					<?php echo $posts->pages; ?>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
    </table>
    <input type="hidden" name="author" value="<?php echo ($page->author > 0 ? $page->author : $userinfo->ID);?>" />
    <?php if($page->ID > 0):?><input type="hidden" name="ID" value="<?php echo $page->ID;?>" /><?php endif;?>
    <input type="hidden" id="slug_check" value="<?php echo ($page->ID > 0 ? 'no' : 'yes');?>" />
    <input type="hidden" name="action" value="save" />
    <input type="hidden" name="mod" value="edit" />
    <input type="hidden" name="form" value="page" />    
</form>