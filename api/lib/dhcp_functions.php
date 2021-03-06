<?php
/*
 * Copyright (C) 2018 Nethesis S.r.l.
 * http://www.nethesis.it - nethserver@nethesis.it
 *
 * This script is part of NethServer.
 *
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 *
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see COPYING.
*/

require_once("/usr/libexec/nethserver/api/lib/Helpers.php");

function getNetworkInterfaces()
{
    $db = new EsmithDatabase('networks');
    $interfaces = array_filter($db->getAll(), function ($record) {
        if ( ! in_array($record['type'], array('ethernet', 'bridge', 'bond', 'vlan'))) {
            return FALSE;
        }
        if ( ! in_array($record['role'], array('green', 'blue'))) {
            return FALSE;
        }
        return TRUE;
    });
    return $interfaces;
}

function getDefaultRange($type, $props)
{
    $ipaddr = ip2long($props['ipaddr']);
    $netmask = ip2long($props['netmask']);
    if ( ! ($ipaddr && $netmask)) {
        return '';
    }
    if ($type === 'start') {
        return long2ip(($ipaddr & $netmask) | 1);
    } elseif ($type === 'end') {
        return long2ip(($ipaddr | ~$netmask) & ~1);
    }
    return '';
}

function getDefaults($props)
{
    $ret = array(
        "DhcpTFTP" => array(),
        "status" =>  "disabled",
        "DhcpDNS" => array(),
        "DhcpDomain" => "",
        "DhcpLeaseTime" => "",
        "DhcpRangeStart" => getDefaultRange('start', $props),
        "DhcpWINS" => array(),
        "DhcpRangeEnd" => getDefaultRange('end', $props),
        "DhcpGatewayIP" => "",
        "DhcpNTP" =>  array()
    );

    return $ret;
}
