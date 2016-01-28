<?php namespace Kevupton\MerchantWarrior\Models;

class CardInfo extends BaseModel {
    // table name
    protected $table = 'card_info';
    public $timestamps = true;
    protected $primaryKey = 'cardID';
    public $incrementing = false;

    // validation rules
    public static $rules = array(
        'cardID' => 'required|string|max:32',
        'cardName' => 'required|string|max:128',
        'cardExpiryMonth' => 'required|string|size:2',
        'cardExpiryYear' => 'required|string|size:2',
        'cardNumberFirst' => 'required|string|size:4',
        'cardNumberLast' => 'required|string|size:4',
        'cardAdded' => 'required|date',
    );

    protected $fillable = array(
        'cardID', 'cardName', 'cardExpiryMonth', 'cardAdded',
        'cardExpiryYear', 'cardNumberFirst', 'cardNumberLast'
    );

    // relationships
    public static $relationsData = array(
        'card' => array(self::BELONGS_TO, Card::class, 'cardID'),
    );
}
