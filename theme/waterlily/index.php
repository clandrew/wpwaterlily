<?php get_header(); ?>
<div id="main">

<!-- end header -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="main_title"><a href="<?php the_permalink() ?>" rel="bookmark">📌<?php the_title(); ?></a></div>

<h3>📅<?php the_time('F jS, Y');?> </h3>

<div class="main_post">

<?php the_content(__('<strong>&raquo; Continue Reading</strong>')); ?></div>
<div id="tags">

<?php
$tags = wp_get_post_tags(get_the_ID());
foreach ( $tags as $tag ) {
	$tag_link = get_tag_link( $tag->term_id );
	echo "<a href='{$tag_link}'>&bull;{$tag->name}</a> ";
}
?>

</div>

<div class="main_feedback">
<?php the_time('F jS, Y');?> at <?php the_time() ?>&nbsp;|&nbsp;<?php comments_popup_link(__('Comments &amp; Trackbacks (0)'), __('Comments &amp; Trackbacks (1)'), __('Comments &amp; Trackbacks (%)')); ?>&nbsp;|&nbsp;<a href="<?php the_permalink() ?>" rel="bookmark">Permalink</a> </div>
<hr/>


<!--
	<?php trackback_rdf(); ?>
	-->


<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>

<div class="navi" align="right"><?php posts_nav_link(' &#8212; ', __('&laquo; Previous Page'), __('Next Page &raquo;')); ?></div>

</div>


<div id="menu">
<?php get_sidebar(); ?>
</div>

</div>
<div class="clearfix"></div>
<div id="footer"><?php get_footer(); ?></div>
</div>

</body>
</html>
