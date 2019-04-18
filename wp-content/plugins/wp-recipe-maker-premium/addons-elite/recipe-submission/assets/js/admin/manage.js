jQuery(document).ready(function() {
    jQuery(document).on('click', '.wprm-manage-recipe-submissions-edit', function(e) {
        e.preventDefault();

        var id = jQuery(this).data('id');

        WPRecipeMaker.admin.Modal.open(false, {
            recipe_id: id
        });
    });

    jQuery(document).on('click', '.wprm-manage-recipe-submissions-approve', function(e) {
        e.preventDefault();

        var id = jQuery(this).data('id');

        var data = {
            action: 'wprm_approve_recipe_submission',
            security: wprm_admin.nonce,
            recipe_id: id,
        };
        
        jQuery.post(wprm_admin.ajax_url, data, function() {
            jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
        });
    });

    jQuery(document).on('click', '.wprm-manage-recipe-submissions-approve-add', function(e) {
        e.preventDefault();

        var id = jQuery(this).data('id');

        var data = {
            action: 'wprm_approve_add_recipe_submission',
            security: wprm_admin.nonce,
            recipe_id: id,
        };
        
        jQuery.post(wprm_admin.ajax_url, data, function(out) {
            if(out.success && out.data.edit_link) {
                window.location = out.data.edit_link;
            } else {
                jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
            }
        });
    });

    jQuery(document).on('click', '.wprm-manage-recipe-submissions-delete', function(e) {
        e.preventDefault();

        var id = jQuery(this).data('id'),
            name = jQuery('#wprm-manage-recipe-submissions-name-' + id).text();
        
        if(confirm('Are you sure you want to delete "' + name + '"?')) {
            var data = {
                action: 'wprm_delete_recipe_submission',
                security: wprm_admin.nonce,
                recipe_id: id,
            };
            
            jQuery.post(wprm_admin.ajax_url, data, function() {
                jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
            });
        }
    });
});