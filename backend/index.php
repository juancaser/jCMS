<?php
include('backend-load.php'); // Backend bootstrap loader
global $backend_page,$config,$ga;
$backend_page = (object) array('title'=>'Dashboard');
the_backend_header(); ?>
<div id="dashboard">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
    	<tr>
        	<td width="40%">
            	<?php if(get_option('ga') == 'yes' && get_option('ga_email')!='' && get_option('ga_password')!='' && get_option('ga_report_id')!=''):?>
                <script type="text/javascript">
					$(document).ready(function(){
						$.ajax({
							type:'POST',
							url:'<?php get_siteinfo('url');?>/backend/ajax.php',
							data:'action=get_ga&report_id=<?php echo get_option('ga_report_id');?>',
							success: function(msg){
								var ga = $.parseJSON(msg);
								$('#total_no_visitor').html(ga.visitors);
								$('#total_no_visitor_first_time').html(ga.newVisits);
								$('#visitor_percentage').html(ga.percentNewVisits);
								
								$('#bounce_rate').html(ga.entranceBounceRate + ' / ' + ga.visitBounceRate);
								$('#total_pageviews').html(ga.pageviews);
								$('#avgtimeonpage').html(ga.avgTimeOnPage);
								$('#site_exits_percentage').html(ga.exitRate);
								$('#date_last_update').html(ga.datelastupdate);
							}
						});						
					});
                </script>
                <div class="box" style="margin-right:20px;">
                	<div class="title">Site Analytics</div>
                	<div class="content" style="padding:0;">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td colspan="2" style="padding:5px 10px;background-color:#F0F0F0;border-bottom :1px solid #E5E5E5;">
                                	<span style="font-size:13px;">Visitor</span>
								</td>
							</tr>
                            <tr>
                                <td style="padding:5px 10px 0 10px;font-size:12px;width:50%;">Total number of visitors</td>
                                <td style="padding:5px 10px 0 10px;font-size:12px;width:50%;">&rsaquo; <span id="total_no_visitor" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:0 10px;font-size:12px;">Total number of first-time visitors</td>
                                <td style="padding:0 10px;font-size:12px;">&rsaquo; <span id="total_no_visitor_first_time" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:0 10px 5px 10px;font-size:12px;">Visitor percentage (%)</td>
                                <td style="padding:0 10px 5px 10px;font-size:12px;">&rsaquo; <span id="visitor_percentage" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            
                            <tr>
                                <td colspan="2" style="padding:5px 10px;background-color:#F0F0F0;border-bottom :1px solid #E5E5E5;border-top :1px solid #E5E5E5;">
                                	<span style="font-size:13px;">Page Tracking</span>
								</td>
							</tr>
                            <tr>
                                <td style="padding:5px 10px 0 10px;font-size:12px;">Bounce rate (%)</td>
                                <td style="padding:5px 10px 0 10px;font-size:12px;">&rsaquo; <span id="bounce_rate" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:0 10px;font-size:12px;">The total number of pageviews</td>
                                <td style="padding:0 10px;font-size:12px;">&rsaquo; <span id="total_pageviews" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:0 10px;font-size:12px;">Average time spent on a page</td>
                                <td style="padding:0 10px;font-size:12px;">&rsaquo; <span id="avgtimeonpage" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:0 10px;font-size:12px;">Site exits percentage (%)</td>
                                <td style="padding:0 10px;font-size:12px;">&rsaquo; <span id="site_exits_percentage" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:20px 10px 0 10px;font-size:12px;">Date last updated</td>
                                <td style="padding:20px 10px 0 10px;font-size:12px;">&rsaquo; <span id="date_last_update" style="color:#3A8000;font-weight:bold;"><img src="<?php get_siteinfo('url');?>/backend/images/loader-1.gif" /></span></td>
							</tr>
                            <tr>
                                <td style="padding:5px 10px;text-align:right;" colspan="2"><a href="http://www.google.com/analytics/" target="_blank"><img src="<?php get_siteinfo('url');?>/core/powered_ga.gif" /></a></td>
							</tr>
                        </table>
					</div>
                </div>
                <?php endif;?>
                <div class="box" style="margin-right:20px;">
                	<div class="title">Server Information</div>
                	<div class="content">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%">
                            <tr>
                                <td width="30%"><span style="font-weight:bold;">Server Time</span></td>
                                <td width="70%">&rsaquo; <?php echo date('F j, Y h:i:s A');?></td>
							</tr>
                            <tr>
                                <td><span style="font-weight:bold;">IP Address</span></td>
                                <td>&rsaquo; <?php echo $_SERVER['REMOTE_ADDR'];?></td>
							</tr>
                            <tr>
                                <td><span style="font-weight:bold;">PHP</span></td>
                                <td>&rsaquo; PHP <?php echo PHP_VERSION;?></td>
							</tr>
                            <tr>
                                <td><span style="font-weight:bold;">Database</span></td>
                                <?php $db_info = jcms_db_info();?>
                                <td>&rsaquo; <?php echo $db_info->db.' v',$db_info->version;?></td>
							</tr>
                            <tr>
                                <td><span style="font-weight:bold;">Status</span></td>
                                <td>&rsaquo; <?php echo ($config->maintenance ? '<span style="color:#FF0000;font-weight:bold;">Maintenance</span>' : '<span style="color:#3A8000;font-weight:bold;">Online</span>');?></td>
							</tr>
                        </table>
					</div>
                </div>
                <?php do_action('dashboard_box_left');?>
            </td>
        	<td width="60%">
                <h3>What do you want to do?</h3>
                <div class="quicklinks">
                	<table cellpadding="0" cellspacing="0" border="0" width="100%">
                    	<tr>
                        	<td class="col1 pages">
                                <h4><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php">Post and Pages</a></h4>
                                <ul>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php"><?php _l('txt_ql_view_page','View all pages');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=post"><?php _l('txt_ql_view_post','View all post');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=draft"><?php _l('txt_ql_view_draft','View saved draft');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=edit"><?php _l('txt_ql_new_post','Create new post/page');?></a></li>
                                    <li><span><?php _l('txt_trash','Trash');?></span>: <a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?type=page"><?php _l('txt_view','View');?></a> | <a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?type=page&action=empty"><?php _l('txt_ql_empty','Empty');?></a></li>
                                </ul>
                            </td>
                            <!--td class="col2 attachment">
                                <h4><a href="<?php echo BACKEND_DIRECTORY;?>/media.php">Media Gallery</a></h4>
                                <ul>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/media.php"><?php _l('txt_ql_view_media','View media gallery');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/media.php?opt=upload"><?php _l('txt_ql_upload_media','Upload new media');?></a></li>
                                    <li><span><?php _l('txt_trash','Trash');?></span>: <a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?opt=media"><?php _l('txt_view','View');?></a> | <a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?opt=media&action=empty"><?php _l('txt_ql_empty','Empty');?></a></li>
                                </ul>
                            </td-->
                        	<td class="col1 settings">
                                <h4><a href="<?php echo BACKEND_DIRECTORY;?>/system.php"><?php _l('txt_ql_system_user','System and User Account');?></a></h4>
                                <ul>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/system.php?opt=configuration"><?php _l('txt_ql_system_config','Change system configuration');?></a></li>
                                    <li><span><?php _l('txt_ql_system_user','Users');?></span>: <a href="<?php echo BACKEND_DIRECTORY;?>/users.php?opt=users"><?php _l('txt_view','View');?></a> | <a href="<?php echo BACKEND_DIRECTORY;?>/users.php?opt=add-user"><?php _l('txt_add','Add');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/system.php?opt=sysinfo"><?php _l('txt_ql_system_info','View system information');?></a></li>
                                </ul>
                            </td>

                        </tr>
                    	<tr>
                            <!--td class="col2 help_support">
                                <h4><a href="<?php echo BACKEND_DIRECTORY;?>/help.php"><?php _l('txt_ql_help_support','Help and Support');?></a></h4>
                                <ul>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?opt=tutorial">Tutorials</a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?opt=support">Contact Support</a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?opt=about">About</a></li>
                                </ul>
                            </td-->
	                        <?php $count = trashed_items();?>
                        	<td class="col1 trash<?php echo ($count > 0 ? '-full' : '');?>">                            	
                                <h4><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php">Trash</a><?php echo ($count > 0 ? '<span style="font-size:0.8em;color:#333333;"> ('.$count.' items)</span>' : '');?></h4>
                                <ul>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?opt=page"><?php _l('txt_ql_trash_page','Trashed Post/Page');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?opt=media"><?php _l('txt_ql_trash_media','Trashed Media');?></a></li>
                                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?opt=all&action=empty"><?php _l('txt_ql_trash_empty_all','Empty Bin');?></a></li>
                                </ul>
                            </td>
                            <td></td>
                        </tr>                        
                    </table>
                    <?php //do_action('dashboard_quicklinks');?>
                </div>
            </td>
        </tr>
    </table>
</div>
<?php the_backend_footer(); ?>