<?php

return array(

    //prefix to each of the tables in the database
    'database_prefix' => env('MERCHANT_WARRIOR_DB_PREFIX', 'mw_'),

    //The Merchant UUID found in your Direct API Merchant Warrior Account
    'merchant_uuid' => env('MERCHANT_WARRIOR_UUI'),

    //API Key, your merchant warrior API Key found in your Direct API Merchant Warrior Account
    'api_key' => env('MERCHANT_WARRIOR_API_KEY'),

    //The API Passphrase from the Direct API from the Merchant Warrior Account.
    'api_passphrase' => env('MERCHANT_WARRIOR_API_PASSPHRASE'),

    //whether or not to use the testing service or live service with merchant warrior.
    'testing' =>  env('MERCHANT_WARRIOR_TESTING', false),

    //Whether or not to automatically saved the data received (requires the tables to be created)
    'save_data' => env('MERCHANT_WARRIOR_SAVE_DATA', false),

    // whether or not to save the authenticated user to the card object
    'add_user_to_card' => env('MERCHANT_WARRIOR_SAVE_USER', false)

);