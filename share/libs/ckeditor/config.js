/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
    config.skin = 'bootstrapck';        
    config.toolbar_Full = [
    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo' ] },
    { name: 'insert', items: [ 'CreatePlaceholder', 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'RemoveFormat' ] },
    { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ], items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
    { name: 'links', items: [ 'Link', 'Unlink' ] },
    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
    { name: 'tools', items: [ 'UIColor', 'Maximize', 'ShowBlocks' ] },
    { name: 'editing',     groups: [ 'find', 'selection' ], items: [ 'Find', 'Replace', 'SelectAll' ] },
    { name: 'document',    groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', 'Preview', 'Print', 'document' ] },
    { name: 'about', items: [ 'About' ] }
  ];
 
  config.toolbar = "Full";
  config.toolbarCanCollapse = true;
  config.allowedContent = true;
  CKEDITOR.dtd.$removeEmpty['i'] = false;
  CKEDITOR.dtd.$removeEmpty.i = 0;
  CKEDITOR.dtd.$removeEmpty['span'] = false;
  config.contentsCss = ['../share/libs/font-awesome/css/font-awesome.min.css'];
};