<?php

define('MERCHANT_WARRIOR_CONFIG', 'merchant_warrior');

if (!function_exists('mw_prefix')) {
    /**
     * Gets the prefix of the database table.
     *
     * @return string
     */
    function mw_prefix() {
        return mw_conf('database_prefix');
    }
}


if (!function_exists('mw_conf')) {
    /**
     * Gets a config value from the config file.
     *
     * @param string $prop the key property
     * @param string $default the default response
     *
     * @return mixed
     */
    function mw_conf($prop, $default = '') {
        return Config::get(MERCHANT_WARRIOR_CONFIG . '.' . $prop, $default);
    }
}
