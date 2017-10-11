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
 * @property mixed responseCode
 * @property mixed responseMessage
 * @property mixed transactionID
 * @property mixed authCode
 * @property mixed receiptNo
 * @property mixed authMessage
 * @property mixed authResponseCode
 * @property mixed authSettledDate
 * @property mixed paymentCardNumber
 * @property mixed customHash
 */
class Payment extends BaseModel
{
    // table name
    protected $table = 'payments';

    public $hidden = ['paymentCardNumber', 'customHash'];

    protected $fillable = array(
        'transactionAmount', 'transactionCurrency', 'transactionProduct', 'cardID',
        'transactionReferenceID', 'customerName', 'customerCountry', 'customerState',
        'customerCity', 'customerAddress', 'customerPostCode', 'customerPhone',
        'customerEmail', 'customerIP', 'custom1', 'custom2', 'custom3',
        'responseCode',
        'responseMessage',
        'transactionID',
        'authCode',
        'receiptNo',
        'authMessage',
        'authResponseCode',
        'authSettledDate',
        'paymentCardNumber',
        'customHash',
    );

    public function card ()
    {
        return $this->belongsTo(Card::class, 'cardID');
    }

    public function getPaymentCardNumberAttribute ($value)
    {
        return decrypt($value);
    }

    public function setPaymentCardNumberAttribute ($value)
    {
        $this->attributes['paymentCardNumber'] = encrypt($value);
    }
}
