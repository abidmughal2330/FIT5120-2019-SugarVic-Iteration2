<script src='https://www.google.com/recaptcha/api.js'></script>
<script>
    jQuery('#wprm-recipe-submission').submit(function(event) {
        if (!grecaptcha.getResponse()) {
            event.preventDefault();
            grecaptcha.execute();
        }
    });

    function wprmpsOnCaptchaCompleted(token) {
        jQuery('#wprm-recipe-submission').submit();
    }
</script>
<div class="wprmprs-layout-block-submit">
    <div class="g-recaptcha" data-callback="wprmpsOnCaptchaCompleted" data-size="invisible" data-sitekey="<?php echo esc_attr( WPRM_Settings::get( 'recipe_submission_recaptcha_site_key' ) ); ?>"></div>
    <input type="submit" name="wprmprs_submit" class="button" value="<?php echo esc_attr( do_shortcode( $block['text'] ) ); ?>" />
</div>