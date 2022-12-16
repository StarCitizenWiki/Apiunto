local libraryUtil = require('libraryUtil')
local Apiunto = {}
local php

local function request(method, payload)
    if payload.identifier == nil then
        local msg = string.format("identifier is missing in payload '%s'.",
                method
        )
        error(msg, 3)
    end

    if type(payload.args) ~= 'table' then
        payload.args = {}
    end

    return php[method](payload.identifier, payload.args)
end

function Apiunto.get_ship(name, args)
    return request('get_ship', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_ground_vehicle(name, args)
    return request('get_ground_vehicle', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_manufacturer(name, args)
    return request('get_manufacturer', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_comm_link_metadata(id, args)
    return request('get_comm_link_metadata', {
        identifier = id,
        args = args,
    })
end

function Apiunto.get_starsystem(id, args)
    return request('get_starsystem', {
        identifier = id,
        args = args,
    })
end

function Apiunto.get_celestial_object(id, args)
    return request('get_celestial_object', {
        identifier = id,
        args = args,
    })
end

function Apiunto.get_galactapedia(id, args)
    return request('get_galactapedia', {
        identifier = id,
        args = args,
    })
end

function Apiunto.get_weapon_personal(name, args)
    return request('get_weapon_personal', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_char_armor(name, args)
    return request('get_char_armor', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_cooler(name, args)
    return request('get_cooler', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_power_plant(name, args)
    return request('get_power_plant', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_quantum_drive(name, args)
    return request('get_quantum_drive', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_shield(name, args)
    return request('get_shield', {
        identifier = name,
        args = args,
    })
end

function Apiunto.get_raw(uri, args)
    return request('get_raw', {
        identifier = uri,
        args = args,
    })
end

function Apiunto.setupInterface(options)
    -- Boilerplate
    Apiunto.setupInterface = nil
    php = mw_interface
    mw_interface = nil

    -- Register this library in the "mw" global
    mw = mw or {}
    mw.ext = mw.ext or {}
    mw.ext.Apiunto = Apiunto

    package.loaded['mw.ext.Apiunto'] = Apiunto
end

function getLocale(locale)
    if locale == nil then
        return ''
    else
        return tostring(locale)
    end
end

return Apiunto
