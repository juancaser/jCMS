<?php
if(!defined('JCMS')){exit();} // No direct access
global $config;
$userinfo = $_SESSION['__USER']['info'];
?>
<form method="post" action="<?php echo SYSTEM_URL;?>"> 
    <table class="viewer" cellpadding="0" cellspacing="0" border="0">
    	<tr>
        	<td class="sidebar">
                <div class="box">
                    <div class="title">System</div>
                    <div class="content">
	                    <p style="margin-bottom:5px;"><input class="button" type="submit" value="Save Changes" /></p>
                        <p style="margin-bottom:5px;"><input class="button" type="button" value="Back" onclick="window.location='<?php echo SYSTEM_URL;?>';" /></p>
                    </div>
                </div>                
            </td>
        	<td class="content">
            	<?php load_documentations(); ?>
            	<h2><?php echo $title;?></h2>
                <?php _d($action_message->message,'<div class="messagebox '.$action_message->class.'">','</div>');?>
                <table class="system-settings" cellpadding="0" cellspacing="0" border="0">
                	<tr>
                    	<td class="col1"><label for="site_name">Site Name</label></td>
                        <td class="col2"><input type="text" name="site_name" id="site_name" value="<?php echo get_option('site_name');?>" /></td>
                    </tr>                    
                	<tr>
                    	<td class="col1"><label for="site_description">Site Description</label></td>
                        <td class="col2"><textarea name="site_description" id="site_description"><?php echo get_option('site_description');?></textarea></td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="site_keywords">Keywords</label></td>
                        <td class="col2"><textarea name="site_keywords" id="site_keywords"><?php echo get_option('site_keywords');?></textarea>
                        <div class="info" style="width:300px;margin-top:5px;"><strong>Note:</strong> Type keywords separated by a comma.</div>
                        </td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="site_url">Site Address (URL)</label></td>
                        <td class="col2">
                        	<input type="text" name="site_url" id="site_url" value="<?php echo get_option('site_url');?>" />
                            <div class="info" style="width:300px;margin-top:5px;"><strong>Note:</strong> Please make sure in changing site address to avoid messing up the site.</div>
                        </td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="admin_email">E-mail Address</label></td>
                        <td class="col2">
                        	<input type="text" name="admin_email" id="admin_email" value="<?php echo get_option('admin_email');?>" />
                            <div class="info" style="width:300px;margin-top:5px;">This will be used to manage and receive site notification.</div>
                        </td>
                    </tr>
                	<!--tr>
                    	<td class="col1"><label>Optimization</label></td>
                        <td class="col2">
                        	<label><input type="checkbox" name="site_caching" value="yes"<?php echo (get_option('site_caching') == 'yes' ? ' checked="checked"' : '');?> /> Site Caching</label>
                            <div class="info" style="width:300px;margin-top:5px;">This will speed-up page loading, but it will take up server space. Cache page will be updated after the 72 hours.</div>
                        </td>
                    </tr-->
					<tr><td colspan="2"><h3>Date and Time</h3></td></tr>
                	<?php if(function_exists('date_default_timezone_set')):?>
                	<tr>
                    	<td class="col1"><label for="time_zone">Time Zone</label></td>
                        <td class="col2">
                        	<select name="time_zone" id="time_zone">
							<?php
							$tz = timezone_lists();
							//for($i=0; $i < count($tz->continent); $i++){
							foreach($tz->continent as $key => $value){
								$cities = $tz->cities[$key];
	                            echo '<optgroup label="'.$value.'">'."\n";
								for($i=0; $i < count($cities); $i++){
									$city = $cities[$i];
									echo  '<option value="'.$city->ID.'"'.($city->ID == date_default_timezone_get() ? ' selected="selected"':'').'>'.$city->name.'</option>'."\n";
								}
								echo '</optgroup>'."\n";
                            }
							?>
                            </select><span>&nbsp;<code><?php echo date_default_timezone_get(); ?> (<?php echo date('Y-m-d h:i:s');?>)</code></span>
                        </td>
                    </tr>
                	<?php endif;?>
                	<tr>
                    	<td class="col1"><label for="date_format">Date Format</label></td>
                        <td class="col2">
                        	<!--input type="text" name="date_format" id="date_format" value="" /-->
                            <p><label><input type="radio" checked="checked" value="F j, Y" name="date_format"<?php echo (get_option('date_format') == 'F j, Y' ? ' selected="selected"' : '');?>> <?php echo date('F j, Y');?></label></p>
                            <p><label><input type="radio" value="Y/m/d" name="date_format"<?php echo (get_option('date_format') == 'Y/m/d' ? ' selected="selected"' : '');?>> <?php echo date('Y/m/d');?></label></p>
                            <p><label><input type="radio" value="d/m/Y" name="date_format"<?php echo (get_option('date_format') == 'd/m/Y' ? ' selected="selected"' : '');?>> <?php echo date('d/m/Y');?></label></p>
                            <p><label style="display:inline;"><input type="radio" value="custom" name="date_format">Custom: </label><input type="text" name="custom_date_format" style="width:60px;text-align:center;" value="<?php echo'F j, Y';?>" />&nbsp;&nbsp;<em>e.g.</em> <span style="text-decoration:underline;"><?php echo date('F j, Y');?></span></p>
                        </td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="time_format">Time Format</label></td>
                        <td class="col2">
                            <p><label><input type="radio" checked="checked" value="g:i a" name="time_format"<?php echo (get_option('time_format') == 'g:i a' ? ' selected="selected"' : '');?>> <?php echo date('g:i a');?></label></p>
                            <p><label><input type="radio" value="g:i A" name="time_format"<?php echo (get_option('time_format') == 'g:i A' ? ' selected="selected"' : '');?>> <?php echo date('g:i A');?></label></p>
                            <p><label><input type="radio" value="g:i" name="time_format"<?php echo (get_option('time_format') == 'g:i' ? ' selected="selected"' : '');?>> <?php echo date('g:i');?></label></p>
                            <p><label style="display:inline;"><input type="radio" value="custom" name="time_format">Custom: </label><input type="text" name="custom_time_format" style="width:60px;text-align:center;" value="<?php echo 'g:i A';?>" />&nbsp;&nbsp;<em>e.g.</em> <span style="text-decoration:underline;"><?php echo date('g:i A');?></span></p>
                        </td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="weeks_starts_on">Weeks Starts On</label></td>
                        <td class="col2">
                        	<select name="weeks_starts_on" id="weeks_starts_on" style="width:120px;">
                            	<option value="07"<?php echo (get_option('weeks_starts_on') == '07' ? ' selected="selected"' : '');?>>Sunday</option>
                            	<option value="01"<?php echo (get_option('weeks_starts_on') == '01' ? ' selected="selected"' : '');?>>Monday</option>
                            	<option value="02"<?php echo (get_option('weeks_starts_on') == '02' ? ' selected="selected"' : '');?>>Tuesday</option>
                            	<option value="03"<?php echo (get_option('weeks_starts_on') == '03' ? ' selected="selected"' : '');?>>Wednesday</option>
                            	<option value="04"<?php echo (get_option('weeks_starts_on') == '04' ? ' selected="selected"' : '');?>>Thursday</option>
                            	<option value="05"<?php echo (get_option('weeks_starts_on') == '05' ? ' selected="selected"' : '');?>>Friday</option>
                            	<option value="06"<?php echo (get_option('weeks_starts_on') == '06' ? ' selected="selected"' : '');?>>Saturday</option>
                            </select>
                        </td>
                    </tr>
					<tr><td colspan="2"><h3><label style="font-size:16px;"><input type="checkbox" name="recaptcha" value="yes"<?php echo (get_option('recaptcha') == 'yes' ? ' checked="checked"' : '');?> /> ReCAPTCHA</label></h3></td></tr>
					<?php if(get_option('recaptcha') == 'yes'):?> 
						<?php if(get_option('recaptcha_domain') == '' && get_option('recaptcha_public_key') == '' && get_option('recaptcha_private_key') == ''):?>
                        <tr><td colspan="2"><p>Get ReCAPTCHA key from <code>http://www.google.com/recaptcha</code>, or click <a href="http://www.google.com/recaptcha" target="_blank">here</a>.</p></td></tr>
                        <?php endif;?>
                        <tr>
                            <td class="col1"><label for="recaptcha_domain">Domain</label></td>
                            <td class="col2"><input type="text" name="recaptcha_domain" id="recaptcha_domain" value="<?php echo get_option('recaptcha_domain');?>" /></td>
                        </tr>
                        <tr>
                            <td class="col1"><label for="recaptcha_public_key">Public Key</label></td>
                            <td class="col2"><input type="text" name="recaptcha_public_key" id="recaptcha_public_key" value="<?php echo get_option('recaptcha_public_key');?>" /></td>
                        </tr>
                        <tr>
                            <td class="col1"><label for="recaptcha_private_key">Private Key</label></td>
                            <td class="col2"><input type="text" name="recaptcha_private_key" id="recaptcha_private_key" value="<?php echo get_option('recaptcha_private_key');?>" /></td>
                        </tr>
                        <tr>
                            <td class="col1">Options</td>
                            <td class="col2">
                                <?php $recaptcha_option = (object) unserialize(get_option('recaptcha_option')); ?>
                                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                    <tr>
                                        <td width="20%"><label for="recaptcha_theme">Theme</label></td>
                                        <td width="80%">
                                            <p><select id="recaptcha_theme" name="recaptcha_option[theme]" style="width:auto;">
                                                <option value=""<?php echo ($recaptcha_option->theme == '' ? ' selected="selected"': '');?>>Red</option>
                                                <option value="white"<?php echo ($recaptcha_option->theme == 'white' ? ' selected="selected"': '');?>>White</option>
                                                <option value="blackglass"<?php echo ($recaptcha_option->theme == 'blackglass' ? ' selected="selected"': '');?>>Blackglass</option>
                                                <option value="clean"<?php echo ($recaptcha_option->theme == 'clean' ? ' selected="selected"': '');?>>Clean</option>
                                            </select></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="recaptcha_lang">Language</label></td>
                                        <td>
                                            <select id="recaptcha_lang" name="recaptcha_option[lang]" style="width:auto;">
                                                <option value=""<?php echo ($recaptcha_option->lang == '' ? ' selected="selected"': '');?>>English</option>
                                                <option value="nl"<?php echo ($recaptcha_option->lang == 'nl' ? ' selected="selected"': '');?>>Dutch</option>
                                                <option value="fr"<?php echo ($recaptcha_option->lang == 'fr' ? ' selected="selected"': '');?>>French</option>
                                                <option value="de"<?php echo ($recaptcha_option->lang == 'de' ? ' selected="selected"': '');?>>German</option>
                                                <option value="pt"<?php echo ($recaptcha_option->lang == 'pt' ? ' selected="selected"': '');?>>Portuguese</option>
                                                <option value="ru"<?php echo ($recaptcha_option->lang == 'ru' ? ' selected="selected"': '');?>>Russian</option>
                                                <option value="es"<?php echo ($recaptcha_option->lang == 'es' ? ' selected="selected"': '');?>>Spanish</option>
                                                <option value="tr"<?php echo ($recaptcha_option->lang == 'tr' ? ' selected="selected"': '');?>>Turkish</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="recaptcha_tabindex">Tab Index</label></td>
                                        <td><input type="text" id="recaptcha_tabindex" name="recaptcha_option[tabindex]" style="width:20px;text-align:center;" value="<?php echo $recaptcha_option->tabindex;?>" /></td>
                                    </tr>
                                </table>
                                <p>For more information about the settings and customization goto <code>http://code.google.com/apis/recaptcha/docs/customization.html</code>, or just click <a href="http://code.google.com/apis/recaptcha/docs/customization.html" target="_blank">here</a>.</p>
                            </td>
                        </tr>
					<?php else:?> 
						<tr><td colspan="2"><div class="info">reCAPTCHA is currently disabled.</div></td></tr>
                        <input type="hidden" name="recaptcha_domain" value="<?php echo get_option('recaptcha_domain');?>" />
                        <input type="hidden" name="recaptcha_public_key" value="<?php echo get_option('recaptcha_public_key');?>" />
                        <input type="hidden" name="recaptcha_private_key" value="<?php echo get_option('recaptcha_private_key');?>" />
                        <?php $recaptcha_option = (object) unserialize(get_option('recaptcha_option')); ?>
                        <input type="hidden" name="recaptcha_option[theme]" value="<?php echo $recaptcha_option->theme;?>" />
                        <input type="hidden" name="recaptcha_option[lang]" value="<?php echo $recaptcha_option->lang;?>" />
                        <input type="hidden" name="recaptcha_option[tabindex]" value="<?php echo $recaptcha_option->tabindex;?>" />
					<?php endif;?> 
                    <tr>
                        <td class="col1" style="background:url('<?php get_siteinfo('url');?>/core/powered_recaptcha.gif') no-repeat top left;height:55px;">
                        <td class="col2"></td>
                    </tr>
                    
					<tr><td colspan="2"><h3><label style="font-size:16px;"><input type="checkbox" name="ga" value="yes"<?php echo (get_option('ga') == 'yes' ? ' checked="checked"' : '');?> /> Google Analytics</label></h3></td></tr>
                    <?php if(get_option('ga') == 'yes'):?>                    
                	<tr>
                    	<td class="col1"><label for="ga_email">Email</label></td>
                        <td class="col2"><input type="text" name="ga_email" id="ga_email" value="<?php echo get_option('ga_email');?>" /></td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="ga_password">Password</label></td>
                        <td class="col2"><input type="password" name="ga_password" id="ga_password" value="<?php echo get_option('ga_password');?>" /></td>
                    </tr>
                    <tr><td colspan="2"><p>Email and Password is required for fetching analytics data.</p></td></tr>
                	<tr>
                    	<td class="col1"><label for="ga_tracker">Tracker Code</label></td>
                        <td class="col2">
                        	<input type="text" name="ga_tracker" id="ga_tracker" value="<?php echo get_option('ga_tracker');?>" />
                            <?php if(get_option('ga_tracker')!=''):?>
                            <div class="info" style="width:300px;margin-top:5px;">Get Google Analytics account from <code>http://www.google.com/analytics</code>, or click <a href="http://www.google.com/analytics" target="_blank">here</a>.</div>
                            <?php endif;?>
                        </td>
                    </tr>
                	<tr>
                    	<td class="col1"><label for="ga_report_id">Report ID</label></td>
                        <td class="col2"><input type="text" name="ga_report_id" id="ga_report_id" value="<?php echo get_option('ga_report_id');?>" /></td>
                    </tr>	
                    <tr><td colspan="2"><p>How to get your GA report id &rarr; <code>https://www.google.com/analytics/reporting/?reset=1&amp;id=&lt;<strong><em>REPORT ID</em></strong>&gt;&amp;pdr=12345678-12345678</code>.</p></td></tr>
                    <?php else:?>
                    
						<tr><td colspan="2"><div class="info">Google Analytics is currently disabled.</div></td></tr>
                        <input type="hidden" name="ga_email" value="<?php echo get_option('ga_email');?>" />
                        <input type="hidden" name="ga_password" value="<?php echo get_option('ga_password');?>" />
                        <input type="hidden" name="ga_tracker" value="<?php echo get_option('ga_tracker');?>" />
                        <input type="hidden" name="ga_report_id" value="<?php echo get_option('ga_report_id');?>" />
                    <?php endif;?>
                    <tr>
                        <td class="col1" style="background:url('<?php get_siteinfo('url');?>/core/powered_ga.gif') no-repeat top left;height:37px;">
                        <td class="col2"></td>
                    </tr>
				</table>
			</td>
		</tr>
    </table>
    <input type="hidden" name="action" value="save-settings" />
</form>