<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
        </div>
        <div class="clear"></div>
        <div id="footer">
        	<div class="payment"></div>
        	<div class="links">
            	<ul>
                	<li class="first"><a href="<?php get_siteinfo('url');?>">Home</a></li>
                	<li><a href="<?php the_permalink('contact-us');?>">Contact Us</a></li>
                    <?php if(is_user_logged()):?>
                	<li><a href="<?php store_info('user');?>">My Account</a></li>
                    <?php endif;?>
                	<li><a href="<?php the_permalink('privacy-policy');?>">Privacy Policy</a></li>
                </ul>
                <div class="clear"></div>
                <p>Copyright &copy; <?php echo date('Y');?>. All Rights Reserved.</p>
                <p>Powered by jCMS 2.2.<!--[if lt IE 7]> This site is best viewed on <a href="http://www.mozilla.com/en-US/firefox/" targe="_blank">Firefox</a><![endif]--></p>
                
            </div>
			<div class="clear"></div>
        </div>
    </div>    
    <?php the_foot();?>
</body>
</html>