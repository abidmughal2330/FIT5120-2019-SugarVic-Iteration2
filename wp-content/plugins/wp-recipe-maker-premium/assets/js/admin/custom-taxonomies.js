import '../../css/admin/custom-taxonomies.scss';

function edit_custom_taxonomy(key, singular, plural) {
    var data = {
			action: 'wprmp_edit_custom_taxonomy',
			security: wprm_admin.nonce,
			key: key,
            singular: singular,
            plural: plural
	};

	jQuery.post(wprm_admin.ajax_url, data, function() {
		location.reload();
	});
};

function delete_custom_taxonomy(key) {
    var data = {
			action: 'wprmp_delete_custom_taxonomy',
			security: wprm_admin.nonce,
			key: key,
	};

	jQuery.post(wprm_admin.ajax_url, data, function() {
		location.reload();
	});
};

jQuery(document).ready(function($) {
    jQuery('.wprm-manage-custom-taxonomies-actions').tooltipster({
        content: '<div class="wprm-manage-custom-taxonomies-actions-tooltip"><div class="tooltip-header">&nbsp;</div><a href="#" class="wprm-manage-custom-taxonomies-actions-edit">Edit Custom Taxonomy</a><a href="#" class="wprm-manage-custom-taxonomies-actions-delete">Delete Custom Taxonomy</a></div>',
        contentAsHTML: true,
        functionBefore: function() {
            var instances = jQuery.tooltipster.instances();
            jQuery.each(instances, function(i, instance){
                instance.close();
            });
        },
        functionReady: function(instance, helper) {
            var key = jQuery(helper.origin).data('key');

            jQuery(helper.tooltip).find('a').data('key', key);
            jQuery(helper.tooltip).find('.tooltip-header').text(key);
        },
        interactive: true,
        delay: 0,
        side: 'left',
        trigger: 'custom',
        triggerOpen: {
            mouseenter: true,
            touchstart: true
        },
        triggerClose: {
            click: true,
            tap: true
        },
    });

    jQuery(document).on('click', '.wprm-manage-custom-taxonomies-actions-edit', function() {
		var key = jQuery(this).data('key');

        var singular = prompt('What is the new singular name for "' + key + '"?');
        
        if(singular) {
            var plural = prompt('What is the new plural name for "' + key + '"?');

            if(plural) {
                edit_custom_taxonomy(key, singular, plural);
            }
        }
	});

    jQuery(document).on('click', '.wprm-manage-custom-taxonomies-actions-delete', function() {
		var key = jQuery(this).data('key');
		
		if(confirm('Are you sure you want to delete "' + key + '"?')) {
			delete_custom_taxonomy(key);
		}
	});
});