jQuery(document).ready(function($) {
	var typingTimer;

	jQuery('#topbar').dropdown();
	jQuery("#system-message").fadeOut('slow', function() { jQuery(this).slideUp( 'slow' ); });
	jQuery('#quicksearch').popover({
		trigger:'manual',
		placement:'below'
	});

	jQuery("#quicksearch")
	.on("keypress", function(e) {
		if (e.keyCode == 13) return false;
	}).on("keyup", function(e) {
		clearTimeout(typingTimer);

		inputString = this.value;

		typingTimer = setTimeout(lookup, "300");
	}).on("keydown", function(e) {
		jQuery('.popover .content p').html("Searching...");
	}).on("focusin", function(e) {
		jQuery(this).popover('show');
	}).on("focusout", function(e) {
		jQuery("div.popover").fadeOut();
	});

	jQuery('form#adminForm input, form#adminForm select').change(function() {
	  jQuery('div.aec-buttons a[disabled="disabled"]').attr("disabled", false);
	});

	jQuery('#aecmenu-help').popover({
		trigger:'manual',
		placement:'below'
	});

	jQuery("#aecmenu-help")
	.on("click", function(e) {
		jQuery('#aecmenu-help').popover('show');

		jQuery.post("index.php?option=com_acctexp&task=getHelp" , {queryString: ""}, function(data) {
			jQuery('.popover .content p').html(data);
		});
	});

	function lookup() {
		jQuery.post("index.php?option=com_acctexp&task=quicksearch&search="+inputString , {queryString: ""+inputString+""}, function(data) {
			jQuery('.popover .content p').html(data);
		});
	}

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
