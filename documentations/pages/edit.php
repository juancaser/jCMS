<?php
global $title;
?>
<h2><?php echo $title;?></h2>
<?php if($_REQUEST['id'] > 0):?>
<?php else:?>
<p>Posts/Pages are the entries that display in your home page. In contrast to pages, posts and pages are usually the same except that pages can be used as a parent page while post can't.</p>
<p>To write a Post/Page: </p>
<ul class="nostyle">
	<li>1.&nbsp;&nbsp;Log in to your <?php get_siteinfo('name');?> Backend Panel.</li>
	<li>2.&nbsp;&nbsp;Click the <a href="<?php get_siteinfo('url');?>/backend/pages.php">Page</a> or <a href="<?php get_siteinfo('url');?>/backend/pages.php?mod=post">Post</a> link.</li>
	<li>3.&nbsp;&nbsp;Click the <strong>Add Page</strong> or <strong>Add Post</strong> button to go to Page Editor.</li>
    <li>4.&nbsp;&nbsp;Start filling in the blanks.</li>
    <li>5.&nbsp;&nbsp;When you are ready, click the <strong>Publish</strong> or <strong>Save as Draft</strong>.</li>

</ul>
<h3>The Page Editor</h3>
<p>If you have experienced working on wordpress then you'll find the editor easy to use since it is similar to the one at wordpress.</p>
<p>The page editor are divided into three areas: <em>Sidebar Area</em>, <em>Editor Area</em> and the <em>Custom Area</em>.</p>
<ul>
	<li>
    	<p><strong>Sidebar Area</strong> - The sidebar are also divided into two sections or box: Button box and the Page Option box</p>
		<ul>
        	<li><strong>Button Box</strong> - Are the part of the sidebar where you can find the <strong>Publish</strong>/<strong>Update</strong>, <strong>Save as Draft</strong> and <strong>Back</strong> button.</li>
            <li>
            	<p><strong>Page/Post Option Box</strong> - This is where you set the page type, parent page, template, and the menu order.</p>
                <p>The Page/Post Options are described below:</p>
                <ul>
                	<li><strong>Page Type</strong> - This will determined what type of page to display on the site. There are two option on this field <strong>Page</strong> and <strong>Post</strong></li>
                    <li><strong>Parent Page</strong> - Applicable only on <strong>Page Type = Page</strong>. Setting this will make you page a subpage of the selected parent page.</li>
					<li><strong>Template</strong> - Page template.</li>
                    <li><strong>Menu Order</strong> - Menu order are only applicable on listings both in frontend and backend.</li>
                </ul>
           	</li>
        </ul>
    </li>
    <li><strong>Editor Area</strong> - The mosts important of all the areas, this is where you will put your Title and Content of you page.</li>
    <li>
    	<p><strong>Custom Area</strong> - This is where you set additional settings to you page like <em>Custom Fields</em>, <em>Meta Information</em> and <em>Settings</em>.</p>
        <p>The Custom Area tabs are described below:</p>
        <ul>
        	<li><strong>Custom Fields</strong> - General custom fields. Clicking on the <strong>Add</strong> button will add more field or <strong>Remove</strong> if you want to remove it. Left textfield is called <strong>Key</strong> and the Right field is called <strong>Value</strong>.</li>
        	<li><strong>Meta Information</strong> - If set this will be used as the meta description and meta keyword on you webpage that will help web crawler identify you page.</li>
        	<li><strong>Settings</strong> - Currently the only setting added is <em>Featured Page</em>. Setting this will make your page appear on the homepage slideshow.</li>
        </ul>
    </li>
</ul>
<h3>Inserting Images to the Page/Post</h3>
<ul class="nostyle">
	<li><strong>By Uploading</strong></li>
	<li>&nbsp;&nbsp;1.&nbsp;&nbsp;Click the <img src="<?php get_siteinfo('url');?>/backend/images/fm.gif" /> icon to toggle filemanager dialog <em>(See File Manager instruction on how to upload)</em>.</li>
	<li>&nbsp;&nbsp;2.&nbsp;&nbsp;Once the image is successfully uploaded click the <strong>Insert to Post</strong> to insert image to editor. CLick close if you done adding images.</li>
	<li><strong>By Gallery</strong></li>
	<li>&nbsp;&nbsp;1.&nbsp;&nbsp;On your file manager dialog click the <strong>Gallery</strong> tab to load gallery.</li>
    <li>&nbsp;&nbsp;2.&nbsp;&nbsp;Once the gallery is loaded click <strong>Insert to Post</strong> to insert image to editor. CLick close if you done adding images.</li>
</ul>
<h3>Editing HTML Source Code</h3>
<p>Click <img src="<?php get_siteinfo('url');?>/documentations/pages/images/html_code.gif" /> to toggle HTML Source Editor. Click <strong>Update</strong> to update your visual editor.</p>

<h3>Adding Post/Page to Featured Page <em>(Homepage Slideshow)</em></h3>
<ul class="nostyle">
	<li>&nbsp;&nbsp;1.&nbsp;&nbsp;On Custom Area, click Settings tab.</li>
	<li>&nbsp;&nbsp;2.&nbsp;&nbsp;Toggle radio button <strong>Yes</strong> to activate and add the page to featured page lists.</li>
	<li>&nbsp;&nbsp;3.&nbsp;&nbsp;<strong>Optional.</strong> Set the show order if necessary, if not set the arrangement of the slideshow is based from page/post published date.</li>
	<li>&nbsp;&nbsp;4.&nbsp;&nbsp;If you want to add extra information on the slideshow, <strong>Custom</strong> is where you put your code, and if you want to make the whole image clickable just use this code <code>&lt;a href="{link_me}"&gt;&lt;/a&gt;</code>.</li>
	<li>&nbsp;&nbsp;5.&nbsp;&nbsp;Click <strong>Browse</strong> to upload image you want to use on the slideshow.</li>
</ul>
<?php endif;?>