<?php

define( 'SENTINEL_WEB_PAGE_TO_ROOT', '' );
require_once SENTINEL_WEB_PAGE_TO_ROOT . 'sentinel/includes/sentinelPage.inc.php';
require_once SENTINEL_WEB_PAGE_TO_ROOT . 'sentinel/includes/Parsedown.php';

sentinelPageStartup( array( ) );

$page = sentinelPageNewGrab();
$page[ 'title' ]   = 'Instructions' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'instructions';

$docs = array(
	'readme'         => array( 'type' => 'markdown', 'legend' => 'Read Me', 'file' => 'README.md' ),
	'license'        => array( 'type' => 'markdown', 'legend' => 'License', 'file' => 'LICENSE' ),
);

$selectedDocId = isset( $_GET[ 'doc' ] ) ? $_GET[ 'doc' ] : '';
if( !array_key_exists( $selectedDocId, $docs ) ) {
	$selectedDocId = 'readme';
}
$readFile = $docs[ $selectedDocId ][ 'file' ];

$instructions = file_get_contents( SENTINEL_WEB_PAGE_TO_ROOT.$readFile );

if ($docs[ $selectedDocId ]['type'] == "markdown") {
	$parsedown = new ParseDown();
	$instructions = $parsedown->text($instructions);
}

/*
function urlReplace( $matches ) {
	return sentinelExternalLinkUrlGet( $matches[1] );
}

// Make links and obfuscate the referer...
$instructions = preg_replace_callback(
	'/((http|https|ftp):\/\/([[:alnum:]|.|\/|?|=]+))/',
	'urlReplace',
	$instructions
);

$instructions = nl2br( $instructions );
*/
$docMenuHtml = '';
foreach( array_keys( $docs ) as $docId ) {
	$selectedClass = ( $docId == $selectedDocId ) ? ' selected' : '';
	$docMenuHtml  .= "<span class=\"submenu_item{$selectedClass}\"><a href=\"?doc={$docId}\">{$docs[$docId]['legend']}</a></span>";
}
$docMenuHtml = "<div class=\"submenu\">{$docMenuHtml}</div>";

$page[ 'body' ] .= "
<div class=\"body_padded\">
	<h1>Instructions</h1>

	{$docMenuHtml}

		{$instructions}
	
</div>";

sentinelHtmlEcho( $page );

?>
