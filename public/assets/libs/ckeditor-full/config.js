/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.allowedContent = true;
    config.autoParagraph = false;
    config.image_prefillDimensions  = false;
    config.font_defaultLabel = 'Calibri';


    let ckeditor_font_sizes = "";
    for(let i = 0; i < 80; i+=1)    {
        ckeditor_font_sizes+= i + "/" + i + "px;";
    }
    config.fontSize_sizes = ckeditor_font_sizes;
};

CKEDITOR.dtd.$removeEmpty['i'] = false;
CKEDITOR.dtd.$removeEmpty['span'] = false;