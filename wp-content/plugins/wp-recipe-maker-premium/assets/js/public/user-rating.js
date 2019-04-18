import '../../css/public/user-rating.scss';

if (wprmp_public.settings.features_user_ratings) {
	jQuery(document).ready(function($) {
		var color = wprmp_public.settings.template_color_icon;

		jQuery(document).on('mouseenter', '.wprm-user-rating-allowed .wprm-rating-star', function() {		
			if ( 'modern' === wprmp_public.settings.recipe_template_mode && jQuery(this).data('color') ) {
				color = jQuery(this).data('color');
			}
			jQuery(this).prevAll().andSelf().each(function() {
				jQuery(this)
					.addClass('wprm-rating-star-selecting-filled')
					.find('polygon')
					.css('fill', color);
			});
			jQuery(this).nextAll().each(function() {
				jQuery(this)
					.addClass('wprm-rating-star-selecting-empty')
					.find('polygon')
					.css('fill', 'none');
			});
		});
		jQuery(document).on('mouseleave', '.wprm-user-rating-allowed .wprm-rating-star', function() {
			jQuery(this).siblings().andSelf().each(function() {
				jQuery(this)
					.removeClass('wprm-rating-star-selecting-filled wprm-rating-star-selecting-empty')
					.find('polygon')
					.css('fill', '');
			});
		});

		jQuery(document).on('click', '.wprm-user-rating-allowed .wprm-rating-star', function() {
			var star = jQuery(this),
				rating_container = star.parents('.wprm-recipe-rating'),
				rating = star.data('rating'),
				recipe_id = rating_container.data('recipe');

			// Backwards compatibility.
			if (!recipe_id) {
				recipe_id = star.parents('.wprm-recipe-container').data('recipe-id');
			}

			// Update current view.
			if(rating_container.length > 0) {
				var count = rating_container.data('count'),
					total = rating_container.data('total'),
					user = rating_container.data('user');

				if(user > 0) {
					total -= user;
				} else {
					count++;
				}

				total += rating;

				var average = Math.ceil(total/count * 100) / 100;

				// Upate details.
				rating_container.find('.wprm-recipe-rating-average').text(average);
				rating_container.find('.wprm-recipe-rating-count').text(count);

				// Update stars.
				var stars = Math.ceil(average);

				for(var i = 1; i <= 5; i++) {
					var star = rating_container.find('.wprm-rating-star-' + i);
					star.removeClass('wprm-rating-star-full').removeClass('wprm-rating-star-empty');

					if(i <= stars) {
						star.addClass('wprm-rating-star-full');
					} else {
						star.addClass('wprm-rating-star-empty');
					}
				}
			}

			// Update rating via AJAX.
			var data = {
				action: 'wprm_user_rate_recipe',
				security: wprm_public.nonce,
				recipe_id: recipe_id,
				rating: rating
			};
	
			jQuery.post(wprm_public.ajax_url, data);
		});
	});
}
