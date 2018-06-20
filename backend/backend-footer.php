<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
		<?php backend_foot();?>
        <?php global $pathinfo;?>
        <?php if($pathinfo->filename!='login'):?>        
        <div id="footer">
        	<div style="border-top:1px dotted #E5E5E5;padding:5px 0;">
                <span class="left">Copyright &copy; <?php echo date('Y');?>. All Rights Reserved. <a href="<?php get_siteinfo('url');?>" target="_blank" title="<?php get_siteinfo('name');?>" alt="<?php get_siteinfo('name');?>"><?php get_siteinfo('name');?></a></span>
                <span class="right"><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?opt=support">Support</a> | Powered by jCMS Framework <?php echo VERSION;?>.</p>
                <div class="clear"></div>
            </div>
        </div>
    	<?php endif;?>
    </div>
</body>
</html>
<?php do_action('jcms_backend_close'); // Exiting ?>