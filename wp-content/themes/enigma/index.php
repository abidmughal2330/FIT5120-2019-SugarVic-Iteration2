<?php get_header();
$wl_theme_options = weblizar_get_options(); 
if($wl_theme_options['breadcrumb']!='') { 
get_template_part('breadcrums'); } else { ?>
<style>
.row.enigma_blog_wrapper {
    margin-top: 70px;
}
</style>
<?php } ?>
<div class="container">	
	<div class="row enigma_blog_wrapper">
	<div class="col-md-8">
	<?php if ( have_posts()): 
	while ( have_posts() ): the_post(); ?>	
	<?php get_template_part('post','content'); ?>	
	<?php endwhile; 
	endif; ?>
	<?php weblizar_navigation(); ?>	
	</div>
	<?php get_sidebar(); ?>
	</div>	
</div>
<?php get_footer(); ?>