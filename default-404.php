<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
get_header();
?>

	<div id="content" class="narrowcolumn">

		<h2 class="center">Well this is embarrasing</h2>
		<p>It seems that we can't find the content you are looking for. We hope one of these will help you find it.</p>
		<?php 
		if(function_exists('trueGoogle404'))echo trueGoogle404(); 
		?>
	</div>


<?php get_sidebar(); ?>

<?php get_footer(); ?>