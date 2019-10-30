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

namespace MediaWiki\Extension\xsl;

use Parser;

class Hooks {
	
	/**
	 * @see https://www.mediawiki.org/wiki/Manual:Hooks/ParserFirstCallInit
	 * 
	 */
	public static function onParserFirstCallInit ( Parser $parser ) {
		$parser->setHook( 'xsl', [self::class, 'xsl_render' ] );
	}	

	#parser hook callback function
	public static function xsl_render( &$parser, $xsl, $xml, $parse=true, $nocache=false ) {
		return "Hello World";
		if ($nocache) {
			$parser->disableCache();
		}

		$output = Hooks::xsl_transform( $xsl, $xml );
		
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