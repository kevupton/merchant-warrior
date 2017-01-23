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

if (!function_exists('mw_uuid')) {
    /**
     * Returns the Merchant UUID for Merchant Warrior account
     *
     * @return string
     */
    function mw_uuid() {
        return mw_conf('merchant_uuid');
    }
}

if (!function_exists('mw_api_key')) {
    /**
     * Returns the API Key for the Merchant Warrior account
     * @return mixed
     */
    function mw_api_key() {
        return mw_conf('api_key');
    }
}


if (!function_exists('mw_api_passphrase')) {
    /**
     * Returns the API Passphrase for the Merchant Warrior account
     * @return mixed
     */
    function mw_api_passphrase() {
        return mw_conf('api_passphrase');
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
        return config(MERCHANT_WARRIOR_CONFIG . '.' . $prop, $default);
    }
}


if (!function_exists('mw_log')) {
    /**
     * Logs the request in the database
     *
     * @param string $content the xml data received
     * @param array $sent the data sent
     * @return Kevupton\MerchantWarrior\Models\Log
     */
    function mw_log($content, array $sent = null) {
        return \Kevupton\MerchantWarrior\Models\Log::create([
            'sent' => json_encode(array_except($sent, \Kevupton\MerchantWarrior\Models\Log::DO_NOT_LOG)),
            'content' => $content
        ]);
    }
}
