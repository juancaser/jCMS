<?php
if(!defined('IMPARENT')){exit();} // No direct access

get_header();
?>
<?php
$random_bg = array(
				'1' => 'bike-girl2.gif',
				'2' => 'bike-girl2.gif'
			);	
$img = $random_bg[rand(1,count($random_bg))];
?>
<style type="text/css">
#login-bg{
	background:url('<?php get_siteinfo('template_directory');?>/images/<?php echo $img;?>') no-repeat 0 100%;
	height:420px;
	width:420px;
}
</style>
<?php breadcrumb('Home','<span class="sep"></span>');?>
<div id="page" class="inner-wrapper">
	<div id="login">
        <form class="validate" method="post" action="<?php store_info('login');?>">
            <div id="login-bg" class="left">
                <h1>Welcome to <?php get_siteinfo('name');?> &#8212; Login</h1>
            </div>
            <div class="login right">                
                <div class="container">
                    <h2>Login to your account</h2>
                    <p style="padding-bottom:10px;">Login now to buy, view promos and prices, or to manage your account.</p>
                    <?php _d($login_message,'<p style="padding-bottom:20px;color:red;">','</p>');?>
                    <p><label for="user_name">Email Address/User name</label><input class="field text_field" type="text" name="user_name" id="user_name" /></p>
                    <p><label for="user_password">Password</label><input class="field text_field" type="password" name="user_password" id="user_password" /></p>
                    <div class="info"><a href="<?php store_info(array('key'=>'recover','type'=>'password'),true);?>">I forgot my password</a></div>
                    <div class="left" style="padding-right:5px;"><input type="checkbox" name="keep_me_signin" id="keep_me_signin" value="yes" /></div>
                    <div class="left" style="width:230px;"><label style="display:inline;" for="keep_me_signin"><strong>Keep me login in.</strong> Don't check this box if you're at a public or shared computer.</label></div>
                    <div class="clear" style="padding-bottom:10px;"></div>
                    <p><input type="submit" class="button" value="Login" /></p>                    
                    <div style="height:20px;"></div>
                    <p style="font-size:15px;font-weight:bold;padding-bottom:20px;">Not yet a member? Register <a href="<?php store_info('register');?>">here</a></p>
                </div>
            </div>
            <div class="clear"></div>
            <?php if($_REQUEST['redirect']!=''):?>
            	<input type="hidden" name="redirect" value="<?php echo $_REQUEST['redirect'];?>" />
            <?php endif;?>            
            <input type="hidden" name="action" value="user_auth" />
        </form>
	</div>
</div>
<?php get_footer(); ?>