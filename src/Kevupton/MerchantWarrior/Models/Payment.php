<?php namespace Kevupton\MerchantWarrior\Models;

/**
 * Class Payment
 * @package Kevupton\MerchantWarrior\Models
 * @property mixed id
 * @property mixed transactionAmount
 * @property mixed transactionCurrency
 * @property mixed transactionProduct
 * @property mixed cardID
 * @property mixed transactionReferenceID
 * @property mixed customerName
 * @property mixed customerCountry
 * @property mixed customerState
 * @property mixed customerCity
 * @property mixed customerAddress
 * @property mixed customerPostCode
 * @property mixed customerPhone
 * @property mixed customerEmail
 * @property mixed customerIP
 * @property mixed custom1
 * @property mixed custom2
 * @property mixed custom3
 */
class Payment extends BaseModel {
    // table name
    protected $table = 'payments';

    protected $fillable = array(
        'transactionAmount', 'transactionCurrency', 'transactionProduct', 'cardID',
        'transactionReferenceID', 'customerName', 'customerCountry', 'customerState',
        'customerCity', 'customerAddress', 'customerPostCode', 'customerPhone',
        'customerEmail', 'customerIP', 'custom1', 'custom2', 'custom3'
    );

    public function card () {
        return $this->belongsTo(Card::class, 'cardID');
    }
}
