import 'tooltipster';
import 'tooltipster/dist/css/tooltipster.bundle.css';

import alarmSound from '../../sounds/alarm.mp3';
import '../../css/public/timer.scss';

let timer_seconds = 0;
let timer_seconds_remaining = 0;
let timer = undefined;
let alarm_timer = undefined;

function timer_play() {
    jQuery('#wprm-timer-play').hide();
    jQuery('#wprm-timer-pause').show();

    clearInterval(timer);
    timer = setInterval(update_timer, 1000);
};

 function timer_pause() {
    jQuery('#wprm-timer-pause').hide();
    jQuery('#wprm-timer-play').show();
    
    clearInterval(timer);
};

function update_timer() {
    timer_seconds_remaining--;
    if(timer_seconds_remaining <= 0) {
        timer_seconds_remaining = 0;
        timer_finished();
    }

    jQuery('#wprm-timer-remaining').text(timer_seconds_to_hms(timer_seconds_remaining));

    var percentage_elapsed = 100 * (timer_seconds - timer_seconds_remaining) / timer_seconds;
    jQuery('#wprm-timer-bar-elapsed').css('width', percentage_elapsed + '%');
};

function timer_finished() {
    // Clear existing timers.
    timer_pause();
    clearInterval(alarm_timer);

    // Sound alarm once and keep pulsate background until closed.
    timer_play_alarm();
    timer_finished_sequence();
    timer = setInterval(timer_finished_sequence, 2000);
};

function timer_finished_sequence() {
    jQuery('#wprm-timer-container')
        .animate({ opacity: 0.5 }, 500 )
        .animate({ opacity: 1 }, 500 )
        .animate({ opacity: 0.5 }, 500 )
        .animate({ opacity: 1 }, 500 );
};

function timer_play_alarm() {
    var alarm = new Audio(wprmp_public.timer.sound_dir + alarmSound);
    alarm.play();
};

function open_timer(seconds) {
    remove_timer(function() {
        if(seconds > 0) {
            timer_seconds = seconds;
            timer_seconds_remaining = seconds;

            var timer = jQuery('<div id="wprm-timer-container"></div>').hide(),
                play = jQuery('<span id="wprm-timer-play" class="wprm-timer-icon">' + wprmp_public.timer.icons.play + '</span>'),
                pause = jQuery('<span id="wprm-timer-pause" class="wprm-timer-icon">' + wprmp_public.timer.icons.pause + '</span>'),
                time_remaining = jQuery('<span id="wprm-timer-remaining"></span>'),
                bar = jQuery('<span id="wprm-timer-bar-container"><span id="wprm-timer-bar"><span id="wprm-timer-bar-elapsed"></span></span></span>'),
                close = jQuery('<span id="wprm-timer-close" class="wprm-timer-icon">' + wprmp_public.timer.icons.close + '</span>');

            time_remaining.text(timer_seconds_to_hms(seconds));

            timer
                .append(play)
                .append(pause)
                .append(time_remaining)
                .append(bar)
                .append(close);

            jQuery('body').append(timer);
            timer_play();
            timer.fadeIn();
        }
    });
};

 function remove_timer(callback) {
    clearInterval(timer);
    clearInterval(alarm_timer);
    var timer = jQuery('#wprm-timer-container');

    if(timer.length > 0) {
        timer.clearQueue();
        timer.fadeOut(400, function() {
            timer.remove();
            if(callback !== undefined) {
                callback();
            }
        });
    } else {
        if(callback !== undefined) {
            callback();
        }
    }
}

 function timer_seconds_to_hms(s) {
    var h = Math.floor(s/3600);
    s -= h*3600;
    var m = Math.floor(s/60);
    s -= m*60;
    return (h < 10 ? '0'+h : h)+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s);
}


jQuery(document).ready(function($) {
	jQuery('.wprm-timer').each(function() {
		var timer_element = jQuery(this),
				seconds = parseInt(timer_element.data('seconds'));

		if( seconds > 0 ) {
			if( !jQuery('body').hasClass('wprm-print')) {
				// Make the servings a link
				timer_element.wrap('<a href="#" class="wprm-timer-link"></a>');

				// Add tooltip
				timer_element.tooltipster({
					content: wprmp_public.timer.text.start_timer,	
					interactive: true,
					delay: 0,
					trigger: 'hover',
				});
			}
		}
	});


	jQuery(document).on('click', '.wprm-timer-link', function(e) {
		e.preventDefault();
		e.stopPropagation();

        var seconds = parseInt(jQuery(this).find('.wprm-timer').data('seconds'));
        open_timer(seconds);
	});

	jQuery(document).on('click', '#wprm-timer-play', function(e) {
		timer_play();
	});
    jQuery(document).on('click', '#wprm-timer-pause', function(e) {
		timer_pause();
	});
    jQuery(document).on('click', '#wprm-timer-close', function(e) {
		remove_timer();
	});
});
