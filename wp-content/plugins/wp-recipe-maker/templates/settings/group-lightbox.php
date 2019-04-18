<?php
$lightbox = array(
	'id' => 'lightbox',
	'name' => __( 'Lightbox', 'wp-recipe-maker' ),
	'description' => __( 'Use a lightbox plugin and enable clickable images to have your recipe and/or instruction images open in a lightbox after clicking on them.', 'wp-recipe-maker' ),
	'documentation' => 'https://help.bootstrapped.ventures/article/176-clickable-images-for-lightbox-integration',
	'settings' => array(
		array(
			'id' => 'recipe_image_clickable',
			'name' => __( 'Clickable Recipe Image', 'wp-recipe-maker' ),
			'type' => 'toggle',
			'default' => false,
		),
		array(
			'id' => 'instruction_image_clickable',
			'name' => __( 'Clickable Instruction Images', 'wp-recipe-maker' ),
			'type' => 'toggle',
			'default' => false,
		),
	),
);
