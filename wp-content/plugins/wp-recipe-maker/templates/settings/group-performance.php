<?php
$performance = array(
	'id' => 'performance',
	'name' => __( 'Performance', 'wp-recipe-maker' ),
	'settings' => array(
		array(
			'id' => 'performance_use_combined_stars',
			'name' => __( 'Output Combined Stars in Comments', 'wp-recipe-maker' ),
			'description' => __( 'Reduce DOM nodes by using one image for stars in comments. Disable to be able to use the comment star color setting.', 'wp-recipe-maker' ),
			'type' => 'toggle',
			'default' => true,
		),
	),
);
