local libraryUtil = require('libraryUtil')
local Apiunto = {}
local php

function Apiunto.get_ship(name, locale)
    libraryUtil.checkType('get_ship', 1, name, 'string', false)
    libraryUtil.checkTypeMulti('get_ship', 2, locale, { 'string', 'nil' })

    name = tostring(name)
    locale = getLocale(locale)

    return php.get_ship(name, locale)
end

function Apiunto.get_ground_vehicle(name, locale)
    libraryUtil.checkType('get_ground_vehicle', 1, name, 'string', false)
    libraryUtil.checkTypeMulti('get_ground_vehicle', 2, locale, { 'string', 'nil' })

    name = tostring(name)
    locale = getLocale(locale)

    return php.get_ground_vehicle(name, locale)
end

function Apiunto.get_manufacturer(name, locale)
    libraryUtil.checkType('get_manufacturer', 1, name, 'string', false)
    libraryUtil.checkTypeMulti('get_manufacturer', 2, locale, { 'string', 'nil' })

    name = tostring(name)
    locale = getLocale(locale)

    return php.get_manufacturer(name, locale)
end

function Apiunto.get_comm_link_metadata(id)
    libraryUtil.checkType('get_comm_link_metadata', 1, name, 'number', false)

    return php.get_comm_link_metadata(id)
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
