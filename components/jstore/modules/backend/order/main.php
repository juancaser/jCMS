<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access
global $config;

$userinfo = $_SESSION['__BACKEND_USER']['info'];
if($_REQUEST['opt'] == 'on-hold'){
	$title = 'On-Hold - Order';
	$title2 = 'On-Hold';
}elseif($_REQUEST['opt'] == 'shipped'){
	$title = 'Shipped - Order';
	$title2 = 'Shipped';
}elseif($_REQUEST['opt'] == 'on-going'){
	$title = 'On-Going - Order';
	$title2 = 'On-Going';
}else{
	$title = 'Pending - Order';	
	$title2 = 'Pending';
}

$userinfo = $_SESSION['__USER']['info'];
?>
<form id="item-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="content2">
            	<?php load_documentations(); ?>
                <div id="tax-shipping">	
                    <ul class="tab">
                        <li<?php echo ($_REQUEST['opt'] == '' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&mod=order">Pending (<?php echo get_order_count();?>)</a></li>
                        <li<?php echo ($_REQUEST['opt'] == 'on-going' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&mod=order&opt=on-going">On-Going (<?php echo get_order_count('ongoing');?>)</a></li>
                        <li<?php echo ($_REQUEST['opt'] == 'on-hold' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&mod=order&opt=on-hold">On-Hold (<?php echo get_order_count('onhold');?>)</a></li>
                        <li<?php echo ($_REQUEST['opt'] == 'shipped' ? ' class="active"' : '');?>><a href="<?php echo COMPONENTS_URL;?>&mod=order&opt=shipped">Shipped (<?php echo get_order_count('shipped');?>)</a></li>
                    </ul>
                    <div class="clear"></div>
                    <div class="tab-content">
						<?php
                        $filter = '';
                        if($_REQUEST['opt'] == 'on-hold'){
                            $filter = " ord.order_status='onhold'";
                        }elseif($_REQUEST['opt'] == 'shipped'){
                            $filter = " ord.order_status='shipped'";
                        }elseif($_REQUEST['opt'] == 'on-going'){
                            $filter = " ord.order_status='ongoing'";
                        }else{
                            $filter = " ord.order_status='pending'";
                        }
                        $url = BACKEND_DIRECTORY.'/components.php?comp='.JSTORE_ID.'&mod=order';
                        $numrows = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_order AS ord WHERE".$filter)->_count;
                        $sql = "SELECT ord.*,DATE_FORMAT(ord.order_date,'%M %e, %Y %h:%i:%s %p') AS date_ordered,usr.display_name,usr.email_address FROM #_order AS ord LEFT JOIN #_users AS usr ON usr.ID=ord.user_id WHERE".$filter." ORDER BY ord.order_date DESC";
                        $ordr = jcms_db_pagination($sql,$numrows,10,$url);
                        $ordr_count = count($ordr->results);
                        ?>  
                        <h2 style="padding-bottom:10px;"><?php echo $title2;?></h2>
                        <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                        <?php
                        $can_delete = true;
                        if($_REQUEST['opt'] == 'shipped'){
                            if(in_array($userinfo->user_group,array('ghosts','administrators'))){
                                $can_delete = true;
                            }else{
                                $can_delete = false;
                            }
                        }
                        ?>
                        <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <th class="col1"><?php echo ($can_delete ? '<input type="checkbox" class="check_all" />' : '');?></th>
                                <th class="col2">Name</th>
                                <th class="col3">Status</th>
                                <th class="col4">Date</th>
                            </tr>
                           <?php if($ordr_count > 0):?>
                                <?php for($i=0;$i < $ordr_count;$i++): $ord = $ordr->results[$i];$meta = unserialize(stripslashes($ord->order_meta));?>
                                    <tr class="list">
                                        <td class="col1"><?php echo ($can_delete ? '<input type="checkbox" name="chk[order][]" class="chk" value="'.$ord->ID.'" />' : '');?></td>
                                        <td class="col2">
                                            <div><a href="<?php echo BACKEND_DIRECTORY.'/components.php?comp='.JSTORE_ID.'&mod=order&opt=view&id='.$ord->ID;?>"><?php echo $ord->display_name;?> (<?php echo $ord->email_address;?>)</a></div>
                                            <div class="order-info" style="font-size:11px;line-height:1.2em;">TransID: <span style="color:#084482;">#<?php echo $ord->trans_id;?></span></div>
                                            <div class="order-info" style="font-size:11px;line-height:1.2em;">Order Quantity: <span style="color:#084482;"><?php echo count($meta);?></span></div>
                                        </td>
                                        <td class="col3"><?php
                                            switch($ord->order_status){
                                                case 'pending':
                                                    echo 'New';
                                                    break;
                                            case 'onhold':
                                                    echo 'On-Hold';
                                                    break;
                                            case 'ongoing':
                                                    echo 'On-Going';
                                                    break;
                                            case 'shipped':
                                                    echo 'Shipped';
                                                    break;
                                            }
                                        ?></td>
                                        <td class="col4"><?php echo $ord->date_ordered;?></td>
                                    </tr>
                                <?php endfor;?>
                            <?php else:?>
                                <tr>
                                    <td colspan="4">No order found.</td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="col1"><?php echo ($can_delete ? '<input type="checkbox" class="check_all" />' : '');?></th>
                                <th class="col2">Name</th>
                                <th class="col3">Status</th>
                                <th class="col4">Date</th>
                            </tr>
                        </table>                
						<?php if($ordr_count > 0 && $_REQUEST['opt'] !='shipped'):?>
                            <div style="margin-top:5px;" class="left"><input type="submit" value="Delete" /></div>
                        <?php endif;?>
                        <div id="pagination" class="right" style="margin-top:5px;">
                            <?php echo $ordr->pages; ?>
                        </div>
                        <div class="clear"></div>
                
					</div>
				</div>
			</td>
		</tr>
    </table>
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="order" />
    <input type="hidden" name="action" value="delete" />
</form>
