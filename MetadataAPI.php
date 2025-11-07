<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Class to get and set metadata for a page
 * GET /v1/page/{title}/props
 */
class MetadataAPI extends SimpleHandler {

	/**
	 * Get the page properties from the database
	 * and return them as an associative array
	 * that will be converted into JSON
	 *
	 * @param string $title Title of the page
	 * @return array Page properties
	 */
	public function run( $title ) {
		$Title = Title::newFromText( $title );
		$id = $Title->getArticleID();
		$lb = MediaWikiServices::getInstance()->getDBLoadBalancer();
		$dbr = $lb->getConnection( DB_REPLICA );
		$data = [];
		$result = $dbr->select( 'page_props', 'pp_propname, pp_value', "pp_page = $id" );
		foreach ( $result as $row ) {
			$data[ $row->pp_propname ] = $row->pp_value;
		}
		return $data;
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			]
		];
	}
}
