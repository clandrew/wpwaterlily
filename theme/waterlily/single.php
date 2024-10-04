<?php get_header(); ?>
<div id="main">

<!-- end header -->
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>


<div class="main_title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></div>

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

<div class="main_feedback"><?php the_time('F jS, Y');?> at <?php the_time() ?></div>

<!--
	<?php trackback_rdf(); ?>
	-->
<?php comments_template(); // Get wp-comments.php template ?>


<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>



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