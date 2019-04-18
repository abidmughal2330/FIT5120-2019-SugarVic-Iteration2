jQuery(document).ready(function($) {
    jQuery(document).on('click', '.wprm-manage-recipes-actions-reset-user-ratings', function(e) {
        e.preventDefault();

        var id = jQuery(this).data('id'),
            name = jQuery('#wprm-manage-recipes-name-' + id).text();

        if(confirm('Are you sure you want to reset the user ratings for "' + name + '"?')) {
            var data = {
                action: 'wprm_reset_user_ratings',
                security: wprm_admin.nonce,
                recipe_id: id,
            };
        
            jQuery.post(wprm_admin.ajax_url, data, function() {
                jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
            });
        }
    });
});