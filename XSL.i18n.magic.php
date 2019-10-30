<?php
/**
 * i18n json files setup $messages. This is for $magicWords
 */
function xsl_magic(&$magicWords, $langCode = 0) {
	$magicWords['xsl'] = array( $langCode, 'xsl' );
	return true;
}

$magicWords = [];

/** English
 * @author Greg Rundlett (GregRundlett)
 */
$magicWords['en'] = [
	'xsl' => [ 0, 'xsl' ],
];
