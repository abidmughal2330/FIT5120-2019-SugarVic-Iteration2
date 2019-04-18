<?php if (!function_exists('theme_hire_page')) {
	function theme_hire_page() {
	$page1=add_theme_page(__('Welcome to Enigma', 'enigma'), __('<span style="color:#ffc94a">Hire Us</span>', 'enigma'), 'edit_theme_options', 'hire-page', 'theme_display_theme_info_page');
	
	add_action('admin_print_styles-'.$page1, 'theme_admin_info');
	}	
}
add_action('admin_menu', 'theme_hire_page');

function theme_admin_info(){
	// CSS
	wp_enqueue_style('bootstrap',  get_stylesheet_directory_uri() .'/core/admin/bootstrap/css/bootstrap.css');
	wp_enqueue_style('hire',  get_stylesheet_directory_uri() .'/core/admin/admin-themes.css');
	wp_enqueue_style('fontawesome',  get_template_directory_uri() .'/css/font-awesome-4.7.0/css/font-awesome.css');
	//JS
	wp_enqueue_script('jquery');
	wp_enqueue_script('bootstrap-js',get_stylesheet_directory_uri() .'/core/admin/bootstrap/js/bootstrap.js');
} 
if (!function_exists('theme_display_theme_info_page')) {
	function theme_display_theme_info_page() {
		$theme_data = wp_get_theme(); ?>
		
		<div class="row theme-heighlights">
			<div class="support-data">
				<h3 class="high-title">Get Things Done Quickly:</h3>
				<ul>
					<li><i class="fa fa-star theme-icon"></i> Hire once, A to Z WP customization.</li>	 	
					<li><i class="fa fa-star theme-icon"></i> Quick ticket support, quick solution</li>					
					<li><i class="fa fa-star theme-icon"></i> Dedicated expert working only for you!</li>
				</ul>
			</div>
			<div class="col-md-12 blocks">
				<div class="col-md-4">
					<div class="col-md-12 hire-block">
						<span class="recent-ribbon">Starter Plan</span>
						<h2>Hire me for 01 Hr</h2>
						
						<a href="skype:weblizar?chat" class="hire-btn" target="_blank">Hire Us - in $9</a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="col-md-12 hire-block">
						<span class="recent-ribbon">Economy Plan</span>
						<h2>Hire me for 5 Hr</h2>
						
						<a href="skype:weblizar?chat" class="hire-btn" target="_blank">Hire Us - in $40</a>
					</div>
				</div>
				<div class="col-md-4">
					<div class="col-md-12 hire-block">
						<span class="recent-ribbon">Valuable Plan</span>
						<h2>Hire me for 40 Hr</h2>
						
						<a href="skype:weblizar?chat" class="hire-btn" target="_blank">Hire Us  - in $200</a>
					</div>
				</div>
				
			</div>
		</div>
<?php
	}
}
?>