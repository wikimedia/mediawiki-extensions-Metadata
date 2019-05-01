<?php

class Metadata {

	/**
	 * Create the database table
	 */
	static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$updater->addExtensionTable( 'metadata', __DIR__ . '/metadata.sql' );
	}

	/**
	 * Set metadata
	 */
	static function set( $page, $key, $value ) {
		$dbw = wfGetDB( DB_MASTER );
		return $dbw->upsert(
			'metadata',
			[
				'md_page' => $page,
				'md_key' => $key,
				'md_value' => $value,
			],
			[
				'md_page',
				'md_key',
			],
			[
				'md_value' => $value,
			]
		);
	}

	/**
	 * Get metadata
	 */
	static function get( $page, $key ) {
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->selectField( 'metadata', 'md_value', "md_page = '$page' AND md_key = '$key'" );
	}
}