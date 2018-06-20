<?php
if(!defined('JCMS')){exit();} // No direct access
$userinfo = $_SESSION['__USER']['info'];

// get Current parent Page
if($_REQUEST['id']!=''){
	$parent = get_page($_REQUEST['id']);
}

$filter = '';
if($_REQUEST['id'] > 0){
	$filter = "pg.parent_page='".$_REQUEST['id']."' ";
}else{
	$filter = "pg.parent_page='0' ";
}


$url = BACKEND_DIRECTORY.'/pages.php'.($_REQUEST['id'] > 0 ? '?id='.$_REQUEST['id'] : '');					
$numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM ".$GLOBALS['page_tbl_name']." AS pg WHERE pg.page_type='page' AND pg.status='published'".($filter!=''? ' AND '.$filter : ''))->_count;
$sql = "SELECT pg.ID,pg.title,u.display_name AS author,DATE_FORMAT(pg.date_created,'%Y/%m/%d') AS published_date FROM ".$GLOBALS['page_tbl_name']." AS pg 
	LEFT JOIN ".$GLOBALS['users_tbl_name']." AS u ON u.ID=pg.author WHERE pg.page_type='page' AND pg.status='published'".($filter!=''? ' AND '.$filter : '');

$pages = jcms_db_pagination($sql,$numrows,15,$url);
$page_count = count($pages->results);
?>
<form id="page-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/pages.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Page</div>
                    <div class="content">
	                    <p style="margin-bottom:5px;"><input class="button" type="button" value="Add <?php echo ($_REQUEST['id'] > 0 ? ' Sub' : '')?>Page" onclick="window.location='<?php echo CURRENT_PAGE;?>?mod=edit<?php echo ($_REQUEST['id'] > 0 ? '&parent='.$_REQUEST['id'] : '')?>';" /></p>
                        <p style="margin-bottom:5px;"><input class="button" type="button" value="Trashed" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/trash.php?type=page';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
				<?php load_documentations(); ?>
            	<h2><?php echo $title;?></h2>                
                 <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <div style="padding:0;margin:0;"><?php if($_REQUEST['id']!=''){
					page_breadcrumb($parent->ID);
				}?></div>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date</th>
                    </tr>

                    <?php if($page_count > 0):?>
                    	<?php $oe = 'odd';?>
						<?php for($i=0;$i < $page_count;$i++): $page = $pages->results[$i];?>
                            <tr class="list <?php echo $oe;?>">
                                <td class="col1"><input type="checkbox" name="chk[]" class="chk" value="<?php echo $page->ID;?>" /></td>
                                <td class="col2">
                                    <span class="title"><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?id=<?php echo $page->ID;?>"><?php echo $page->title;?></a></span>
                                    <div class="author"><strong>Author:</strong> <em><?php echo $page->author;?></em></div>
                                    <div class="control">
                                        <a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=edit&id=<?php echo $page->ID;?>">Edit</a> | 
                                        <a href="<?php echo _generate_page_permalink($page->ID);?>" target="_blank">View</a> | 
                                        <a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?action=trashed&chk[]=<?php echo $page->ID;?>">Trashed</a>
                                     </div>
                                </td>
                                <td class="col3"><?php echo $page->published_date;?>
                                    <div class="status">Published</div>
                                </td>
                            </tr>
                             <?php $oe = ($oe == 'odd' ? 'even' : 'odd');?>
                        <?php endfor;?>
                    <?php else:?>
                        <tr class="notify">
                            <td colspan="3">No page found.</td>
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
				$back = BACKEND_DIRECTORY.'/pages.php';
				if($parent->parent_page > 0){
					$back = $back.'?id='.$parent->parent_page;
				}
                ?>                
                <input type="button" value="Back" onclick="window.location='<?php echo $back;?>';" />
                <?php endif; ?>&nbsp;<input type="submit" value="Delete" /></div>
                <div id="pagination" class="right" style="margin-top:5px;">
					<?php echo $pages->pages; ?>
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