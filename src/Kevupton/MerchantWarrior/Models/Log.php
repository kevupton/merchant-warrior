<?php namespace Kevupton\MerchantWarrior\Models;

/**
 * Class Log
 * @package Kevupton\MerchantWarrior\Models
 * @property mixed content
 * @property mixed sent
 */
class Log extends BaseModel {
    // table name
    protected $table = 'log';

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
