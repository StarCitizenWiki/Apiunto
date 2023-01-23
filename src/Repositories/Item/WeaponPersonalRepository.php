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

namespace MediaWiki\Extension\Apiunto\Repositories\Item;

use MediaWiki\Extension\Apiunto\Repositories\AbstractRepository;

/**
 * Star Citizen Manufacturer data
 */
final class WeaponPersonalRepository extends AbstractRepository {
	/**
	 * Available includes on the api
	 */
	public const INCLUDES = [
		'modes',
		'shops',
		'shops.items',
		'modes',
		'damages',
		'attachments',
		'attachmentPorts',
	];

	public const API_ENDPOINT = 'api/weapons/personal';

	/**
	 * Star Citizen Personal Weapons
	 * https://docs.star-citizen.wiki/weapons/personal.html
	 *
	 * @return string JSON data
	 */
	public function getWeaponPersonal(): string {
		return $this->request();
	}
}
