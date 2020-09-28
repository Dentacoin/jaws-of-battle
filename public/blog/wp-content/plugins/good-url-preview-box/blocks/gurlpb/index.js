( function( wp ) {
	/**
	 * Registers a new block provided a unique name and an object defining its behavior.
	 * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
	 */
	var registerBlockType = wp.blocks.registerBlockType;
	/**
	 * Returns a new element of given type. Element is an abstraction layer atop React.
	 * @see https://github.com/WordPress/gutenberg/tree/master/element#element
	 */
	var el = wp.element.createElement;
	/**
	 * Retrieves the translation of text.
	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
	 */
	var __ = wp.i18n.__;

	/**
	 * Every block starts by registering a new block type definition.
	 * @see https://wordpress.org/gutenberg/handbook/block-api/
	 */
	registerBlockType( 'good-url-preview-box/gurlpb', {
		/**
		 * This is the display title for your block, which can be translated with `i18n` functions.
		 * The block inserter will show this name.
		 */
		title: 'URL Preview Box',

		/**
		 * Blocks are grouped into categories to help users browse and discover them.
		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
		 */
		category: 'embed',
        
        attributes: {
            linkedURL: {
                type: 'url'
            }
        },

		/**
		 * Optional block extended support features.
		 */
		supports: {
			// Removes support for an HTML mode.
			html: false,
		},

		/**
		 * The edit function describes the structure of your block in the context of the editor.
		 * This represents what the editor will render when the block is used.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
		 *
		 * @param {Object} [props] Properties passed from the editor.
		 * @return {Element}       Element to render.
		 */
		edit: function( props ) {
            if ( ! document.gurlpbData.cnt ) document.gurlpbData.cnt = 0;            
            var linkedURL = props.attributes.linkedURL;
            var gurlpbId = document.gurlpbData.cnt++;
            var noNew = document.gurlpbData.noNew;
            var hasToken = document.gurlpbData.hasT;
            
            var validURL = function ( params ) {
                p = params.url;
                error = params.error;
                success = params.success;
                
                if ( ! p || p.toLowerCase().indexOf('http') < 0 || p.length>1024 ||  p.toLowerCase().indexOf('.') < 0 ) {
                    (error)( 'Please enter a valid internet adress, which begins with http...' );
                    return;
                }
                
                try {
                    var a  = document.createElement('a');
                    a.href = p;
                    if ( ! a.host || a.host == '' ) {
                        (error)( 'Please enter a valid internet adress.' );
                        return;
                    }
                    (success)();                    
                } catch ( e ) {
                    (error)( 'Please enter a valid internet adress.' );
                }                
            }
            
            //document.gurlpbGutenbergPrompt.exec
            var promptFunction = function() {
                var p = prompt('Internet Address / URL:'); 
                validURL({
                    url: p,
                    error: function( txt ) { alert( txt ); },
                    success: function() {
                        if ( p != '' ) try {
                            jQuery.ajax( {"type": "POST", url: "https://guteurls.de/urlpreviewbox-prepare.php", data: 'u=' + JSON.stringify( new Array( p ) ) } );
                        } catch ( e) { ; }

                        props.setAttributes({ linkedURL: p})    // store it in gutenberg block scope

                        var jqPreview = jQuery('[gurlpb-p-id=' + gurlpbId + ']');
                        jqPreview.text('');
                        jqPreview.css('visibility', 'hidden');
                    }
                });
            }
            
            var viewFunction = function() {
                var jqThisBlock = jQuery('[gurlpb-id=' + gurlpbId + ']');
                var jqPreview = jQuery('[gurlpb-p-id=' + gurlpbId + ']');
                
                jqPreview.text( linkedURL );            
                jqPreview.data('guteurls-url', '');     // guteurls.js uses this to identify what block is ajax loaded from server           
                jqPreview.css('visibility', 'visible');

                // visible Refresh Button
                jqThisBlock.find('button.gurlpbGutenbergButtonRefresh').css('display', 'inline');
                
                // (re)activate the buttons
                jQuery('[gurlpb-id=' + gurlpbId + '] button').removeAttr('disabled');
                
                // Prepage guteurls.js
                document.guteUrls.fUrlMultiple = true; // in case the page has one URL several times, we show them all
                document.guteUrls.strSelector = '[gurlpb-p-id=' + gurlpbId + ']';
                window.setTimeout( document.guteUrls.execute(), 300);
            }
            
            var refreshFunction = function() {
                jQuery('[gurlpb-id=' + gurlpbId + '] button').attr('disabled', 'disabled');
                
                window.setTimeout(function() { 
                    jQuery('[gurlpb-id=' + gurlpbId + '] button').removeAttr('disabled') 
                }, 8000 );
                
                $.ajax( "https://guteurls.de/removeurl-wp.php", {
                    data: {
                        'd': document.gurlpbData.siteUrl,
                        'c': document.gurlpbData.c,
                        'u': props.attributes.linkedURL,
                        'l': document.gurlpbData.l                        
                    },
                    method: 'POST',
                    timeout: 30000, // 1000 ms
                    success: function ( data ) {
                        
                        for ( var key in localStorage ) {
                            if ( key && key.substr( 0, 8 ) == 'guteurls' ) localStorage.removeItem( key );
                        }
                        
                        if ( data && data.indexOf('Found and Cache reseted') ) {
                            alert('Perfect! Url found and Cache reseted');
                            window.setTimeout( function() { jQuery('[gurlpb-p-id=' + gurlpbId + ']').text( "loading in 5" ) }, 1000);
                            window.setTimeout( function() { jQuery('[gurlpb-p-id=' + gurlpbId + ']').text( "loading in 4" ) }, 2000);
                            window.setTimeout( function() { jQuery('[gurlpb-p-id=' + gurlpbId + ']').text( "loading in 3" ) }, 3000);
                            window.setTimeout( function() { jQuery('[gurlpb-p-id=' + gurlpbId + ']').text( "loading in 2" ) }, 4000);
                            window.setTimeout( function() { jQuery('[gurlpb-p-id=' + gurlpbId + ']').text( "loading in 1" ) }, 5000);
                            window.setTimeout( function() { jQuery('[gurlpb-p-id=' + gurlpbId + ']').text( "loading fresh data" ) }, 6000);
                            window.setTimeout( viewFunction, 7000);                            
                        }
                    },
                    error: function () { // will fire when timeout is reached
                        // reactivate the buttons
                        jQuery('[gurlpb-id=' + gurlpbId + '] button').removeAttr('disabled')
                        alert('failed');
                    }
                });
            }
            
            
            
            if ( ! linkedURL || linkedURL == '' ) {
                if ( hasToken || ( ! noNew && jQuery('.gurlpbGutenbergBox').length < 5 ) ) {
                    e = el('div', { className: "gurlpbGutenbergBox" },
                            el('h3', { }, "URL Preview Box"),
                            //el('div', { className: "gurlpbGutenbergUrl" }, linkedURL),
                            el('div', { className: "gurlpbGutenbergHP" }, "guteURLs"),
                            el('button', { onClick: promptFunction }, "Add URL"),
                        );
                } else {
                    e = el('div', { className: "gurlpbGutenbergBox" },
                            el('h3', { }, "URL Preview Box "),
                            el('p', { }, "You reached your limit of maximal 4 URL Preview Boxes."),
                            el('p', { }, "Please purchase a licence key."),
                            el('p', { }, "Starts with €1."),
                            el('a', { 'href': document.gurlpbData.adminUrl }, "Click here to set the key"),
                    );
                }                
            } else {
                e = el('div', { className: "gurlpbGutenbergBox", "gurlpb-id": gurlpbId, "gurlpb-url": linkedURL},
                        el('h3', { }, "URL Preview Box:"),
                        el('div', { className: "gurlpbGutenbergUrl" }, linkedURL),                        
                        el('div', { className: "gurlpbGutenbergHP" }, "guteURLs"),
                        el('button', { onClick: promptFunction}, "Modify URL"),
                        el('button', { onClick: viewFunction}, "View"),
                        el('button', { onClick: refreshFunction, className: "gurlpbGutenbergButtonRefresh" }, "Refresh"),
                        el('div', {  className: 'gurlpbGutenbergPreview',  "gurlpb-p-id": gurlpbId}, linkedURL),
                    );                
            }
            
            return e;
            
		},

		/**
		 * The save function defines the way in which the different attributes should be combined
		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
		 *
		 * @return {Element}       Element to render.
		 */
    save: function(props) {
        return el( 'p', { class: 'wp-block-good-url-preview-box-gurlpb' }, '[urlpreviewbox url="' + props.attributes.linkedURL + '"]' );
    },
        
        
        
	} );
} )(
	window.wp
);
