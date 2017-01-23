<?php namespace Kevupton\MerchantWarrior\Models;

class Log extends BaseModel {
    // table name
    protected $table = 'log';
    public $timestamps = true;

    const DO_NOT_LOG = [
        'cardNumber',
        'cardName',
        'cardExpiryMonth',
        'cardExpiryYear',
        'paymentCardNumber',
        'paymentCardExpiry',
        'paymentCardName',
        'paymentCardCSC'
    ];

    // validation rules
    public static $rules = array();

    protected $fillable = array(
        'content',
        'sent'
    );
}
