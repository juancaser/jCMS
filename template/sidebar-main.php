<?php if(!defined('IMPARENT')){exit();} // No direct access ?>
<ul class="sidebar">
    <li id="quicklinks">
        <h3>QUICKLINKS</h3>
        <ul>
            <li class="sb active"><a href="<?php the_permalink('contact-us');?>?subject=order">Wheres my Order?</a></li
        </ul>
    </li>       
    <li class="sidebar clear" id="blogs">
        <?php $blog = get_page('blog',array('ID','title','content'));?>
        <h3><?php echo $blog->title;?></h3>
        <?php echo make_excerpt(html_entity_decode($blog->content),500,'... <a class="read-more" href="'.the_permalink($blog->ID,false).'#more">read more</a></p>');?>
    </li>
</ul>