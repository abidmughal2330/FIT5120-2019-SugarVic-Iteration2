<?php get_header(); 
$wl_theme_options = weblizar_get_options(); 
if($wl_theme_options['breadcrumb']!='') { ?>
<div class="enigma_header_breadcrum_title">	
	<div class="container">
		<div class="row">
		<?php if(have_posts()) :?>
			<div class="col-md-12">
			<?php /* translators: %s: author name. */ ?>
			<h1><?php printf( esc_html__( 'Author Archives: %s', 'enigma' ), '<span class="vcard">'. get_the_author() .'</span>' ) ; ?>
			</h1>
			</div>
		<?php endif; ?>
		<?php rewind_posts(); ?>
		</div>
	</div>	
</div>
<?php } else { ?>
<style>
.row.enigma_blog_wrapper {
    margin-top: 70px;
}
</style>
<?php } ?>
<div class="container">	
	<div class="row enigma_blog_wrapper">
	<div class="col-md-8">
	<?php if ( have_posts()): while ( have_posts() ): the_post();
	get_template_part('post','content'); ?>
	<?php endwhile; 
	endif; 
	weblizar_navigation();
	?>
	</div>		
	<?php get_sidebar(); ?>
	</div>
</div>
<?php get_footer(); ?>	