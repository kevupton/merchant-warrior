<?php namespace Kevupton\MerchantWarrior\Models;

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
