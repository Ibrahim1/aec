jQuery(document).ready(function($) {
	var typingTimer;

	jQuery('#topbar').dropdown();
	jQuery(".collapse").collapse({toggle: false, selector: '.aecadminform'}).on('show', function(e){
		jQuery(this).parent('.accordion-group').parent('.accordion').children('.accordion-group').children('.collapse').collapse('hide');
	});
	jQuery("#system-message").fadeOut('slow', function() { jQuery(this).slideUp( 'slow' ); });
	jQuery('#quicksearch').popover({
		trigger:'manual',
		placement:'bottom'
	});

	jQuery("#quicksearch")
	.on("keypress", function(e) {
		if (e.keyCode == 13) return false;
	}).on("keyup", function(e) {
		clearTimeout(typingTimer);

		inputString = this.value;

		typingTimer = setTimeout(lookup, "300");
	}).on("keydown", function(e) {
		jQuery('.popover .popover-content p').html("Searching...");
	}).on("focusin", function(e) {
		jQuery(this).popover('show');
		
		if ( this.value != "" ) {
			inputString = this.value;

			jQuery('.popover .popover-content p').html("Searching...");

			typingTimer = setTimeout(lookup, "100");
		}
	}).on("focusout", function(e) {
		jQuery("div.popover").fadeOut();
	});

	jQuery('form#adminForm input, form#adminForm select').change(function() {
	  jQuery('div.aec-buttons a[disabled="disabled"]').attr("disabled", false);
	});

	jQuery('a.quicksearch').on("click", function(e) {
		e.preventDefault();

		jQuery("#quicksearch").popover('show');

		jQuery("input#quicksearch").val( this.text ).focus();
	});

	jQuery('a#testexport').on("click", function(e) {
		var values = {};
		$.each($('#adminForm').serializeArray(), function(i, field) {
		    if ( typeof values[field.name] != 'undefined' ) {
		    	if ( typeof values[field.name] != 'object' ) {
		    		var temparray = new Array();
		    		temparray.push( values[field.name] );
		    		temparray.push( field.value );

		    		values[field.name] = temparray;
		    	} else {
		    		values[field.name].push( field.value );
		    	}
		    } else {
		    	values[field.name] = field.value;
		    }
		});

		values.task = 'testexport'+values.returnTask;
		values.export_method = 'test';

		jQuery('#export-result').html('<p>Loading...</p>');

		jQuery.post("index.php?option=com_acctexp" , values, function(data) {
			jQuery('#export-result').html(data);
		});
	});

	jQuery('#aecmenu-help').popover({
		trigger:'manual',
		placement:'below'
	});

	jQuery("#aecmenu-help")
	.on("click", function(e) {
		jQuery('#aecmenu-help').popover('show');

		jQuery.post("index.php?option=com_acctexp&task=getHelp" , {queryString: ""}, function(data) {
			jQuery('.popover .popover-content p').html(data);
		});
	});

	// fix sub nav on scroll - adapted, against better advice, from http://twitter.github.com/bootstrap/less.html
	var tbFixed = 0, btFixed = 0, navTop = 0, navBtn = 0;

	processScroll();

	jQuery(window).on('scroll', processScroll);

	function processScroll() {
		var i, scrollTop = jQuery(window).scrollTop();

		if ( navTop == 0 ) {
			navTop = jQuery('.topbar-inner').offset().top;
		}

		if (scrollTop >= navTop && !tbFixed) {
			tbFixed = 1;
			jQuery('.topbar-inner').addClass('topbar-fixed');
		} else if (scrollTop <= navTop && tbFixed) {
			tbFixed = 0;
			jQuery('.topbar-inner').removeClass('topbar-fixed');
		}

		if ( jQuery('.aec-buttons').length ) {
			if ( navBtn == 0 ) {
				navBtn = jQuery('.aec-buttons').offset().top - 42;
			}

			if (scrollTop >= navBtn && !btFixed) {
				btFixed = 1;
				jQuery('.aec-buttons').addClass('aec-buttons-fixed');
			} else if (scrollTop <= navTop && btFixed) {
				btFixed = 0;
				jQuery('.aec-buttons').removeClass('aec-buttons-fixed');
			}
		}
	}

	function lookup() {
		jQuery.post("index.php?option=com_acctexp&task=quicksearch&search="+inputString , {queryString: ""+inputString+""}, function(data) {
			jQuery('.popover .popover-content p').html(data);
		});
	}

	jQuery('div.aec-buttons').tooltip({placement: "bottom", selector: 'a.btn', delay: { show: 300, hide: 100 }});
	jQuery('div.control-group').tooltip({placement: "bottom", selector: '.bstooltip', delay: { show: 300, hide: 400 }});

});

function readNotice(id) {
	jQuery.post("index.php?option=com_acctexp&task=readNoticeAjax&id="+id , {queryString: ""}, function(data) {
		jQuery('#alert-'+id).remove();

		jQuery.post("index.php?option=com_acctexp&task=getNotice" , {queryString: ""}, function(data) {
			jQuery('#aec-alertlist').append(data);
		});

		if ( jQuery('#further-notices>span').html() ) {
			if ( ( jQuery('#further-notices>span').html() - 1 ) > 0 ) {
				jQuery('#further-notices>span').html( jQuery('#further-notices>span').html() - 1 );
			} else {
				jQuery('#further-notices').remove();
			}
		}
	});
}
