/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'ru';
	//config.skin='v2';
config.toolbar = 'Full';
 //config.filebrowserBrowseUrl = '/engine/admin/ajax/elf/php/connector_img.php';
 config.filebrowserBrowseUrl = '/engine/admin/ajax/elf/index_img.php';
config.toolbar_Full =
[
	{ name: 'document', items : [ 'Source' ] },
	{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },

	{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
	'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	{ name: 'insert', items : [ 'Image','Table','HorizontalRule','SpecialChar' ] },
	{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
	{ name: 'colors', items : [ 'TextColor','BGColor' ] },
	{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
	
	
];
 
config.toolbar_Basic =
[
	['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
];
};
