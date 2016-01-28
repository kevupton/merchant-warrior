<?php namespace Kevupton\MerchantWarrior\Models;

class Payment extends BaseModel {
    // table name
    protected $table = 'payments';
    public $timestamps = true;

    // validation rules
    public static $rules = array(
        'cardID' => 'string|max:32',
        'transactionAmount' => 'required|numeric',
        'transactionCurrency' => 'required|string|max:3',
        'transactionProduct' => 'required|string|max:255',
        'transactionReferenceID' => 'string|max:16',
        'customerName' => 'required|string|max:255|min:3',
        'customerCountry' => 'required|string|size:2',
        'customerState' => 'required|string|max:75',
        'customerCity' => 'required|string|max:75',
        'customerAddress' => 'required|string|max:255',
        'customerPostCode' => 'required|string|max:10|min:4',
        'customerPhone' => 'string|max:25',
        'customerEmail' => 'string|max:255',
        'customerIP' => 'string|max:39',
        'custom1' => 'string|max:500',
        'custom2' => 'string|max:500',
        'custom3' => 'string|max:500',
    );

    protected $fillable = array(
        'transactionAmount', 'transactionCurrency', 'transactionProduct', 'cardID',
        'transactionReferenceID', 'customerName', 'customerCountry', 'customerState',
        'customerCity', 'customerAddress', 'customerPostCode', 'customerPhone',
        'customerEmail', 'customerIP', 'custom1', 'custom2', 'custom3'
    );

    // relationships
    public static $relationsData = array(
        'card' => array(self::BELONGS_TO, Card::class, 'cardID'),
    );
}
