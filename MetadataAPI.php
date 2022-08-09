<?php

use MediaWiki\Rest\SimpleHandler;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Class to get and set metadata for a page
 * POST /metadata/v0/{id}/{key}/{value}
 */
class MetadataAPI extends SimpleHandler {

	public function run( $id, $key = null, $value = null ) {
		$id = strval( $id );
		if ( $value ) {
	        return Metadata::set( $id, $key, $value );
		} else {
			return Metadata::get( $id, $key );
		}
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'id' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
			'key' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			],
			'value' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
			]
		];
	}
}