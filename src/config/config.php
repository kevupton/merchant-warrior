<?php

return array(

    //prefix to each of the tables in the database
    'database_prefix' => 'mw_',

    //The Merchant UUID found in your Direct API Merchant Warrior Account
    'merchant_uuid' => '',

    //API Key, your merchant warrior API Key found in your Direct API Merchant Warrior Account
    'api_key' => '',

    //The API Passphrase from the Direct API from the Merchant Warrior Account.
    'api_passphrase' => '',

    //whether or not to use the testing service or live service with merchant warrior.
    'testing' => true,

    //Whether or not to automatically saved the data received (requires the tables to be created)
    'save_data' => true
);