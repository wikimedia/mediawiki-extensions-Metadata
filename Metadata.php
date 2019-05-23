<?php

class Metadata {

	/**
	 * Create the database table
	 */
	static function onLoadExtensionSchemaUpdates( DatabaseUpdater $updater ) {
		$updater->addExtensionTable( 'metadata', __DIR__ . '/metadata.sql' );
	}

	/**
	 * Set metadata for the given page
	 */
	static function set( int $page, $keyOrData, $value = null ) {
		if ( is_array( $keyOrData ) ) {
			$data = $keyOrData;
		} else if ( is_string( $keyOrData ) ) {
			$key = $keyOrData;
			$data = [ $key => $value ];
		} else {
			return;
		}

		$dbw = wfGetDB( DB_MASTER );
		foreach ( $data as $key => $value ) {
			$dbw->upsert(
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
	}

	/**
	 * Get metadata for the given page and key
	 */
	static function get( int $page, $key = null ) {
		$dbr = wfGetDB( DB_REPLICA );

		if ( $key ) {
			return $dbr->selectField( 'metadata', 'md_value', "md_page = $page AND md_key = '$key'" );
		}

		$data = [];
		$result = $dbr->select( 'metadata', 'md_key, md_value', "md_page = $page" );
		foreach ( $result as $row ) {
			$data[ $row->md_key ] = $row->md_value;
		}
		return $data;
	}
}