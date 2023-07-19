local Apiunto = {}
local php


local function request( payload )
    if payload.uri == nil then
        error( "Uri is missing in payload.", 3 )
    end

    if type( payload.args ) ~= 'table' then
        payload.args = {}
    end

    --return payload.uri
    return php.get_raw( payload.uri, payload.args )
end


function Apiunto.get_ship( name, args )
    return request( {
        uri = 'v2/vehicles/' .. name,
        args = args,
    } )
end


function Apiunto.get_ground_vehicle( name, args )
    return request( {
        uri = 'v2/vehicles/' .. name,
        args = args,
    } )
end


function Apiunto.get_manufacturer( name, args )
    return request( {
        uri = 'v2/manufacturers/' .. name,
        args = args,
    } )
end


function Apiunto.get_comm_link_metadata( id, args )
    return request( {
        uri = 'v2/comm-links/' .. id,
        args = args,
    } )
end


function Apiunto.get_starsystem( id, args )
    return request( {
        uri = 'v2/starsystems/' .. id,
        args = args,
    } )
end


function Apiunto.get_celestial_object( id, args )
    return request( {
        uri = 'v2/celestial-objects/' .. id,
        args = args,
    } )
end


function Apiunto.get_galactapedia( id, args )
    return request( {
        uri = 'v2/galactapedia/' .. id,
        args = args,
    } )
end


function Apiunto.get_weapon_personal( name, args )
    return request( {
        uri = 'v2/weapons/' .. name,
        args = args,
    } )
end


function Apiunto.get_char_armor( name, args )
    return request( {
        uri = 'v2/armor' .. name,
        args = args,
    } )
end


function Apiunto.get_cooler( name, args )
    return request( {
        uri = 'v2/vehicle-items/' .. name,
        args = args,
    } )
end


function Apiunto.get_power_plant( name, args )
    return request( {
        uri = 'v2/vehicle-items/' .. name,
        args = args,
    } )
end


function Apiunto.get_quantum_drive( name, args )
    return request( {
        uri = 'v2/vehicle-items/' .. name,
        args = args,
    } )
end


function Apiunto.get_shield( name, args )
    return request( {
        uri = 'v2/vehicle-items/' .. name,
        args = args,
    } )
end


function Apiunto.get_raw( uri, args )
    return request( {
        uri = uri,
        args = args,
    } )
end


function Apiunto.setupInterface( options )
    -- Boilerplate
    Apiunto.setupInterface = nil
    php = mw_interface
    mw_interface = nil

    -- Register this library in the "mw" global
    mw = mw or {}
    mw.ext = mw.ext or {}
    mw.ext.Apiunto = Apiunto

    package.loaded[ 'mw.ext.Apiunto' ] = Apiunto
end


return Apiunto