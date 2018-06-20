<?php
if(!defined('IMPARENT')){exit();} // No direct access
if(!defined('JSTORE')){exit();} // No direct access

$title = 'My Products';
// get Current parent Page
if($_REQUEST['id']!=''){
	$parent = get_category($_REQUEST['id'],array('title','parent'));
	$title = $parent->title.'\'s Categories/Products';
}

?>
<form id="item-viewer" method="post" action="<?php echo BACKEND_DIRECTORY;?>/components.php"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">Product</div>
                    <div class="content">
	                    <p style="margin-bottom:5px;"><input class="button" type="button" value="New <?php echo ($_REQUEST['id'] > 0 ? 'Sub-' : '')?>Category" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=item&opt=category-edit<?php echo ($_REQUEST['id'] > 0 ? '&parent='.$_REQUEST['id'] : '')?>';" /></p>
	                    <p><input class="button" type="button" value="New Product" onclick="window.location='<?php echo BACKEND_DIRECTORY;?>/components.php?comp=<?php echo JSTORE_ID;?>&mod=item&opt=edit<?php echo ($_REQUEST['id'] > 0 ? '&parent='.$_REQUEST['id'] : '')?>';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
				<?php load_documentations(); ?>
            	<h2><?php echo $title;?></h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="table-lists" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date</th>
                        <th class="col4">Sort</th>
                    </tr>
					<?php
					$filter = '';
					$filter2 = '';
					if($_REQUEST['id'] > 0){
						$filter = "pc.cat_parent='".$_REQUEST['id']."'";
						$filter2 = "pi.item_category='".$_REQUEST['id']."'";
					}else{
						$filter = "pc.cat_parent='0'";
						$filter2 = "pi.item_category='0'";
					}					
					$url = BACKEND_DIRECTORY.'/components.php?comp='.JSTORE_ID.'&mod=item'.($_REQUEST['id'] > 0 ? '&id='.$_REQUEST['id'] : '');					

                    $numrows1 = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_product_categories AS pc".($filter!=''? ' WHERE '.$filter : ''))->_count;
					$numrows2 = jcms_db_get_row("SELECT COUNT(*) AS _count FROM #_store_product_info AS pi".($filter2!=''? ' WHERE '.$filter2 : ''))->_count;
					$numrows = ($numrows1 + $numrows2);
                    $sql = "(SELECT 'category' AS this_type,'1' AS list_order,pc.ID,pc.cat_title AS title,pc.cat_status AS status,pc.cat_sort_order AS menu_order,DATE_FORMAT(pc.cat_date_added,'%Y/%m/%d') AS date_added FROM #_store_product_categories AS pc".($filter!=''? ' WHERE '.$filter : '').") UNION 
					(SELECT 'product' AS this_type,'2' AS list_order,pi.ID,pi.item_name AS title,pi.item_status AS status,pi.item_sort_order AS menu_order,DATE_FORMAT(pi.item_date_created,'%Y/%m/%d') AS date_added FROM #_store_product_info AS pi".($filter2!=''?  ' WHERE '.$filter2 : '').") ORDER BY menu_order ASC";
					$categories = jcms_db_pagination($sql,$numrows,15,$url);
                    $category_count = count($categories->results);
                    ?>
                    <?php if($category_count > 0):?>
                    	<?php
						$cat = '';
						$prod = '';
						$oe1 = 'odd';
						$oe2 = 'odd';
                        ?>
						<?php for($i=0;$i < $category_count;$i++): $category = $categories->results[$i];?>
							<?php
                            if($category->this_type == 'category'){
								$cat.= '
								<tr class="list '.$category->status.' '.$oe1.'">
									<td class="col1"><input type="checkbox" name="chk[category][]" class="chk" value="'.$category->ID.'" /></td>
									<td class="col2">
										<span class="title">
											<a href="'.COMPONENTS_URL.'&mod=item&id='.$category->ID.'">'.stripslashes($category->title).'</a> ('.count_child_category($category->ID).' / '.count_child_products($category->ID).')
										</span>
										<div class="control">
											<a href="'.COMPONENTS_URL.'&mod=item&opt=category-edit&id='.$category->ID.'">Edit</a> | 
											<a href="'.COMPONENTS_URL.'&mod=item&action=delete&chk[category][]='.$category->ID.'">Delete</a>
										 </div>
									</td>
									<td class="col3">'.$category->date_added.'<div class="status active">'.($category->status=='active' ? 'Active' : 'In-Active').'</div>
									</td>
									<td class="col4">'.$category->menu_order.'</td>
								</tr>'."\n";
								$oe1 = ($oe1 == 'odd' ? 'even' : 'odd');
                            }
							
                            if($category->this_type == 'product'){
								$prod.= '
								<tr class="list '.$category->status.' '.$oe2.'">
									<td class="col1"><input type="checkbox" name="chk[product][]" class="chk" value="'.$category->ID.'" /></td>
									<td class="col2">
										<span class="title"><a href="'.COMPONENTS_URL.'&mod=item&opt=edit&id='.$category->ID.'">'.stripslashes($category->title).'</a></span>
										<div class="control">
											<a href="'.COMPONENTS_URL.'&mod=item&opt=edit&id='.$category->ID.'">Edit</a> | 
											<a href="'.COMPONENTS_URL.'&mod=item&action=delete&chk[product][]='.$category->ID.'">Delete</a>
										 </div>
									</td>
									<td class="col3">'.$category->date_added.'<div class="status active">'.($category->status=='active' ? 'Active' : 'In-Active').'</div>
									</td>
									<td class="col4">'.$category->menu_order.'</td>
								</tr>'."\n";
								$oe2 = ($oe2 == 'odd' ? 'even' : 'odd');
                            }
                            ?>
                        <?php endfor;?>
                        <tr class="section">
                            <td colspan="4">Categories</td>
                        </tr>
                        <?php if($cat!=''):?>
                       		<?php echo $cat; ?>
                        <?php else:?>
                            <tr class="notify">
                                <?php if($_REQUEST['id'] > 0):?>
                                <td colspan="4">No category under <strong><?php echo stripslashes($parent->title);?></strong></td>
                                <?php else:?>
                                <td colspan="4">No categories</td>
                                <?php endif;?>  
                            </tr>
                        <?php endif?>                        
                        <tr class="section">
                            <td colspan="4">Products</td>
                        </tr>
                        <?php if($prod!=''):?>
	                        <?php echo $prod; ?>
                        <?php else:?>
                            <tr class="notify">
                                <?php if($_REQUEST['id'] > 0):?>
                                <td colspan="4">No products under <strong><?php echo stripslashes($parent->title);?></strong></td>
                                <?php else:?>
                                <td colspan="4">No products</td>
                                <?php endif;?>  
                            </tr>
                        <?php endif?>                        
                    <?php else:?>
                        <tr class="section">
                            <td colspan="4">Categories</td>
                        </tr>
                        <tr class="notify">
                            <?php if($_REQUEST['id'] > 0):?>
                            <td colspan="4">No category under <strong><?php echo stripslashes($parent->title);?></strong></td>
                            <?php else:?>
                            <td colspan="4">No categories</td>
                            <?php endif;?>  
                        </tr>                  
                        <tr class="section">
                            <td colspan="4">Products</td>
                        </tr>
                        <tr class="notify">
                            <?php if($_REQUEST['id'] > 0):?>
                            <td colspan="4">No products under <strong><?php echo stripslashes($parent->title);?></strong></td>
                            <?php else:?>
                            <td colspan="4">No products</td>
                            <?php endif;?>  
                        </tr>
                    <?php endif;?>
                    <tr>
                        <th class="col1"><input type="checkbox" class="check_all" /></th>
                        <th class="col2">Name</th>
                        <th class="col3">Date</th>
                        <th class="col4">Sort</th>
                    </tr>
                </table>
                <div style="margin-top:5px;" class="left"><?php if($_REQUEST['id']!=''): ?>
                <?php
				$back = BACKEND_DIRECTORY.'/components.php?comp='.JSTORE_ID.'&mod=item';
				if($parent->parent > 0){
					$back = $back.'&id='.$parent->parent;
				}
                ?>                
                <input type="button" value="Back" onclick="window.location='<?php echo $back;?>';" />
                <?php endif; ?>&nbsp;<input type="submit" value="Delete" /></div>
                <div id="pagination" class="right" style="margin-top:5px;">
					<?php echo $categories->pages; ?>
                </div>
                <div class="clear"></div>

            </td>
        </tr>
    </table>
    <input type="hidden" name="comp" value="<?php echo JSTORE_ID;?>" />
    <input type="hidden" name="mod" value="item" />
    <input type="hidden" name="action" value="delete" />
</form>