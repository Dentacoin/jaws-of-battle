(function() {
    tinymce.create('tinymce.plugins.gurlpb', {
        init : function(ed, url) {
            ed.addButton('gurlpb_edit', {
                title : 'Link/URL preview box',
                cmd : 'gurlpb_edit',
                image : url + '/gurlpb_wordpress-editor-icon.png'
            });

            ed.addCommand('gurlpb_edit', function() {
                if ( document.gurlpbData.noNew == 1 ) {
                     if ( confirm('You reached the maximum number of 4 url preview boxes. Please buy a licence key. Do you want to buy it right now?') ) {
                          document.location.href = document.gurlpbData.adminUrl;                          
                     }
                     return;
                }
                var str = ed.selection.getContent();
                str = str ? str.trim(): '';
                if ( ! str ) str=prompt('URL for URL Preview Box?');
                if ( ! str) return;
                var arr = str.match(/http[^"' <\n\r]*/i);
                var return_text = '';
                if ( ! arr || ! arr.length ) return alert( 'No URL. URL starts with "http"' );
                str = arr[0];
                var shortcode = '[urlpreviewbox url="' + str + '"/]';
                return_text = '<span class="gurlpb">' + shortcode + "</span><br />";
                ed.execCommand('mceInsertContent', 0, return_text);
                try {
                    jQuery.ajax( {"type": "POST", url: "https://guteurls.de/urlpreviewbox-prepare.php", data: 'u=' + JSON.stringify( new Array( str ) ) } );
                } catch ( e) { ; }
            });
        }
    });
    // Register plugin
    tinymce.PluginManager.add( 'gurlpb', tinymce.plugins.gurlpb );
})();