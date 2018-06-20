<div id="settings">
	<?php if($_SESSION['USER_UPDATE']!=''):?>
		<div class="msgbox"><?php echo $_SESSION['USER_UPDATE'];?></div>
        <?php 
		if($_SESSION['USER_UPDATE_COUNT'] == 2){
			unset($_SESSION['USER_UPDATE']);
		}else{
			$_SESSION['USER_UPDATE_COUNT']++;
		}
		?>
    <?php endif;?>
	<form method="post" action="<?php store_info('user');?>" class="validate">
        <table cellpadding="0" cellspacing="0" border="0" style="margin-top:10px;">
            <tr>
                <td colspan="2"><span style="color:red;font-size:16px;">*</span> Required fields.</td>
            </tr>
            <tr>
                <td class="col1"><label for="first_name">First name<span style="color:red;font-size:16px;">*</span></label><input type="text" name="user[meta][first_name]" id="first_name" class="required" value="<?php echo $meta->first_name;?>" style="width:80%;" /></td>
                <td class="col2"><label for="last_name">Last name<span style="color:red;font-size:16px;">*</span></label><input type="text" name="user[meta][last_name]" id="last_name" class="required" value="<?php echo $meta->last_name;?>" style="width:80%;" /></td>
            </tr>
            <tr>
                <td class="col3" colspan="2"><label for="business_name">Business Name</label><input type="text" name="user[meta][business_name]" id="business_name" style="width:90%;" value="<?php echo $meta->business_name;?>" /></td>
            </tr>
            <tr>
                <td class="col3" colspan="2"><label for="street1">Address</label><input type="text" name="user[meta][street1]" id="street1" style="width:90%;" value="<?php echo $meta->street1;?>" /></td>
            </tr>
            <tr>
                <td class="col3" colspan="2"><input type="text" name="user[meta][street2]"style="width:90%;" value="<?php echo $meta->street2;?>" /></td>
            </tr>
            <tr>
                <td class="col3" colspan="2" style="padding-top:5px;">
                    <div class="left" style="padding-right:50px;"><label for="city">City</label><input type="text" name="user[meta][city]" id="city" style="width:150px;" value="<?php echo $meta->city;?>" /></div>
                    <div class="left" style="padding-right:50px;"><label for="state">State</label>
                        <select name="user[meta][state]" id="state" style="width:200px;">
                            <option value="">--State--</option>
                            <?php							
                            foreach(us_states::get() as $key => $value){
                                if($key !='non-US'){
                                    echo '<option value="'.$key.'"'.($meta->state == $key ? ' selected="selected"' : '').'>'.$value.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="left"><label for="zip">ZIP / Postal code</label><input type="text" name="user[meta][zip]" id="zip" style="width:100px;" value="<?php echo $meta->zip;?>" /></div>                    	
                    <div class="clear"></div>
                </td>
            </tr>
            <?php if(get_option('store_intl_ship') == 'yes'):?>
            <tr>
                <td class="col3" colspan="2"><label for="country">Country<span class="required">*</span></label>
                    <select name="user[meta][country]" id="city" style="width:350px;">
                        <option value="">--Country--</option>
                        <?php							
                        foreach(iso_3166::get() as $key => $value){
                            echo '<option value="'.$key.'"'.($meta->country == $key ? ' selected="selected"' : '').'>'.$value.'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php endif;?>
            <tr>
                <td class="col3" colspan="2">
                    <label>Date of birth<span style="color:red;font-size:16px;">*</span></label>
                    <div class="left" style="padding-right:10px;">
                        <select name="user[meta][dob_month]" style="width:100px;" class="required">
                            <option value="">--Month--</option>
                            <option value="01"<?php echo ($meta->dob_month == '01' ? ' selected="selected"' : '');?>>January</option>
                            <option value="02"<?php echo ($meta->dob_month == '02' ? ' selected="selected"' : '');?>>February</option>
                            <option value="03"<?php echo ($meta->dob_month == '03' ? ' selected="selected"' : '');?>>March</option>
                            <option value="04"<?php echo ($meta->dob_month == '04' ? ' selected="selected"' : '');?>>April</option>
                            <option value="05"<?php echo ($meta->dob_month == '05' ? ' selected="selected"' : '');?>>May</option>
                            <option value="06"<?php echo ($meta->dob_month == '06' ? ' selected="selected"' : '');?>>June</option>
                            <option value="07"<?php echo ($meta->dob_month == '07' ? ' selected="selected"' : '');?>>July</option>
                            <option value="08"<?php echo ($meta->dob_month == '08' ? ' selected="selected"' : '');?>>August</option>
                            <option value="09"<?php echo ($meta->dob_month == '09' ? ' selected="selected"' : '');?>>September</option>
                            <option value="10"<?php echo ($meta->dob_month == '10' ? ' selected="selected"' : '');?>>October</option>
                            <option value="11"<?php echo ($meta->dob_month == '11' ? ' selected="selected"' : '');?>>November</option>
                            <option value="12"<?php echo ($meta->dob_month == '12' ? ' selected="selected"' : '');?>>December</option>
                        </select>
                    </div>
                    <div class="left" style="padding-right:10px;">
                        <select  name="user[meta][dob_day]" style="width:100px;" class="required">
                            <option value="">--Day--</option>
                            <?php
                            for($i=1;$i <= 31;$i++){
                                $day = ($i < 10 ? '0'.$i : $i);
                                echo '<option value="'.$day.'"'.($meta->dob_day == $i ? ' selected="selected"' : '').'>'.$day.'</option>'."\n";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="left">
                        <select name="user[meta][dob_year]" style="width:100px;" class="required">
                            <option value="">--Year--</option>
                            <?php							
                            for($i= (date('Y') - 100);$i <= (date('Y') - 17);$i++){
                                echo '<option value="'.$i.'"'.($meta->dob_year == $i ? ' selected="selected"' : '').'>'.$i.'</option>'."\n";
                            }
                            ?>
                        </select> 
                    </div>                    	
                    <div class="clear info">
                        <p>You must be at least 18 years old to register.</p>
                    </div>                        
                </td>
            </tr>
    
            <tr>
                <td class="col3" colspan="2"><label for="primary_phone">Primary telephone number</label>
                    <div class="left" style="padding-right:10px;"><input type="text" name="user[meta][primary_phone]" id="primary_phone" style="width:150px;" value="<?php echo $meta->primary_phone;?>" /></div>
                    <div class="left"><span style="font-size:14px;">Ext.:</span> <input type="text" name="user[meta][phone_extension_no]" style="width:50px;" /></div>                    	
                    <div class="info clear">
                        <p>Example: 123-456-7890</p>
                        <p>Telephone is required in case there are questions about your account.</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="col3" colspan="2">
                    <label for="email_address">Email address<span style="color:red;font-size:16px;">*</span></label>
                    <input type="text" name="user[email_address]" id="email_address" class="required email ajax" style="width:200px;" value="<?php echo $userinfo->email_address;?>" />
                    <div class="info" id="email_address_ajax" style="display:block;color:red;"></div>
                </td>
            </tr>
            <tr>
                <td class="col3" colspan="2">
                    <label for="user_password">Update your password</label><input type="password" name="user[user_password]" id="user_password" maxlength="20" style="width:200px;" class="strength" />
                    <div class="info">
                        <p>User password is case-sensitive.</p>
                    </div>
                </td>
            </tr>
        </table>
    	<?php /*
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col1">
                    <label for="first_name">First name</label>
                    <input type="text" name="user[meta][first_name]" id="first_name" class="required" value="<?php echo $meta->first_name;?>" style="width:215px;" />
                </td>
                <td class="col2">
                    <label for="last_name">Last name</label>
                    <input type="text" name="user[meta][last_name]" id="last_name" class="required" value="<?php echo $meta->last_name;?>" style="width:215px;" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;">
                    <label for="street1">Address</label>
                    <input type="text" name="user[meta][street1]" id="street1" style="width:465px;display:block;margin-bottom:5px;" class="required" value="<?php echo $meta->street1;?>" />
                    <input type="text" name="user[meta][street2]" id="street2" style="width:465px;display:block;" value="<?php echo $meta->street2;?>" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;">
                    <div class="left" style="padding-right:15px;">
                        <label for="city">City</label>
                        <input type="text" name="user[meta][city]" id="city" style="width:150px;" class="required" value="<?php echo $meta->city;?>" />
                    </div>
                    <div class="left" style="padding-right:15px;">
                        <label for="state">State</label>
                        <select name="user[meta][state]" id="state" style="width:175px;">
                            <option value="">--State--</option>
                            <?php
                            foreach(us_states::get() as $key => $value){
                                if($key !='non-US'){
                                    echo '<option value="'.$key.'"'.($meta->state == $key ? ' selected="selected"' : '').'>'.$value.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="left">
                        <label for="zip">ZIP / Postal code</label>
                        <input type="text" name="user[meta][zip]" id="zip" style="width:100px;" class="required" value="<?php echo $meta->zip;?>" />
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;">                
                    <div class="left" style="padding-right:15px;">
                        <label for="primary_phone">Primary telephone number</label>
                        <input type="text" name="user[meta][primary_phone]" id="primary_phone" style="width:150px;" class="required" value="<?php echo $meta->primary_phone;?>" />
                    </div>
                    <div class="left">
                        <label for="extension_no">Ext.</label>
                        <input type="text" name="user[meta][extension_no]" id="extension_no" style="width:50px; value="<?php echo $meta->zip;?>"" />
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;">    
                    <label for="email_address">Email address</label>
                    <input type="text" name="user[email_address]" id="email_address" class="required email" value="<?php echo $userinfo->email_address;?>" style="width:200px;" />
                </td>
            </tr>
        </table>
        <h4>User ID and Password Settings</h4>
        <table cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col1">
                    <label for="display_name">Display Name</label>
                    <input type="text" name="user[display_name]" id="display_name" maxlength="50" style="width:150px;" class="required" value="<?php echo $userinfo->display_name;?>" />
                    <div class="info">This is what other user see.</div>
                </td>
                <td class="col2">
                    <label for="user_name">User ID</label>
                    <div class="input-box" style="width:150px;font-weight:bold;padding:3px;"><?php echo $userinfo->user_name;?></div>
                    <div class="info">User ID is read-only.</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;">
                    <label for="user_password">User password</label>
                    <input type="password" name="user[user_password]" id="user_password" maxlength="20" style="width:200px;" class="strength" />
                    <div class="info">User password is case-sensitive.</div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;">
                    <label>Date of birth</label>
                    <div class="left" style="padding-right:10px;">
                        <select name="user[meta][dob_month]" style="width:100px;" class="required">
                            <option value="">--Month--</option>
                            <option value="01"<?php echo ($meta->dob_month == '01' ? ' selected="selected"' : '');?>>January</option>
                            <option value="02"<?php echo ($meta->dob_month == '02' ? ' selected="selected"' : '');?>>February</option>
                            <option value="03"<?php echo ($meta->dob_month == '03' ? ' selected="selected"' : '');?>>March</option>
                            <option value="04"<?php echo ($meta->dob_month == '04' ? ' selected="selected"' : '');?>>April</option>
                            <option value="05"<?php echo ($meta->dob_month == '05' ? ' selected="selected"' : '');?>>May</option>
                            <option value="06"<?php echo ($meta->dob_month == '06' ? ' selected="selected"' : '');?>>June</option>
                            <option value="07"<?php echo ($meta->dob_month == '07' ? ' selected="selected"' : '');?>>July</option>
                            <option value="08"<?php echo ($meta->dob_month == '08' ? ' selected="selected"' : '');?>>August</option>
                            <option value="09"<?php echo ($meta->dob_month == '09' ? ' selected="selected"' : '');?>>September</option>
                            <option value="10"<?php echo ($meta->dob_month == '10' ? ' selected="selected"' : '');?>>October</option>
                            <option value="11"<?php echo ($meta->dob_month == '11' ? ' selected="selected"' : '');?>>November</option>
                            <option value="12"<?php echo ($meta->dob_month == '12' ? ' selected="selected"' : '');?>>December</option>
                        </select>
                    </div>
                    <div class="left" style="padding-right:10px;">
                        <select  name="user[meta][dob_day]" style="width:100px;" class="required">
                            <option value="">--Day--</option>
                            <?php
                            for($i=1;$i <= 31;$i++){
                                $day = ($i < 10 ? '0'.$i : $i);
                                echo '<option value="'.$day.'"'.($meta->dob_day == $day ? ' selected="selected"' : '').'>'.$day.'</option>'."\n";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="left">
                        <select name="user[meta][dob_year]" style="width:100px;" class="required">
                            <option value="">--Year--</option>
                            <?php							
                            for($i= (date('Y') - 100);$i <= (date('Y') - 17);$i++){
                                echo '<option value="'.$i.'"'.($meta->dob_year == $i ? ' selected="selected"' : '').'>'.$i.'</option>'."\n";
                            }
                            ?>
                        </select> 
                    </div>  
                </td>
            </tr>
            <tr>
                <td colspan="2" style="width:480px;padding-top:20px;">
					<input class="update" type="submit" value="Save Changes" />
                </td>
            </tr>
        </table>
		*/ ?>
        <input type="hidden" name="action" value="update" />
        <input type="submit" class="update"  value="Save Changes" />
    </form>    
</div>