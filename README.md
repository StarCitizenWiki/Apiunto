# Apiunto

Lua Extension for MediaWiki to access the Star Citizen Wiki API

## Installation
```php
wfLoadExtension( 'Apiunto' );

$wgApiuntoKey = ''; // Key from api.star-citizen.wiki
$wgApiuntoUrl = 'https://api.star-citizen.wiki'; // Or self-host https://github.com/StarCitizenWiki/API
$wgApiuntoTimeout = '5'; // 5 seconds
$wgApiuntoDefaultLocale = 'de_DE'; // Or en_EN
```

## Lua Usage
```lua
local api = mw.ext.Apiunto

-- Request the ship data for the 300i with german locale
-- Docs: https://docs.star-citizen.wiki/star_citizen_api.html#raumschiffe
-- Output: https://api.star-citizen.wiki/api/ships/300i?locale=de_DE
local ship_300i = api.get_ship( '300i', {
    locale = 'de_DE',
} )
local json = mw.text.jsonDecode( ship_300i )


-- Request data for the 300i with both german and english locale
local ship_300i = api.get_ship( '300i' )

-- Request data for the 300i with english locale and included components
local ship_300i = api.get_ship( '300i', {
    locale = 'en_EN',
    include = 'components'
} )

-- Request data for the Greycat Industrial ROC
-- Docs: https://docs.star-citizen.wiki/star_citizen_api.html#bodenfahrzeuge
-- Output: https://api.star-citizen.wiki/api/vehicles/Greycat%20Industrial%20-%20ROC
local roc = api.get_ground_vehicle( 'Greycat Industrial - ROC' )

-- RSI Manufacturer data
-- Docs: https://docs.star-citizen.wiki/star_citizen_api.html#hersteller
-- Output: https://api.star-citizen.wiki/api/manufacturers/RSI
local rsi = api.get_manufacturer( 'RSI' )

-- RSI Manufacturer data with vehicles
-- Docs: https://docs.star-citizen.wiki/star_citizen_api.html#hersteller
-- Output: https://api.star-citizen.wiki/api/manufacturers/RSI
local rsi = api.get_manufacturer( 'RSI', {
    include = 'vehicles'
} )

-- Comm-Link Metadata
-- Docs: https://docs.star-citizen.wiki/star_citizen_api.html#comm-links
-- Output: https://api.star-citizen.wiki/api/comm-links/12667
local commLink = api.get_comm_link_metadata( 12667 )

-- Comm-Link Metadata including images and image hashes
-- Docs: https://docs.star-citizen.wiki/star_citizen_api.html#comm-links
-- Output: https://api.star-citizen.wiki/api/comm-links/12667
local commLink = api.get_comm_link_metadata( 12667, {
    include = {
        'images',
        'images.hashes',
    }
} )

-- Star System Data
-- Includable: "jumppoint_entries", "jumppoint_exits", "celestial_objects"
-- Output: https://api.star-citizen.wiki/api/starmap/starsystems/sol
local sol = api.get_starsystem( 'sol' ) -- Or: 'SOL' / 355 (id)

-- Celestial object data
-- Output: https://api.star-citizen.wiki/api/starmap/celestial-objects/SOL.JUMPPOINTS.CROSHAW
local jumppoint = api.get_celestial_object( 'SOL.JUMPPOINTS.CROSHAW', {
    include = {
    }
} )

-- Output: https://api.star-citizen.wiki/api/starmap/celestial-objects/2702
-- YULIN.STATION.YULINFLOTILLA
local objectById = api.get_celestial_object( 2702, {
    include = {
    }
} )
```