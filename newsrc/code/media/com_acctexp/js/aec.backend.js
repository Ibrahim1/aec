jQuery(document).ready(function(jQuery) {
	jQuery.expr[':'].Contains = function(a,i,m){
		return jQuery(a).text().toLowerCase().indexOf(m[3].toLowerCase())>=0;
	};

	jQuery('.aec-navbar').hover(function(){
		if ( jQuery(".navbar-hover-helper").length == 0 ) {
			jQuery('.aec-navbar').addClass("navbar-hover-helper");
			jQuery('.aec-buttons-fixed').addClass("aec-buttons-fixed-extended");

			jQuery('div.navbar-fixed-top').addClass("navbar-fixed-top-override").prepend("<div class=\"head-minify btn btn-inverse\"><i class=\"glyphicon glyphicon-chevron-left\"></i></div>");

			jQuery(".aec-navbar").on( "click", ".head-minify", function(){
				jQuery('.head-minify').remove();
				jQuery('.navbar-fixed-top').removeClass('navbar-fixed-top-override');
				jQuery('.aec-buttons-fixed').removeClass('aec-buttons-fixed-extended');
			});
		}

		}, function(){
			jQuery('.aec-navbar').removeClass("navbar-hover-helper");
		}
	);

	jQuery('#module-status span.multilanguage a').removeClass('modal');

	if ( jQuery("#system-message li").length != 0 ) {
		jQuery("div.container").before('<span class="nav-msg label label-warning">'+jQuery("#system-message li").html()+'</span>')
			.parent().children(".nav-msg").fadeIn(500,function(){jQuery(".nav-msg").addClass("nav-msg-transition");});
	}

	jQuery('#quicksearch').focus(function() {
		jQuery('#quicksearch').popover({ trigger:'manual', placement:'bottom' });
	});

	var typingTimer;

	jQuery("#quicksearch").on({
		'keypress' : function(e) { return e.keyCode != 13; },
		'keyup' : function(e) { clearTimeout(typingTimer); inputString = this.value; typingTimer = setTimeout(lookup, "300"); },
		'keydown' : function(e) { jQuery('.popover .popover-content p').html("Searching..."); },
		'focusin' : function(e) {
			jQuery("#quicksearch").popover();

			if ( this.value != "" ) {
				inputString = this.value;

				jQuery('.popover .popover-content p').html("Searching...");

				typingTimer = setTimeout(lookup, "100");
			}
		},
		'focusout' : function(e) {
			jQuery(this).popover('hide');
		}
	});

	jQuery("#settings-filter").on({
		'keypress' : function(e) { return e.keyCode != 13; },
		'keyup' : function(e) {
			clearTimeout(typingTimer); inputString = this.value; typingTimer = setTimeout(settingsfilter, "100");
		}
	});

	jQuery('a.quicksearch').on("click", function(e) {
		e.preventDefault();

		jQuery("#quicksearch").popover('show');

		jQuery("input#quicksearch").val( this.text ).focus();
	});

	jQuery('select[name="color"]').simplecolorpicker();

	jQuery('a#testexport').on("click", function(e) {
		var values = {};
		jQuery.each(jQuery('#adminForm').serializeArray(), function(i, field) {
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

	// JCE is rather large
	if ( typeof(WFEditor) !== undefined ) {
		if ( jQuery(document).width() < 1350 ) {
			if ( jQuery(document).width() < 1000 ) {
				jQuery(".form-horizontal .form-group > label").css("width","30%");
				jQuery(".form-horizontal .controls").css("margin-left","34%");
			} else {
				jQuery(".form-horizontal .controls").css("margin-left","50%");
			}
		}
	}

	// fix sub nav on scroll - adapted, against better advice, from http://twitter.github.com/bootstrap/less.html
	var tbFixed = 0,
		btFixed = 0,
		navTop = 0,
		navHeight = 0,
		navBtn = 0;

	processScroll();

	jQuery(window).on('scroll', processScroll);

	function processScroll() {
		var i,
			scrollTop = jQuery(window).scrollTop();

		if ( navTop == 0 ) {
			navTop = jQuery('.aec-navbar').offset().top;
		}

		if ( navHeight == 0 ) {
			navHeight = jQuery('.aec-navbar').height();
		}

		if (scrollTop >= navTop && !tbFixed) {
			tbFixed = 1;
			jQuery('.aec-navbar').addClass('navbar-fixed-top');
			jQuery('nav.navbar').addClass('navbar-fixed-top-minified');
			jQuery('#aec-wrap').css('padding-top', navHeight);
		} else if (scrollTop <= navTop && tbFixed) {
			tbFixed = 0;
			jQuery('nav.navbar').removeClass('navbar-fixed-top-minified');
			jQuery('.aec-navbar').removeClass('navbar-fixed-top').removeClass('navbar-fixed-top-override');
			jQuery('div.head-minify').remove();
			jQuery('.aec-buttons-fixed').removeClass("aec-buttons-fixed-extended");
			jQuery('#aec-wrap').css('padding-top', '0');
		}

		if ( jQuery('.aec-buttons').length ) {
			if ( navBtn == 0 ) {
				navBtn = jQuery('.aec-buttons').offset().top + navHeight;
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
			jQuery('.popover .popover-content').html(data);
		});
	}

	function settingsfilter() {
		if ( inputString == "" ) {
			jQuery(".setting-filter-mute, .setting-filter-hide, .setting-filter-hide-override, .affixnav a").removeClass("setting-filter-mute setting-filter-hide setting-filter-hide-override");
			return;
		}

		jQuery(".form-group, section h2, section .accordion, .page-header, section").removeClass("setting-filter-mute setting-filter-hide setting-filter-hide-override");

		jQuery('.form-group').filter( function() {
			var matches = jQuery('label.control-label:Contains("'+inputString+'"), label.control-label[name*="'+inputString+'"]', this).length;
			return matches < 1;
		}).each( function() {
			jQuery(this).addClass("setting-filter-mute");
			jQuery(this).parent("section").addClass("setting-filter-hide");
			jQuery(this).prevAll("h2").first().addClass("setting-filter-hide");
		});

		jQuery('.form-group').filter( function() {
			var matches = jQuery('label.control-label:Contains("'+inputString+'"), label.control-label[name*="'+inputString+'"]', this).length;
			return matches > 0;
		}).each( function() {
			jQuery(this).parent("section").addClass("setting-filter-hide-override")
			jQuery(this).prevAll("h2").first().addClass("setting-filter-hide-override");
		});

		jQuery("section h2:not(.setting-filter-hide-override)").nextUntil("h2", ".form-group").addClass("setting-filter-hide");

		jQuery('.page-header').filter( function() {
			return jQuery(this).nextUntil( '.page-header', 'section.setting-filter-hide-override' ).length < 1;
		}).each( function() {
			jQuery(".affixnav a[href$=\""+this.id+"\"]").addClass("setting-filter-mute");
			jQuery(this).addClass("setting-filter-hide");
		});

		jQuery('.page-header').filter( function() {
			return jQuery(this).nextUntil( '.page-header', 'section.setting-filter-hide-override' ).length > 0;
		}).each( function() {
			jQuery(".affixnav a[href$=\""+this.id+"\"]").removeClass("setting-filter-mute");
			jQuery(this).addClass("setting-filter-hide-override");
		});

	}

	jQuery('div.aec-buttons').tooltip({placement: "bottom", selector: 'a.btn', delay: { show: 300, hide: 100 }});
	jQuery('table.aecadminform').tooltip({placement: "bottom", selector: 'a.btn', delay: { show: 300, hide: 100 }});
	jQuery('div.form-group').tooltip({placement: "right", selector: '.bstooltip', delay: { show: 300, hide: 400 }});

	jQuery('.jqui-datepicker').datepicker({ dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, showWeek: true, showOtherMonths: true, selectOtherMonths: true });
	jQuery('.jqui-datetimepicker').datetimepicker({ dateFormat: 'yy-mm-dd', timeFormat: 'hh:mm:ss', changeMonth: true, changeYear: true, showWeek: true, showOtherMonths: true, selectOtherMonths: true, showSecond: true, hourGrid: 6, minuteGrid: 10, secondGrid: 10  });

	jQuery('#drilldown').menu({
		content: jQuery('#drilldown').next().html(),
		backLink: false,
		width: "260px",
		maxHeight: 286,
		topLinkText: 'Root',
		select: function(item) {
			jQuery('#name').val(jQuery(".mi-menu-mi-name", item).html());
			jQuery('#desc').val(jQuery(".mi-menu-mi-desc", item).html());
			jQuery('#class_name').val(item.hash.slice(1));
			jQuery('a#drilldown').html("Selected: <span class=\"label label-important\">"+jQuery(".mi-menu-mi-name", item).html()+"</span>");
		}
	});

	if ( jQuery('.jqui-daterangepicker').length ) {
		jQuery('.jqui-daterangepicker').daterangepicker({
			latestDate: Date.parse('today'),
			dateFormat: "yy-mm-dd",
			constrainDates: true,
			datepickerOptions: {
				dateFormat: 'yy-mm-dd',
				changeMonth: true,
				changeYear: true,
				showWeek: true,
				showOtherMonths: true,
				selectOtherMonths: true,
				maxDate: Date.parse('today'),
				defaultDate: Date.parse('today'),
				gotoCurrent: true
			},
			onClose:function(){
				var range = jQuery('.jqui-daterangepicker').val();
				var rangestart = range;
				var rangeend = range;

				if ( range.indexOf(" - ") !== -1 ) {
					rangestart = range.slice(0, 10);
					rangeend = range.slice(13);
				}

				cf.range( rangestart+" 00:00:00", rangeend+" 23:59:59")
				.update({ start: rangestart+" 00:00:00", end: rangeend+" 23:59:59"});
			}
		});
	}

	jQuery(".collapse").collapse({toggle: false});

	jQuery(".order-select").on("click", function(event){
		jQuery("input[name*=\'orderby_\']").val(jQuery(this).data('ordering'));
		document.adminForm.submit();
	});

	jQuery(function () {
		jQuery('[data-toggle="tooltip"]').tooltip()
	});

	jQuery('.bootstrap-toggle').bootstrapToggle({
			on: 'yes',
			off: 'no',
			onstyle: 'success',
			offstyle: 'danger',
			size: 'mini'
		}
	);

	var showPopover = jQuery.fn.popover.Constructor.prototype.show;
	jQuery.fn.popover.Constructor.prototype.show = function() {
		showPopover.call(this);
		if (this.options.showCallback) {
			this.options.showCallback.call(this);
		}
	};

	var popovers = jQuery('.popover-markup>.trigger').popover({
		placement:'right',
		html: true,
		title: function() {
			return jQuery(this).parent().find('.head').html();
		},
		content: function() {
			var content = jQuery(this).parent().find('.content' ).clone();

			content.find('select').replaceWith(function() {
				var el = jQuery(this),
					state = jQuery('#'+el.attr('id')).val();

				if ( typeof state != 'undefined' && state != null ) {
					el.find('option').attr("selected", false);

					el.find("option[value='" + state.join("'],option[value='") + "']").attr("selected", "selected");
				} else {
					el.find('option').attr("selected", false);
				}

				return el.parent().html();
			});

			return content.html();
		},
		showCallback: function() {
			jQuery('.popover-content .select2-bootstrap').select2({
				containerCss : {"display":"block"},
				allowClear: true
			});

			jQuery('.popover-content .jqui-multiselect').multiselect({
				noneSelectedText: 'Select',
				selectedList: 8
			});

			// Rename original field so it doesn't overwrite our stuff
			var inputs = jQuery(this.$element).parent().find('.content select');

			inputs.each(function() {
				var el = jQuery(this);

				if ( el.attr('name').substr(7) != 'UNSET__' ) {
					el.attr('name', 'UNSET__' + el.attr('name'));
				}
			});
		}
	});

	popovers.on('hidden.bs.popover', function () {
		var state = jQuery(this).parent().find('.popover select').val(),
			inputs = jQuery(this).parent().find('.content select');

		inputs.each(function() {
			var el = jQuery(this);
			el.attr('name', el.attr('name').substr(7));

			if ( typeof state != 'undefined' && state != null ) {
				el.val(state);
			} else {
				el.val(false);
			}

		});
	})

	jQuery('.jqui-multiselect:not(.content .jqui-multiselect)').multiselect({ noneSelectedText: 'Select', selectedList: 8 });

	jQuery('.select2-bootstrap:not(.content .select2-bootstrap)').select2();

	// Make Select2 selects work in bootstrap modals and popovers
	/*jQuery.fn.modal.Constructor.prototype.enforceFocus = function() {};
	jQuery.fn.popover.Constructor.prototype.enforceFocus = function() {};*/

	if ( jQuery("table.table-selectable").length == 0 ) {
		jQuery('#adminForm').one('click', function() {
			jQuery('div.aec-buttons a.btn-conditional').attr("disabled", false);
		});
	} else {
		var rows_count = jQuery("table.table-selectable tbody tr").length,
			selected_rows = 0,
			select_all = jQuery("table.table-selectable a.select-all");

		// Init checked state
		jQuery("table.table-selectable tbody tr").find("input[type='checkbox']").prop('checked', false);

		var toggleButtons = function() {
			if ( selected_rows ) {
				jQuery('div.aec-buttons a.btn-conditional').attr("disabled", false);

				if ( selected_rows == rows_count ) {
					select_all.removeClass('btn-success').addClass('btn-warning');
				} else {
					select_all.removeClass('btn-warning').addClass('btn-success');
				}
			} else {
				jQuery('div.aec-buttons a.btn-conditional').attr("disabled", true);

				select_all.removeClass('btn-warning').addClass('btn-success');
			}
		};

		var selectRow = function(row) {
			var el = jQuery(row).parent();

			var checkbox = el.find("input[type='checkbox']");

			if ( checkbox.prop('checked') ) {
				el.removeClass("success");

				checkbox.prop('checked', false);

				selected_rows--;
			} else {
				el.addClass("success");

				checkbox.prop('checked', true);

				selected_rows++;
			}

			toggleButtons();
		};

		jQuery("table.table-selectable tbody tr td").on("click", function(event){
			selectRow(this);
		}).children().click(function(e) {
			var el = jQuery(this);

			// Prevent all other actions except links
			if ( el.prop('tagName') != 'A' || el.hasClass('btn') ) {
				return false;
			}
		});

		select_all.on("click", function(event){
			if ( select_all.hasClass('btn-success') ) {
				jQuery("table.table-selectable tbody tr" ).each(function(){
					if ( !jQuery(this).find("input[type*='checkbox']").prop('checked') ) {
						jQuery(this).addClass("success").find("input[type*='checkbox']").prop('checked', true);
					}
				});

				select_all.removeClass('btn-success').addClass('btn-warning');

				selected_rows = rows_count;
			} else {
				jQuery("table.table-selectable tbody tr" ).each(function(){
					if ( jQuery(this).find("input[type*='checkbox']").prop('checked') ) {
						jQuery(this).removeClass("success").find("input[type*='checkbox']").prop('checked', false);
					}
				});

				select_all.removeClass('btn-warning').addClass('btn-success');

				selected_rows = 0;
			}

			toggleButtons();
		});

		var searchTimer;

		function searchDelToggle() {
			if ( jQuery("#aec-listsearch input[type*='text']").val() ) {
				jQuery("#searchclear").removeClass('disabled');
			} else {
				jQuery("#searchclear").addClass('disabled')
			}
		}

		searchDelToggle();

		jQuery("#aec-listsearch input").on({
			'keyup' : function(e) {
				clearTimeout(searchTimer);

				searchTimer = setTimeout(searchDelToggle, "25");
			}
		});

		jQuery("#searchclear").click(function(){
			jQuery("#aec-listsearch input[type*='text']").val('');

			jQuery("#searchclear").addClass('disabled');

			if ( jQuery("#aec-listsearch input[type*='hidden']").val() ) {
				jQuery("#adminForm").submit();
			}
		});

	}

	// Fixing issue with older jQuery and bootstrap.js in FF
	// See https://github.com/twbs/bootstrap/issues/10044
	if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
		document._oldGetElementById = document.getElementById;
		document.getElementById = function(id) {

			if(id === undefined || id === null || id === '') {

				return undefined;

			}

			return document._oldGetElementById(id);
		};

	}
});

function readNotice(id) {
	jQuery.post("index.php?option=com_acctexp&task=readNoticeAjax&id="+id , {queryString: ""}, function(data) {
		jQuery('#alert-'+id).remove();

		jQuery.post("index.php?option=com_acctexp&task=getNotice" , {queryString: ""}, function(data) {
			jQuery('#aec-alertlist').append(data);
		});

		var notices = jQuery('#further-notices>span');

		if ( notices.html() ) {
			if ( ( notices.html() - 1 ) > 0 ) {
				notices.html( notices.html() - 1 );
			} else {
				jQuery('#further-notices').remove();
			}
		}
	});
}

function readNotices() {
	jQuery.post("index.php?option=com_acctexp&task=readNoticesAjax" , {queryString: ""}, function(data) {
		jQuery('#notifications').modal('hide');
		jQuery('a#aecmenu-notifications').remove();
	});
}

function toggleProperty(type, property, id, callerid, callerclass) {
	var idelement = jQuery('#'+callerid);

	if ( idelement.hasClass('ui-disabled') ) {
		return;
	}

	var classelement = jQuery('.'+callerclass+' i' ),
		classidelement = jQuery('#'+callerid+' i');

	if ( property == 'default' ) {
		classelement.addClass('glyphicon-refresh');
		classelement.removeClass('glyphicon-remove glyphicon-star-empty glyphicon-ok glyphicon-eye-open glyphicon-star');
		classelement.addClass('glyphicon-rotate');
	} else {
		classidelement.addClass('glyphicon-refresh');
		classidelement.removeClass('glyphicon-remove glyphicon-stop glyphicon-ok glyphicon-eye-open glyphicon-star');
		classidelement.addClass('glyphicon-rotate');
	}

	if ( idelement.hasClass('btn-danger') ) {
		idelement.removeClass('btn-danger').addClass('btn-warning');
	} else {
		idelement.removeClass('btn-success').addClass('btn-warning');
	}

	classidelement.addClass('glyphicon-refresh').removeClass('glyphicon-eye-open');

	jQuery.post("index.php?option=com_acctexp&task=toggleAjax&type="+type+"&property="+property+"&id="+id , {queryString: ""}, function(data) {
		idelement.removeClass('btn-warning');

		if ( data == "1" ) {
			idelement.addClass('btn-success');

			if ( property == 'default' ) {
				classidelement.addClass('glyphicon-star').removeClass('glyphicon-refresh').removeClass('glyphicon-rotate');
				idelement.attr('disabled','disabled').addClass('ui-disabled');
				jQuery('.'+callerclass+':not(#'+callerid+') i').addClass('glyphicon-star-empty').removeClass('glyphicon-refresh').removeClass('glyphicon-rotate');
				jQuery('.'+callerclass+':not(#'+callerid+')').removeAttr('disabled').removeClass('ui-disabled').removeClass('btn-success').addClass('btn-danger');
			} else if ( property == 'visible' ) {
				classidelement.addClass('glyphicon-eye-open').removeClass('glyphicon-refresh').removeClass('glyphicon-rotate');
			} else {
				classidelement.addClass('glyphicon-ok').removeClass('glyphicon-refresh').removeClass('glyphicon-rotate');
			}
		} else {
			idelement.addClass('btn-danger');

			classidelement.addClass('glyphicon-remove').removeClass('glyphicon-refresh').removeClass('glyphicon-rotate');
		}
	});
}

function addGroup(type, callerid) {
	group = jQuery('select#add_group').val();
	id = jQuery('input:hidden[name=id]').val();

	if ( group > 0 ) {
		jQuery('#'+callerid).attr('disabled','disabled');
		jQuery('#'+callerid+' i').addClass('glyphicon-rotate');

		jQuery.post("index.php?option=com_acctexp&task=addGroupAjax&type="+type+"&group="+group+"&id="+id , {queryString: ""}, function(data) {
			if ( data == "0" ) {

			} else if ( data.length < 500 ) {
				jQuery('#'+callerid+' i').removeClass('glyphicon-rotate');

				jQuery('select#add_group option[value=\''+group+'\']').attr('disabled','disabled');

				jQuery('.aec-grouplist tbody').append(data);
			}

			jQuery('#'+callerid).removeAttr('disabled');
			jQuery('select#add_group').val("0")
		});
	}
}

function removeGroup(type, group, callerid) {
	if ( group > 0 ) {
		jQuery('#'+callerid).attr('disabled','disabled');
		jQuery('#'+callerid+' i').addClass('glyphicon-rotate');

		id = jQuery('input:hidden[name=id]').val();

		jQuery.post("index.php?option=com_acctexp&task=removeGroupAjax&type="+type+"&group="+group+"&id="+id , {queryString: ""}, function(data) {
			if ( data == "1" ) {
				jQuery('#'+callerid+' i').removeAttr('disabled').removeClass('glyphicon-rotate');

				jQuery('select#add_group option[value=\''+group+'\']').removeAttr('disabled');

				jQuery('#row-group-'+group).remove();
			}

			jQuery('select#add_group').val("0")
		});
	}
}
