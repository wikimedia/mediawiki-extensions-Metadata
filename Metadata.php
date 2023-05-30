<?php

class Metadata {

	/**
	 * Set parser hook
	 *
	 * @param Parser $parser
	 */
	public static function onParserFirstCallInit( Parser $parser ) {
		$parser->setFunctionHook( 'metadata', [ self::class, 'onFunctionHook' ] );
	}

	/**
	 * Parser hook callback
	 *
	 * @param Parser $parser Parser object
	 */
	public static function onFunctionHook( Parser $parser ) {
		$arguments = func_get_args();
		$params = array_slice( $arguments, 1 );
		$params = self::parseParams( $params );
		foreach ( $params as $param => $value ) {
			$parser->getOutput()->setPageProperty( $param, $value );
		}
	}

	/**
	 * OutputPageParserOutput callback
	 *
	 * @param OutputPage $out
	 * @param ParserOutput $parserOutput
	 */
	public static function onOutputPageParserOutput( OutputPage $out, ParserOutput $parserOutput ) {
		$props = $parserOutput->getPageProperties();
		foreach ( $props as $prop => $value ) {
			$out->addMeta( $prop, $value );
		}
	}

	/**
	 * Helper method to convert an array of values in form [0] => "name=value"
	 * into a real associative array in form [name] => value
	 * If no = is provided, true is assumed like this: [name] => true
	 *
	 * @param array $params
	 * @return array
	 */
	private static function parseParams( array $params ) {
		$array = [];
		foreach ( $params as $param ) {
			$pair = array_map( 'trim', explode( '=', $param, 2 ) );
			if ( !$pair[0] ) {
				continue;
			}
			if ( count( $pair ) === 2 ) {
				$array[ $pair[0] ] = $pair[1];
			} elseif ( count( $pair ) === 1 ) {
				$array[ $pair[0] ] = true;
			}
		}
		return $array;
	}
}
