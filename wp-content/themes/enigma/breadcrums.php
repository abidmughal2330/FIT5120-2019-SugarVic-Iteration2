<div class="enigma_header_breadcrum_title">	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php $wl_theme_options = weblizar_get_options(); 
					if($wl_theme_options['breadcrumb_title']!='') { ?>
				<h1><?php if(is_home()){echo "";}else{the_title();} ?></h1>
				 <?php } else { ?>
				<style> .enigma_header_breadcrum_title {
				padding-top: 28px;} </style><?php } ?>
				<!-- BreadCrumb -->
                <?php if (function_exists('weblizar_breadcrumbs')) weblizar_breadcrumbs(); ?>
                <!-- BreadCrumb -->
			</div>
		</div>
	</div>	
</div>