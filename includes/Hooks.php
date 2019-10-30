<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */


class XSLExtensionHooks {
	
	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * 
	 */
	public static function onParserFirstCallInit ( Parser $parser ) {
		// $parser->setHook( 'xsl', [self::class, 'xsl_render' ] );
		$parser->setHook( 'xsl', [self::class, 'renderExample' ] );
		return true;
	}	


	public static function renderExample( Parser $parser, $param1 = '', $param2 = '', $param3 = '' ) {
		$parser->disableCache();
		
		$options = extractOptions( array_slice(func_get_args(), 1) );
		// The input parameters are wikitext with templates expanded.
		// The output should be wikitext too.
		$output = "param1 is $param1 and param2 is $param2 and param3 is $param3";
  
		return $output;
	 }


	#parser hook callback function
	public static function xsl_render( &$parser, $xsl, $xml, $parse=true, $nocache=false ) {

		if ($nocache) {
			$parser->disableCache();
		}
		return "Hello World";

		$output = XSLExtensionHooks::xsl_transform( $xsl, $xml );
		
		if ($parse == false) {
			return array($output, 'noparse' => true, 'isHTML' => true);
		}

		return $output;
		// to return the contents inline
		// return $parser->insertStripItem( $output, $parser->mStripState );
	}

	public static function xsl_transform( $xsl_path, $xml_path ) {
		$doc = new DOMDocument();
		$xsl = new XSLTProcessor();

		$doc->load($xsl_path);
		$xsl->importStyleSheet($doc);

		$doc->load($xml_path);
		return $xsl->transformToXML($doc);
	}

}

/**
 * Converts an array of values in form [0] => "name=value" into a real
 * associative array in form [name] => value. If no = is provided,
 * true is assumed like this: [name] => true
 *
 * @param array string $options
 * @return array $results
 */
function extractOptions( array $options ) {
	$results = array();

	foreach ( $options as $option ) {
		$pair = explode( '=', $option, 2 );
		if ( count( $pair ) === 2 ) {
			$name = trim( $pair[0] );
			$value = trim( $pair[1] );
			$results[$name] = $value;
		}

		if ( count( $pair ) === 1 ) {
			$name = trim( $pair[0] );
			$results[$name] = true;
		}
	}
	//Now you've got an array that looks like this:
	//  [foo] => "bar"
	//	[apple] => "orange"
	//	[banana] => true

	return $results;
}