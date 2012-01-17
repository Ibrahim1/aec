jQuery(document).ready(function($) {
	jQuery('#topbar').dropdown();
	jQuery("#system-message").fadeOut('slow', function() { jQuery(this).slideUp( 'slow' ); });
	jQuery('#quicksearch').popover({
		trigger:'manual',
		placement:'below'
	});
	jQuery("#quicksearch").on("keypress", function(e) {
		if (e.keyCode == 13) return false;
		jQuery('.popover .content p').html("Searching...");
	});
	jQuery("#quicksearch").on("keyup", function(e) {
		lookup(this.value);
	});
	jQuery("#quicksearch").on("focusin", function(e) {
		jQuery(this).popover('show');
	});
	jQuery("#quicksearch").on("focusout", function(e) {
		jQuery("div.popover").hide('slow');
	});

	function lookup(inputString) {
		jQuery.post("index.php?option=com_acctexp&task=quicksearch&search="+inputString , {queryString: ""+inputString+""}, function(data) {
			jQuery('.popover .content p').html(data);
		});
	}
});