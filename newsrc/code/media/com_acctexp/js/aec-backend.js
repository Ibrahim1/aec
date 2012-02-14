jQuery(document).ready(function($) {
	jQuery('#topbar').dropdown();
	jQuery(".collapse").collapse({toggle: false, selector: '.aecadminform'}).on('show', function(e){
		jQuery(this).parent('.accordion-group').parent('.accordion').children('.accordion-group').children('.collapse').collapse('hide');
	});
	jQuery("#system-message").fadeOut('slow', function() { jQuery(this).slideUp( 'slow' ); });
	jQuery('#quicksearch').popover({ trigger:'manual', placement:'bottom' });

	var typingTimer;

	jQuery("#quicksearch").on({
		'keypress' : function(e) { if (e.keyCode == 13) return false; },
		'keyup' : function(e) { clearTimeout(typingTimer); inputString = this.value; typingTimer = setTimeout(lookup, "300"); },
		'keydown' : function(e) { jQuery('.popover .popover-content p').html("Searching..."); },
		'focusin' : function(e) {
			jQuery(this).popover('show');
			
			if ( this.value != "" ) {
				inputString = this.value;

				jQuery('.popover .popover-content p').html("Searching...");

				typingTimer = setTimeout(lookup, "100");
			}
		},
		'focusout' : function(e) { jQuery("div.popover").fadeOut(); }
	});

	jQuery('form#adminForm').one('focusin', function() {
		jQuery('div.aec-buttons a.btn').attr("disabled", false);
	});

	jQuery('label.toggleswitch').one('click', function() {
		jQuery('div.aec-buttons a.btn').attr("disabled", false);
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

	jQuery('#aecmenu-help').popover({ trigger:'manual', placement:'below' });

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
	jQuery('table.aecadminform').tooltip({placement: "bottom", selector: 'a.btn', delay: { show: 300, hide: 100 }});
	jQuery('div.control-group').tooltip({placement: "right", selector: '.bstooltip', delay: { show: 300, hide: 400 }});
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

function toggleProperty(type, property, id, callerid) {
	jQuery('#'+callerid+' i').addClass('bsicon-refresh');
	jQuery('#'+callerid+' i').removeClass('bsicon-remove').removeClass('bsicon-ok').removeClass('bsicon-eye-open');
	jQuery('#'+callerid+' i').addClass('bsicon-rotate');

	if ( jQuery('#'+callerid).hasClass('btn-toggle-danger') ) {
		jQuery('#'+callerid).removeClass('btn-toggle-danger').addClass('btn-toggle-warning');
	} else {
		jQuery('#'+callerid).removeClass('btn-toggle-success').addClass('btn-toggle-warning');
	}

	jQuery('#'+callerid+' i').addClass('bsicon-refresh').removeClass('bsicon-eye-open');

	jQuery.post("index.php?option=com_acctexp&task=toggleAjax&type="+type+"&property="+property+"&id="+id , {queryString: ""}, function(data) {
		jQuery('#'+callerid).removeClass('btn-toggle-warning');

		if ( data == "1" ) {
			jQuery('#'+callerid).addClass('btn-toggle-success');

			if ( property == 'visible' ) {
				jQuery('#'+callerid+' i').addClass('bsicon-eye-open').removeClass('bsicon-refresh').removeClass('bsicon-rotate');
			} else {
				jQuery('#'+callerid+' i').addClass('bsicon-ok').removeClass('bsicon-refresh').removeClass('bsicon-rotate');
			}
		} else {
			jQuery('#'+callerid).addClass('btn-toggle-danger');

			jQuery('#'+callerid+' i').addClass('bsicon-remove').removeClass('bsicon-refresh').removeClass('bsicon-rotate');
		}
	});
}

function addGroup(type, callerid) {
	jQuery('#'+callerid+' i').attr('disabled','disabled').addClass('bsicon-rotate');

	group = jQuery('select#add_group').val();
	id = jQuery('input:hidden[name=id]').val();

	jQuery.post("index.php?option=com_acctexp&task=addGroupAjax&type="+type+"&group="+group+"&id="+id , {queryString: ""}, function(data) {
		alert(group);

		if ( data == "0" ) {
			
		} else if ( data.length < 500 ) {
			jQuery('#'+callerid+' i').removeAttr('disabled').removeClass('bsicon-rotate');

			jQuery('select#add_group option[value=\''+id+'\']').remove();
			
			jQuery('.aec-grouplist tbody tr:last').after(data);
		} else {
		}
	});
}