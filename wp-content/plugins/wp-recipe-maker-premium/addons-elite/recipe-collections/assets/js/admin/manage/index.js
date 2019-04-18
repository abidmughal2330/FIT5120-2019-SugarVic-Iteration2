jQuery(document).ready(function() {
    jQuery(document).on('click', '.wprm-manage-recipe-collections-delete', function(e) {
        e.preventDefault();

        var id = jQuery(this).data('id'),
            name = jQuery('#wprm-manage-recipe-collections-name-' + id).text();
        
        if(confirm('Are you sure you want to delete "' + name + '"?')) {
            fetch(`${wprmprc_admin.endpoints.collections}/${id}`, {
                method: 'DElETE',
                headers: {
                    'X-WP-Nonce': wprmprc_admin.api_nonce,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
            }).then((response) => {
                jQuery('.wprm-manage-datatable').DataTable().ajax.reload(null, false);
            });
        }
    });
});