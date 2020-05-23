var toolsetForms = toolsetForms || {};

var wptCallbacks = {};
wptCallbacks.validationInit = jQuery.Callbacks('unique');
wptCallbacks.addRepetitive = jQuery.Callbacks('unique');
wptCallbacks.removeRepetitive = jQuery.Callbacks('unique');
wptCallbacks.conditionalCheck = jQuery.Callbacks('unique');
wptCallbacks.reset = jQuery.Callbacks('unique');

// General
jQuery( document ).ready( function() {
    if (typeof wptValidation !== 'undefined') {
        wptCallbacks.validationInit.add(function () {
            wptValidation.init();
        });
    }
    if (typeof wptCond !== 'undefined') {
        wptCond.init();
    } else {
        wptCallbacks.validationInit.fire();
    }
} );

// CRED specific
jQuery(document).on('cred_form_ready', function (event, event_data) {

    /**
     * check taxonmies on submitted forms
     */
    jQuery('.cred-taxonomy', jQuery('form.is_submitted')).each(function () {
        var $parent = jQuery(this);
        setTimeout(function () {
            jQuery('input.wpt-taxonomy-add-new', $parent).click();
        }, 50);
    });
	
});

var wptFilters = {};
function add_filter(name, callback, priority, args_num) {
    var args = _.defaults(arguments, ['', '', 10, 2]);
    if (typeof wptFilters[name] === 'undefined')
        wptFilters[name] = {};
    if (typeof wptFilters[name][args[2]] === 'undefined')
        wptFilters[name][args[2]] = [];
    wptFilters[name][args[2]].push([callback, args[3]]);
}
function apply_filters(name, val) {
    if (typeof wptFilters[name] === 'undefined')
        return val;
    var args = _.rest(_.toArray(arguments));
    _.each(wptFilters[name], function (funcs, priority) {
        _.each(funcs, function ($callback) {
            var _args = args.slice(0, $callback[1]);
            args[0] = $callback[0].apply(null, _args);
        });
    });
    return args[0];
}
function add_action(name, callback, priority, args_num) {
    add_filter.apply(null, arguments);
}
function do_action(name) {
    if (typeof wptFilters[name] === 'undefined')
        return false;
    var args = _.rest(_.toArray(arguments));
    _.each(wptFilters[name], function (funcs, priority) {
        _.each(funcs, function ($callback) {
            var _args = args.slice(0, $callback[1]);
            $callback[0].apply(null, _args);
        });
    });
    return true;
}

/**
 * flat taxonomies functions
 */
function showHideMostPopularButton(taxonomy, form) {
    var $button = jQuery('[name="sh_' + taxonomy + '"]', form);
    var $taxonomy_box = jQuery('.shmpt-' + taxonomy, form);
    var $tag_list = $taxonomy_box.find('.js-wpt-taxonomy-popular-add');

    if (!$button.hasClass('js-wpt-taxonomy-popular-show-hide'))
        return true;

    if ($tag_list.length > 0) {
        $button.show();
        return true;
    } else {
        $button.hide();
        return false;
    }
}

jQuery(document).off('click', '.js-wpt-taxonomy-popular-show-hide', null);
jQuery(document).off('click', '.js-wpt-taxonomy-popular-add', null);
jQuery(document).off('click', '.js-wpt-taxonomy-add-new', null);
jQuery(document).off('keypress', '.js-wpt-new-taxonomy-title', null);

jQuery(document).on('click', '.js-wpt-taxonomy-popular-show-hide', function () {
    showHideMostPopularTaxonomy(this);
});

function showHideMostPopularTaxonomy(el) {
    var data_type_output = jQuery(el).data('output');
    var taxonomy = jQuery(el).data('taxonomy');
    var form = jQuery(el).closest('form');
    jQuery('.shmpt-' + taxonomy, form).toggle();

    if (data_type_output == 'bootstrap') {
        var curr = jQuery(el).text();
        if (curr == jQuery(el).data('show-popular-text')) {
            jQuery(el).text(jQuery(el).data('hide-popular-text'), form);
            jQuery(el).addClass('btn-cancel').addClass('dashicons-dismiss').removeClass('dashicons-plus-alt');
        } else {
            jQuery(el).text(jQuery(el).data('show-popular-text'), form);
            jQuery(el).removeClass('btn-cancel').removeClass('dashicons-dismiss').addClass('dashicons-plus-alt');
        }
    } else {
        var curr = jQuery(el).val();
        if (curr == jQuery(el).data('show-popular-text')) {
            jQuery(el).val(jQuery(el).data('hide-popular-text'), form).addClass('btn-cancel');
        } else {
            jQuery(el).val(jQuery(el).data('show-popular-text'), form).removeClass('btn-cancel');
        }
    }
}

jQuery(document).on('click', '.js-wpt-taxonomy-popular-add', function () {
    var $thiz = jQuery(this);
    var taxonomy = $thiz.data('taxonomy');
    var slug = $thiz.data('slug');
    var _name = $thiz.data('name');
    setTaxonomyFromPopular(_name, taxonomy, this);
    return false;
});

function setTaxonomyFromPopular(slug, taxonomy, $el) {
    var $form = jQuery($el).closest('form');
    var tmp_tax = String(slug);
    if (typeof tmp_tax === "undefined" || tmp_tax.trim() == '')
        return;
    var tax = jQuery('input[name=' + taxonomy + ']', $form).val();
    var arr = String(tax).split(',');
    if (jQuery.inArray(tmp_tax, arr) !== -1)
        return;
    var toadd = (tax == '') ? tmp_tax : tax + ',' + tmp_tax;
    jQuery('input[name=' + taxonomy + ']', $form).val(toadd);
    updateTaxonomies(taxonomy, $form);
}

function addTaxonomy(slug, taxonomy, $el) {
    var $form = jQuery($el).closest('form');
    var curr = jQuery('input[name=tmp_' + taxonomy + ']', $form).val().trim();
    if ('' == curr) {
        jQuery('input[name=tmp_' + taxonomy + ']', $form).val(slug);
        setTaxonomy(taxonomy, $el);
    } else {
        if (curr.indexOf(slug) == -1) {
            jQuery('input[name=tmp_' + taxonomy + ']', $form).val(curr + ',' + slug);
            setTaxonomy(taxonomy, $el);
        }
    }
    jQuery('input[name=tmp_' + taxonomy + ']', $form).val('');
}

jQuery(document).on('click', '.js-wpt-taxonomy-add-new', function () {
    var $thiz = jQuery(this),
        taxonomy = $thiz.data('taxonomy');
    setTaxonomy(taxonomy, this);
});

jQuery(document).on('keypress', '.js-wpt-new-taxonomy-title', function (e) {
    if (13 === e.keyCode) {
        e.preventDefault();
        var $thiz = jQuery(this),
            taxonomy = $thiz.data('taxonomy'),
            taxtype = $thiz.data('taxtype');
        if (taxtype == 'hierarchical') {
            toolsetForms.cred_tax.add_taxonomy(taxonomy, this);
        } else {
            setTaxonomy(taxonomy, this);
        }
    }
});

function setTaxonomy(taxonomy, $el) {
    var $form = jQuery($el).closest('form');
    var tmp_tax = jQuery('input[name=tmp_' + taxonomy + ']', $form).val();
    var rex = /<\/?(a|abbr|acronym|address|applet|area|article|aside|audio|b|base|basefont|bdi|bdo|bgsound|big|blink|blockquote|body|br|button|canvas|caption|center|cite|code|col|colgroup|data|datalist|dd|del|details|dfn|dir|div|dl|dt|em|embed|fieldset|figcaption|figure|font|footer|form|frame|frameset|h1|h2|h3|h4|h5|h6|head|header|hgroup|hr|html|i|iframe|img|input|ins|isindex|kbd|keygen|label|legend|li|link|listing|main|map|mark|marquee|menu|menuitem|meta|meter|nav|nobr|noframes|noscript|object|ol|optgroup|option|output|p|param|plaintext|pre|progress|q|rp|rt|ruby|s|samp|script|section|select|small|source|spacer|span|strike|strong|style|sub|summary|sup|table|tbody|td|textarea|tfoot|th|thead|time|title|tr|track|tt|u|ul|var|video|wbr|xmp)\b[^<>]*>/ig;
    tmp_tax = _.escape(tmp_tax.replace(rex, "")).trim();
    if (tmp_tax.trim() == '') {
        return;
    }
    var tax = jQuery('input[name=' + taxonomy + ']', $form).val();
    var arr = tax.split(',');
    if (jQuery.inArray(tmp_tax, arr) !== -1)
        return;
    var toadd = (tax == '') ? tmp_tax : tax + ',' + tmp_tax;
    jQuery('input[name=' + taxonomy + ']', $form).val(toadd);
    jQuery('input[name=tmp_' + taxonomy + ']', $form).val('');
    updateTaxonomies(taxonomy, $form);
}

function updateTaxonomies(taxonomy, $form) {
    var $taxonomies_selector = jQuery('input[name=' + taxonomy + ']', $form);
    var taxonomies = $taxonomies_selector.val();
    jQuery('div.tagchecklist-' + taxonomy, $form).html('');
    if (!taxonomies || (taxonomies && taxonomies.trim() == '')) {
        return;
    }

    var toshow = taxonomies.split(',');
    var str = '';
    for (var i = 0; i < toshow.length; i++) {
        var sh = toshow[i].trim();
        if ($taxonomies_selector.data('output') == 'bootstrap') {
            str += '<a class=\'label label-default dashicons-before dashicons-no\' data-wpcf-i=\'' + i + '\' id=\'post_tag-check-num-' + i + '\'>' + sh + '</a> ';
        } else {
            str += '<span><a href="#" class=\'ntdelbutton\' data-wpcf-i=\'' + i + '\' id=\'post_tag-check-num-' + i + '\'>X</a>&nbsp;' + sh + '</span>';
        }
    }
    jQuery('div.tagchecklist-' + taxonomy, $form).html(str);
    jQuery('div.tagchecklist-' + taxonomy + ' a', $form).bind('click', function () {
        jQuery('input[name=' + taxonomy + ']', $form).val('');
        var del = jQuery(this).data('wpcf-i');
        var values = '';
        for (i = 0; i < toshow.length; i++) {
            if (del == i) {
                continue;
            }
            if (values) {
                values += ',';
            }
            values += toshow[i];
        }
        jQuery('input[name=' + taxonomy + ']', $form).val(values);
        updateTaxonomies(taxonomy, $form);

        return false;
    });

}

function initTaxonomies(values, taxonomy, url, fieldId) {
    var $form = jQuery('#' + fieldId.replace(/_field_\d+$/, '')).closest('form');
    jQuery('div.tagchecklist-' + taxonomy, $form).html(values);
    jQuery('input[name=' + taxonomy + ']', $form).val(values);
    updateTaxonomies(taxonomy, $form);

    jQuery('input[name=tmp_' + taxonomy + ']', $form).suggest(
        wptoolset_taxonomy_settings.ajaxurl + '?action=wpt_suggest_taxonomy_term&taxonomy=' + taxonomy,
        {
            resultsClass: 'wpt-suggest-taxonomy-term',
            selectClass: 'wpt-suggest-taxonomy-term-select'
        }
    );

    if (jQuery('input[name=tmp_' + taxonomy + ']', $form).val() !== "") {
        jQuery("input[name='new_tax_button_" + taxonomy + "']", $form).trigger("click");
    }
}

// @bug This does not belong here: move to the CRED frontend script or write its own
// and make sure it includs the taxonomy initialization pseudo-mini-script around here
toolsetForms.CRED_taxonomy = function () {

    var self = this;

    self.init = function () {
        self._new_taxonomy = new Array();
        jQuery(document).ready(self._document_ready);
    };

    self._document_ready = function () {
        self._initialize_taxonomy_buttons();
        self._initialize_hierachical();
    };

	/**
	 * Initialize hierarchical taxonomis on a form.
	 *
	 * The taxonomy field itself will add, hidden, the structure to add a new term, and the button to show/hide it.
	 * Here, we just take those structures and move them to the specific shortcode placeholder output, if any, 
	 * or remove them otherwise.
	 *
	 * @since unknown
	 * @since 1.9.1 Make the structure be moved to the placeholder.
	 */
    self._initialize_hierachical = function () {
		jQuery( '.js-wpt-hierarchical-taxonomy-add-new-container' ).each( function() {
			
			var $addNewContainer = jQuery( this ),
				$form = $addNewContainer.closest( 'form' ),
				$taxonomy = $addNewContainer.data( 'taxonomy' ),
				$addNewShowHide = jQuery( '.js-wpt-hierarchical-taxonomy-add-new-show-hide[data-taxonomy="' + $taxonomy + '"]', $form ),
				$placeholder = jQuery( '.js-taxonomy-hierarchical-button-placeholder[data-taxonomy="' + $taxonomy + '"]', $form );
			
			if ( $placeholder.length > 0 ) {
				$addNewShowHide
					.insertAfter( $placeholder )
					.show();
				$placeholder.replaceWith( $addNewContainer );
				self._fill_parent_drop_down( $form );
			} else {
				$addNewContainer.remove();
				$addNewShowHide.remove();
			}
			
		});
    };

	/**
	 * Fill hierarchical taxonomy parent select dropdown.
	 *
	 * @param object $form
	 *
	 * @since unknown
	 * @since 1.9.1 Add a $form paramete to only initialize parent selectors for hierarchical taxonomies on a given form.
	 */
    self._fill_parent_drop_down = function ( $form ) {
        jQuery('select.js-taxonomy-parent', $form ).each(function () {
            var $select = jQuery(this);

            // remove all the options
            jQuery(this).find('option').each(function () {
                if (jQuery(this).val() != '-1') {
                    jQuery(this).remove();
                }
            });

            var taxonomy = jQuery(this).data('taxonomy');

            // Copy all the checkbox values if it's checkbox mode
            jQuery('input[name="' + taxonomy + '\[\]"]', $form).each(function () {
                var id = jQuery(this).attr('id');
                var label = jQuery(this).data('value');
                var level = jQuery(this).closest('ul').data('level');
                var prefix = '';
                if (level) {
                    prefix = "\xA0\xA0" + Array(level).join("\xA0\xA0");
                }
                $select.append('<option value="' + jQuery(this).val() + '">' + prefix + label + '</option>');
            });

            // Copy all the select option values if it's select mode
            jQuery('select[name="' + taxonomy + '\[\]"]', $form).find('option').each(function () {
                var id = jQuery(this).val();
                var text = jQuery(this).text();
                $select.append('<option value="' + id + '">' + text + '</option>');
            });
        });
    };

    self._initialize_taxonomy_buttons = function () {
        // replace the taxonomy button placeholders with the actual buttons.
        jQuery('.js-taxonomy-button-placeholder').each(function () {
            var $placeholder = jQuery(this);
            var label = jQuery(this).attr('data-label');
            var taxonomy = jQuery(this).data('taxonomy');
            var form = jQuery(this).closest('form');
            var $buttons = jQuery('[name="sh_' + taxonomy + '"]', form);
            var selectors = [];

            if ($buttons.length) {

                $buttons.each(function () {
                    var $button = jQuery(this, form);

                    if (label) {
                        $button.val(label);
                    }

                    $placeholder.replaceWith($button);

                    if ($button.hasClass('js-wpt-taxonomy-popular-show-hide')) {
                        if (showHideMostPopularButton(taxonomy, form)) {
                            $button.show();
                        }
                    } else {
                        $button.show();
                    }

                    //Move anything else that should be moved with the button
                    //changed selector
                    selectors.push($button.data('after-selector'));
                });
            }
        });
    };

    self.add_new_show_hide = function (taxonomy, $button) {
        var $form = jQuery($button).closest('form');
        var $add_wrap = jQuery(".js-wpt-hierarchical-taxonomy-add-new-" + taxonomy, $form);
        if ($add_wrap.is(":visible")) {
            $add_wrap.hide();
        } else {
            $add_wrap.show();
        }
        self.hide_parent_button_if_no_terms(taxonomy, $button);
    };

    self.add_taxonomy_controls_bindings = function () {
        jQuery('.js-wpt-hierarchical-taxonomy-add-new').on('click', function () {
            var $thiz = jQuery(this),
                taxonomy = $thiz.data('taxonomy');
            self.add_taxonomy(taxonomy, this);
        });

        jQuery('.js-wpt-hierarchical-taxonomy-add-new-show-hide').on('click', function () {
			var $button = jQuery( this ),
				$taxonomy = $button.data( 'taxonomy' ),
				$output = $button.data( 'output' );
			if ( $output == 'bootstrap' ) {
				// Dealing with an anchor button
				if ( $button.text() == $button.data( 'close' ) ) {
					$button
						.html( $button.data('open') )
						.removeClass('dashicons-dismiss')
						.addClass('dashicons-plus-alt');
				} else {
					$button
						.html( $button.data('close') )
						.removeClass('dashicons-plus-alt')
						.addClass('dashicons-dismiss');
				}
			} else {
				// Dealing with an input button
				if ( $button.val() == $button.data( 'close' ) ) {
					$button
						.val( $button.data('open') )
						.removeClass('btn-cancel');
				} else {
					$button
						.val( $button.data('close') )
						.addClass('btn-cancel');
				}
			}
            self.add_new_show_hide( $taxonomy, this );
        });
    };

    self.terms_exist = function (taxonomy, $button) {
        var form = jQuery($button).closest('form');
        var build_what = jQuery($button).data('build_what'),
            parent = jQuery('[name="new_tax_select_' + taxonomy + '"]', form).val();
        if (build_what === 'checkboxes') {
            var first_checkbox = jQuery('input[name="' + taxonomy + '\[\]"][data-parent="' + parent + '"]:first', form);
            return first_checkbox.length > 0;
        } else {
            var first_option = jQuery('select[name="' + taxonomy + '\[\]"]', form).find('option[data-parent="' + parent + '"]:first');
            return first_option.length > 0;
        }
    };

    self.hide_parent_button_if_no_terms = function (taxonomy, $button) {
        var $form = jQuery($button).closest('form');
        //var form_id = form.attr('id');
        var number_of_options = [];
        jQuery('[name="new_tax_select_' + taxonomy + '"] option', $form).each(function () {
            number_of_options++;
        });

        if (number_of_options > 1) {
            jQuery('[name="new_tax_select_' + taxonomy + '"]', $form).prop('disabled', false);
        } else {
            jQuery('[name="new_tax_select_' + taxonomy + '"]', $form).prop('disabled', true);
        }
    };

    self.add_taxonomy = function (taxonomy, $button) {
        var $form = jQuery($button).closest('form');
        var dataTypeOutput = jQuery($button).data('output');
        var isBootstrap = ('bootstrap' === dataTypeOutput);
        var new_taxonomy = jQuery('[name="new_tax_text_' + taxonomy + '"]', $form).val();
        var build_what = jQuery($button).data('build_what');
        new_taxonomy = new_taxonomy.trim();

        if (new_taxonomy === '') {
            return;
        }

        // make sure we don't already have a taxonomy with the same name.
        var exists = false;
        jQuery('input[name="' + taxonomy + '\[\]"]').each(function () {
            var id = jQuery(this).attr('id');
            var label = jQuery(this).data('value');
            if (new_taxonomy === label) {
                exists = true;
                self._flash_it(jQuery(this).parent('label'));
            }
        });

        jQuery('select[name="' + taxonomy + '\[\]"]', $form).find('option').each(function () {
            if (new_taxonomy === jQuery(this).text()) {
                exists = true;
                self._flash_it(jQuery(this));
            }
        });

        if (exists) {
            jQuery('[name="new_tax_text_' + taxonomy + '"]', $form).val('');
            return;
        }

        var parent = jQuery('[name="new_tax_select_' + taxonomy + '"]', $form).val(),
            add_position = null,
            add_before = true,
            $div_fields_wrap = jQuery('div[data-item_name="taxonomyhierarchical-' + taxonomy + '"]', $form),
            level = 0;

        if (build_what === 'checkboxes') {
            //Fix add new leaf
            jQuery('div[data-item_name="taxonomyhierarchical-' + taxonomy + '"] li input[type=checkbox]', $form).each(function () {
                if (this.value == parent || this.value == new_taxonomy) {
                    $div_fields_wrap = jQuery(this).parent();
                }
            });

            var new_checkbox = "";
            if (isBootstrap) {
                new_checkbox = '<li class="checkbox"><label class="wpt-form-label wpt-form-checkbox-label"><input data-parent="' + parent + '" class="wpt-form-checkbox form-checkbox checkbox" type="checkbox" name="' + taxonomy + '[]" data-value="' + new_taxonomy + '" checked="checked" value="' + new_taxonomy + '"></input>' + new_taxonomy + '</label></li>';
            } else {
                new_checkbox = '<li><input data-parent="' + parent + '" class="wpt-form-checkbox form-checkbox checkbox" type="checkbox" name="' + taxonomy + '[]" checked="checked" value="' + new_taxonomy + '"></input><label class="wpt-form-label wpt-form-checkbox-label">' + new_taxonomy + '</label></li>';
            }
            // find the first checkbox sharing parent
            var $first_checkbox = jQuery('input[name="' + taxonomy + '\[\]"][data-parent="' + parent + '"]:first', $form);
            if ($first_checkbox.length == 0) {
                // there are no existing brothers
                // so we need to compose the ul wrapper and append to the parent li
                //add_position = jQuery('input[name="' + taxonomy + '\[\]"][value="' + parent + '"]').closest('li');
                level = jQuery('input[name="' + taxonomy + '\[\]"][value="' + parent + '"]', $form).closest('ul').data('level');
                level++;
                new_checkbox = '<ul class="wpt-form-set-children" data-level="' + level + '">' + new_checkbox + '</ul>';
                //first_checkbox = ;
                //add_before = false;
                //add_position = jQuery('input[name="' + taxonomy + '\[\]"][value="' + parent + '"]').closest('li');
                if (isBootstrap) {
                    jQuery(new_checkbox).insertAfter($div_fields_wrap);
                } else {
                    jQuery(new_checkbox).appendTo($div_fields_wrap);
                }
            } else {
                // there are brothers
                // so we need to insert before all of them
                add_position = $first_checkbox.closest('li');
                jQuery(new_checkbox).insertBefore(add_position);
            }
            jQuery('[name="new_tax_select_' + taxonomy + '"]', $form).show();
        } else if (build_what === 'select') {
            // Select control

            jQuery('select[name="' + taxonomy + '\[\]"]', $form).show();

            var label = '';
            var indent = '';
            var $first_option = jQuery('select[name="' + taxonomy + '\[\]"]', $form).find('option[data-parent="' + parent + '"]:first', $form);
            if ($first_option.length == 0) {
                // there a no children of this parent
                $first_option = jQuery('select[name="' + taxonomy + '\[\]"]', $form).find('option[value="' + parent + '"]:first', $form);
                add_before = false;
                label = $first_option.text();
                for (var i = 0; i < label.length; i++) {
                    if (label[i] == '\xA0') {
                        indent += '\xA0';
                    } else {
                        break;
                    }
                }
                indent += '\xA0';
                indent += '\xA0';
                add_position = jQuery('select[name="' + taxonomy + '\[\]"]', $form);
            } else {
                add_position = $first_option;
                label = $first_option.text();
                for (var i = 0; i < label.length; i++) {
                    if (label[i] == '\xA0') {
                        indent += '\xA0';
                    } else {
                        break;
                    }
                }
            }

            if (add_position) {
                var new_option = '<option value="' + new_taxonomy + '" selected>' + indent + new_taxonomy + '</option>';
                if (add_before) {
                    jQuery(new_option).insertBefore(add_position);
                } else {
                    jQuery(new_option).appendTo(add_position);
                }
            }
            jQuery('[name="new_tax_select_' + taxonomy + '"]', $form).show()
        }

        self._update_hierachy(taxonomy, new_taxonomy, $form);

        jQuery('[name="new_tax_text_' + taxonomy + '"]', $form).val('');

        self._fill_parent_drop_down( $form );
    };

    self._update_hierachy = function (taxonomy, new_taxonomy, $form) {
        var $new_taxonomy_input = jQuery('input[name="' + taxonomy + '_hierarchy"]', $form);
        if ($new_taxonomy_input.length <= 0) {
            // add a hidden field for the hierarchy
            jQuery('<input name="' + taxonomy + '_hierarchy" style="display:none" type="hidden">').insertAfter(jQuery('[name="new_tax_text_' + taxonomy + '"]', $form));
            $new_taxonomy_input = jQuery('input[name="' + taxonomy + '_hierarchy"]', $form);
        }

        if (typeof self._new_taxonomy[taxonomy] === 'undefined') {
            self._new_taxonomy[taxonomy] = new Array();
        }

        var parent = jQuery('[name="new_tax_select_' + taxonomy + '"]', $form).val();
        self._new_taxonomy[taxonomy].push(parent + ',' + new_taxonomy);

        var value = '';
        for (var i = 0; i < self._new_taxonomy[taxonomy].length; i++) {
            value += '{' + self._new_taxonomy[taxonomy][i] + '}';
        }
        value = $new_taxonomy_input.val() + value;
        $new_taxonomy_input.val(value);
    };

    self._flash_it = function ($element) {
        $element.fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
    };

    self.init();

    setTimeout(self.add_taxonomy_controls_bindings, 300);

};

toolsetForms.cred_tax = new toolsetForms.CRED_taxonomy();

//removed return key press
jQuery(function () {
    var keyStop = {
        8: ":not(input:text, textarea,  input:file, input:password)", // stop backspace = back
        13: "input:text, input:password", // stop enter = submit

        end: null
    };

    jQuery(document).bind("keydown", function (event) {
        var $thiz_selector = keyStop[event.which],
            $thiz_target = jQuery(event.target);

        if (
            $thiz_target.closest("form.cred-form").length
            && $thiz_selector !== undefined
            && $thiz_target.is($thiz_selector)
        ) {
            event.preventDefault(); //stop event
        }

        return true;
    });
});
