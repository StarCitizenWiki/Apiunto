<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
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

declare( strict_types=1 );

namespace MediaWiki\Extension\Apiunto\Hooks;

use MediaWiki\Page\Hook\ArticlePurgeHook;
use ObjectCache;
use Wikimedia\Rdbms\ILoadBalancer;

class PurgeHooks implements ArticlePurgeHook {

	/**
	 * @var ILoadBalancer
	 */
	private $loadBalancer;

	/**
	 * PurgeHooks constructor.
	 *
	 * @param ILoadBalancer $loadBalancer
	 */
	public function __construct( ILoadBalancer $loadBalancer ) {
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @inheritDoc
	 */
	public function onArticlePurge( $wikiPage ) {
		if ( $wikiPage->getTitle() === null ) {
			return;
		}

		$db = $this->loadBalancer->getConnection( $this->loadBalancer->getReaderIndex() );

		$res = $db->select(
			'page_props',
			[
				'pp_value',
			],
			[
				'pp_page' => $wikiPage->getTitle()->getArticleID(),
				'pp_propname' => 'apiuntocache',
			],
			__METHOD__
		);

		if ( $res->numRows() === 0 ) {
			return;
		}

		$row = $res->fetchRow();
		if ( $row === false || !isset( $row['pp_value'] ) ) {
			return;
		}

		$this->loadBalancer->getConnection( $this->loadBalancer->getWriterIndex() )->delete(
			'page_props',
			[
				'pp_page' => $wikiPage->getTitle()->getArticleID(),
				'pp_propname' => 'apiuntocache',
			]
		);

		ObjectCache::getLocalClusterInstance()->delete( $row['pp_value'] );
	}
}
