<!-- begin sidebar -->

<ul>
<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar() ) : else : ?>

<li><h2>Recent Posts</h2>
<ul><?php wp_get_archives('type=postbypost&limit=10'); ?></ul></li>

<li><h2>Blogroll</h2>	
<ul>
<?php get_links('-1', '<li>', '</li>', '<br />', FALSE, 'id', TRUE, TRUE, -1, TRUE); ?>

</ul></li>


<li><h2>Categories</h2>
<ul><?php wp_list_cats(); ?></ul></li>



<li><h2>Archives</h2>
<ul><?php wp_get_archives('type=monthly'); ?></ul></li>


<li><h2>Meta</h2>
<ul>
<?php wp_register(); ?>
<li><?php wp_loginout(); ?></li>
<li><a href="feed:<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
<li><a href="feed:<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a></li>
<li><a href="http://wordpress.org/" title="<?php _e('Powered by WordPress, state-of-the-art semantic personal publishing platform.'); ?>"><abbr title="WordPress">WP</abbr></a></li>
<li><a href="http://www.wordpresstemplates.com/" title="Free Wordpress Themes">WordPress Themes</a></li>
<?php wp_meta(); ?>
</ul></li>

<li><h2>Search</h2>
<ul><li>
<form id="searchform" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<input type="text" name="s" id="s" size="15" /><br />
<input type="submit" value="<?php _e('Search'); ?>" />
</form></li></ul></li>
<?php endif; ?>

</ul>

<!-- end sidebar -->