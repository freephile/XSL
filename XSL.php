<?php
/*
 * Copyright (c) 2008 Michael Eagar
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights to 
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of 
 * the Software, and to permit persons to whom the Software is furnished to do 
 * so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all 
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, 
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING 
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR 
 * OTHER DEALINGS IN THE SOFTWARE. 
 *
 * XSL MediaWiki extension
 * @ingroup Extensions
 * @author Michael Eagar
 */

//	Make sure mediawiki is installed
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

 
$wgExtensionCredits['parserhook'][] = array(
    'name' => 'XSL',
    'author' => 'Michael Eagar',
    'version' => '1.0 (06.09.2008}',
    'url' => 'http://www.mediawiki.org/wiki/Extension:XSL',
    'description' => 'Performs an XSL transformation'
); 
 
 # Define a setup function
$wgExtensionFunctions[] = 'xsl_setup';
# Add a hook to initialise the magic word
$wgHooks['LanguageGetMagic'][] = 'xsl_magic';
 
#extension hook callback function
function xsl_setup() { 
    global $wgParser;

    # Set a function hook associating the "example" magic word with our function
    $wgParser->setFunctionHook( 'xsl', 'xsl_render' );
    
    return true;
}

/**
 * Needed in MediaWiki >1.8.0 for magic word hooks to work properly
 */
function xsl_magic(&$magicWords, $langCode = 0) {
    $magicWords['xsl'] = array( $langCode, 'xsl' );
	return true;
}
 
#parser hook callback function
function xsl_render( &$parser, $xsl, $xml, $parse=true, $nocache=false ) {
    if ($nocache) {
        $parser->disableCache();
    }

    $output = xsl_transform( $xsl, $xml );
    
    if ($parse == false) {
        return array($output, 'noparse' => true, 'isHTML' => true);
    }
    return $output;
}

function xsl_transform( $xsl_path, $xml_path ) {
    $doc = new DOMDocument();
    $xsl = new XSLTProcessor();

    $doc->load($xsl_path);
    $xsl->importStyleSheet($doc);

    $doc->load($xml_path);
    return $xsl->transformToXML($doc);
}