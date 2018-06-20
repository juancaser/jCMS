<?php if(!defined('JCMS')){exit();} // No direct access ?>
<div class="left" style="width:20%;padding-top:20px;">
    <div class="box" style="margin-right:20px;">
        <div class="title">Information</div>
        <div class="content">
            <ul>
                <li><a href="<?php echo SYSTEM_URL;?>?mod=sysinfo">General</a></li>
                <li><a href="<?php echo SYSTEM_URL;?>?mod=sysinfo&type=modules">Modules</a></li>
                <li><a href="<?php echo SYSTEM_URL;?>?mod=sysinfo&type=environments">Environments</a></li>
                <li><a href="<?php echo SYSTEM_URL;?>?mod=sysinfo&type=variables">Variables</a></li>
            </ul>                        
        </div>
    </div>   
</div>
<div id="sys-info" class="left" style="width:80%;">
	<?php load_documentations(); ?>
	<?php
    switch($_REQUEST['type']){
        case 'modules':
            echo '<h1>Modules</h1>';
            phpinfo(INFO_MODULES);
            break;
        case 'environments':
            echo '<h1>Environments</h1>';
            phpinfo(INFO_ENVIRONMENT);
            break;
        case 'variables':
            echo '<h1>Variables</h1>';
            phpinfo(INFO_VARIABLES);
            break;
			
        default:
            phpinfo(INFO_GENERAL);
			phpinfo(INFO_LICENSE);			
            break;
    }
    ?>
</div>
<div class="clear"></div>
<?php
/*
phpinfo(INFO_CONFIGURATION);



phpinfo(INFO_ENVIRONMENT);*/
?>
