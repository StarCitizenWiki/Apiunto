<?php

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

class PurgeCache extends Maintenance {
	public function __construct() {
		parent::__construct();

		$this->addDescription( "Purge the Apiunto cache" );
		$this->setBatchSize( 150 );
		$this->requireExtension( 'Apiunto' );
		$this->addOption( 'dry-run', "Don't actually delete the cache." );
	}

	public function execute() {
		$iterator = new BatchRowIterator(
			$this->getDB( DB_REPLICA ),
			'page_props',
			[ 'pp_page', 'pp_value' ],
			$this->getBatchSize()
		);
		$iterator->addConditions( [ 'pp_propname' => 'apiuntocache' ] );
		$iterator->setCaller( __METHOD__ );

		$dbw = $this->getDB( DB_PRIMARY );
		if ( $dbw === null ) {
			$this->error( 'DB Write is null.' );
			return;
		}

		foreach ( $iterator as $batch ) {
			foreach ( $batch as $row ) {
				$title = Title::newFromID( $row->pp_page );

				$this->output( sprintf(
					"%sDeleting Apiunto cache for title %s\n",
					$this->getOption( 'dry-run' ) !== null ? '(Not) ' : '',
					$title->getTitleValue()
				) );

				if ( $this->getOption( 'dry-run' ) !== null ) {
					$dbw->delete(
						'page_props',
						[
							'pp_page' => $row->pp_page,
							'pp_propname' => 'apiuntocache',
						]
					);

					ObjectCache::getLocalClusterInstance()->delete( $row->pp_value );
				}
			}
		}
	}
}

$maintClass = PurgeCache::class;
require_once RUN_MAINTENANCE_IF_MAIN;
