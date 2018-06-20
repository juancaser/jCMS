<?php
global $pathinfo;
if($pathinfo->script == 'components.php'){
	$component = ($_REQUEST['comp'] !='' ? $_REQUEST['comp'] : 'main');	
	if($component == 'main'){
		$path = GBL_ROOT.'/documentations/components/main.php';
	}else{
		$path = GBL_ROOT.'/components/'.$component.'/documentations/'.($_REQUEST['mod']!='' ? $_REQUEST['mod'] : 'main').'.php';
	}	
}else{
	$page = ($pathinfo->filename == 'index' ? 'dashboard' : $pathinfo->filename);
	$page = ($filename == 'backend' ? 'dashboard' : $page);
	$file = ($_REQUEST['mod'] !='' ? $_REQUEST['mod'] : 'main');
	$path = GBL_ROOT.'/documentations/'.$page.'/'.$file.'.php';
}
?>
<style type="text/css">
#documentation{
	border-top:none;
	background-color:#FFFFFF;
	/*margin-top:-5px;
	margin-bottom:10px;*/
	margin:0 0 10px 0;padding:0;
	width:100%;
}
	#documentation .article{
		background-color:#FFFFFF;
		color:#333333;
		font-size:13px;
		border:1px solid #D2D2D2;
		border-top:none;
		padding:15px 20px 20px 20px;
		box-shadow:0 4px 18px #C8C8C8;
		-moz-border-radius:0 0 5px 5px;
		-webkit-border-radius:0 0 5px 5px;
		-khtml-border-radius:0 0 5px 5px;
		border-radius:0 0 5px 5px;
	}
		#documentation .article h2,#documentation .article h3{
			border:none;
			background:none;
			text-decoration:none;
			font-size:18px;
			font-weight:normal;
			font-weight:normal;
			color:#D54E21;
			padding:0 0 10px 0;margin:0;
			line-height:1em;
		}
			#documentation .article h3{
				font-size:14px;
				padding-bottom:3px;
				margin:10px 0;
				border-bottom:1px solid #DADADA;
				/*color:#333333;*/
				font-weight:bold;
			}
		#documentation .article p{
			font-weight:normal;
			color:#333333;
			font-size:13px;
			padding:0 0 10px 0;margin:0;
			line-height:1.3em;
		}
		#documentation .article ul,
			#documentation .article ul{
			padding:0;
			margin:0 0 10px 30px;
			list-style-type:square;
		}
		#documentation .article ul.nostyle{
			padding:0;
			margin:0 0 10px 20px;
			list-style-type:none;
		}
			#documentation .article ul ul{
				list-style-type:disc;
			}
			#documentation .article ul ul ul{
				list-style-type:circle;
			}
			#documentation .article ul li{
				padding:0;margin:0;
			}
				#documentation .article ul li a{
				}
	#documentation a.need-help{
		display:block;
		padding:0 10px;
		border:1px solid #D2D2D2;
		border-top:1px solid #ECECEC;
		background:url('images/navbar.gif') repeat-x 0 1%;
		color:#333333;
		text-shadow:0 1px 0 rgba(255, 255, 255, 0.8);
		-moz-border-radius:0 0 5px 5px;-webkit-border-radius:0 0 5px 5px;-khtml-border-radius:0 0 5px 5px;border-radius:0 0 5px 5px;
		text-decoration:none;
		margin-right:10px;
		font-size:14px;
	}
		#documentation a.need-help:hover{
			color:#D54E21;
		}
</style>
<script type="text/javascript">
	function toggle_documentation(){
		$('#documentation').find('.article').slideToggle('slow',function(){
			if($(this).hasClass('hide')){
				$(this).removeClass('hide');
			}else{
				$(this).addClass('hide');
			}
		});
	}
	
	function load_documentation(_this){
		var sub_doc = _this.replace(/#/g,'');
		$('#documentation').find('.article').slideUp('slow',function(){
			$(this).addClass('hide').load('<?php get_siteinfo('url');?>/documentations/<?php echo $page;?>/' + sub_doc + '.php','',function(){
				if(sub_doc!='main'){
					$(this).append('<p><a onclick="load_documentation(\'main\');" href="javascript:void(0);">Back to Documentation Main</a></p>');
				}
				$(this).slideDown('slow',function(){
					$(this).removeClass('hide')
				})
			});
		});

	}
</script>
<div id="documentation">    
    <div class="article hide">
        <?php		
        if(file_exists($path)){			
            include($path);
        }else{
            echo 'Documentation not found. Please contact the developer for help.';
        }
        ?>
    </div>
    <a title="Click to toggle documentation" alt="Click to toggle documentation" class="right need-help" href="javascript:toggle_documentation();">Need Help?</a>
    <div class="clear"></div>
</div>
