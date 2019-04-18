<div class="wprmprs-layout-block-textarea wprmprs-layout-block-<?php echo esc_attr( $block['type'] ); ?>">
	<?php if ( $block['label'] ) : ?>
	<label class="wprmprs-form-label"><?php echo do_shortcode( $block['label'] ); ?><?php if ( $block['required'] ) { echo '<span class="wprmprs-layout-block-required">*</span>'; } ?></label>
	<?php endif; ?>
	<?php if ( $block['help'] ) : ?>
	<div class="wprmprs-form-help"><?php echo do_shortcode( $block['help'] ); ?></div>
	<?php endif; ?>
	<textarea class="wprmprs-form-input" name="<?php echo esc_attr( $block['type'] ); ?>" placeholder="<?php echo esc_attr( do_shortcode( $block['placeholder'] ) ); ?>" <?php if ( $block['required'] ) { echo 'required'; } ?>></textarea>
</div>