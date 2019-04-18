<div class="wprmprs-layout-block-recipe_image">
	<?php if ( $block['label'] ) : ?>
	<label class="wprmprs-form-label"><?php echo do_shortcode( $block['label'] ); ?><?php if ( $block['required'] ) { echo '<span class="wprmprs-layout-block-required">*</span>'; } ?></label>
	<?php endif; ?>
	<?php if ( $block['help'] ) : ?>
	<div class="wprmprs-form-help"><?php echo do_shortcode( $block['help'] ); ?></div>
	<?php endif; ?>
    <input type="file" name="recipe_image" accept="image/*" data-placeholder="<?php echo esc_attr( do_shortcode( $block['placeholder'] ) ); ?>" <?php if ( $block['required'] ) { echo 'required'; } ?>/>
</div>