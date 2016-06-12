/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.skin = 'bootstrapck';
	config.toolbar_Full = [
    
    // On the basic preset, clipboard and undo is handled by keyboard.
    // Uncomment the following line to enable them on the toolbar as well.
    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo' ] },
    
    { name: 'insert', items: [ 'CreatePlaceholder', 'Image', /*'Flash',*/ 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'/*, 'PageBreak', 'Iframe', 'InsertPre'*/ ] },
    /*{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },*/
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'RemoveFormat' ] },
    { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', /*'CreateDiv',*/ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'/*, 'BidiLtr', 'BidiRtl' */] },
    { name: 'links', items: [ 'Link', 'Unlink'/*, 'Anchor'*/ ] },
    /*'/',*/
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'tools', items: [ 'UIColor', 'Maximize', 'ShowBlocks' ] },
	{ name: 'editing',     groups: [ 'find', 'selection'/*, 'spellchecker'*/ ], items: [ 'Find', 'Replace', 'SelectAll'/*, 'Scayt' */] },
	{ name: 'document',    groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', /*'Save', 'NewPage', 'DocProps',*/ 'Preview', 'Print', /*'Templates',*/ 'document' ] },
    { name: 'about', items: [ 'About' ] }
  ];

  config.toolbar = "Full";
  config.toolbarCanCollapse = true;
};
