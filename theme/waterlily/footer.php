<div class="footertext">
<?php bloginfo('name'); ?>
<br />
<a href="feed:<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a> and <a href="feed:<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>.<br />
<?php wp_footer(); ?>
</div>

<?php
function print_footer()
{
	echo "Based off of a theme design by <a href=\"https://lisasabin-wilson.com\">Lisa Sabin</a> with some other stuff added.";
}
?>
