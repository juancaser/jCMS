		<?php global $pathinfo;?>
		<?php //print_r($pathinfo);?>
    	<div id="top-navigation">
        	<ul class="dd-menu">
            	<li class="dashboard<?php echo (($pathinfo->dirname == '/backend/' || $pathinfo->script == 'index.php') ? ' active' : '');?>"><a href="<?php echo BACKEND_DIRECTORY;?>">Dashboard</a></li>
            	<li class="page<?php echo ($pathinfo->script == 'pages.php' ? ' active' : '');?>"><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php">Pages &rsaquo;</a>
                	<ul class="sub-menu">
	                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php">Pages</a></li>
	                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=post">Posts</a></li>
	                    <li><a href="<?php echo BACKEND_DIRECTORY;?>/pages.php?mod=draft">Draft</a></li>
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/trash.php?type=page">Trashed</a></li>
                    </ul>
                </li>            	
            	<li class="media<?php echo ($pathinfo->script == 'filemanager.php' ? ' active' : '');?>"><a href="<?php echo BACKEND_DIRECTORY;?>/filemanager.php">File Manager &rsaquo;</a>
                	<ul class="sub-menu">
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/filemanager.php?mod=upload">Upload</a></li>
                    </ul>
                </li>
                <?php do_action('backend_top_navigation');?>
            	<li class="system<?php echo (in_array($pathinfo->script,array('system.php','users.php')) ? ' active' : '');?>"><a href="<?php echo BACKEND_DIRECTORY;?>/system.php">System &rsaquo;</a>
                	<ul class="sub-menu">
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/users.php">Users</a></li>
                        <?php if(file_exists(GBL_ROOT_TEMPLATE.'/backend/layout.php')):?>
                        <li><a href="<?php echo BACKEND_DIRECTORY;?>/system.php?mod=layout">Layout</a></li>
						<?php endif;?>
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/components.php">Components</a></li>
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/system.php?mod=sysinfo">System Information</a></li>
                    </ul>
                </li>
            	<!--li class="help<?php echo ($pathinfo->script == 'help.php' ? ' active' : '');?>"><a href="<?php echo BACKEND_DIRECTORY;?>/help.php">Help &rsaquo;</a>
                	<ul class="sub-menu">
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?mod=tutorial">Tutorial</a></li>
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?mod=support">Support</a></li>
                    	<li><a href="<?php echo BACKEND_DIRECTORY;?>/help.php?mod=about">About</a></li>
                    </ul>
                </li-->
            	<li><a href="<?php echo BACKEND_DIRECTORY;?>/logout.php">Logout</a></a>
            </ul>
            <div class="clear"></div>
        </div>