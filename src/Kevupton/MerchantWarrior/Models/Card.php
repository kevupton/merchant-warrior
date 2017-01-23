<?php namespace Kevupton\MerchantWarrior\Models;

class Card extends BaseModel {
    // table name
    protected $table = 'cards';
    public $timestamps = true;
    protected $primaryKey = 'cardID';
    public $incrementing = false;

    // validation rules
    public static $rules = array(
        'cardID' => 'required|string|max:32',
        'cardKey' => 'required|string|max:64',
        'ivrCardID' => 'required|string|max:32',
        'user_id' => 'integer|min:0',
        'cardName' => 'required|string|max:128',
        'cardExpiryMonth' => 'required|string|size:2',
        'cardExpiryYear' => 'required|string|size:2',
        'cardNumberFirst' => 'required|string|size:4',
        'cardNumberLast' => 'required|string|size:4',
        'cardAdded' => 'required|date',
    );

    protected $fillable = array(
        'cardID', 'cardKey', 'ivrCardID', 'cardName', 'cardExpiryMonth', 'cardAdded',
        'cardExpiryYear', 'cardNumberFirst', 'cardNumberLast', 'user_id'
    );

    // relationships
    public static $relationsData = array(
        'payments' => array(self::HAS_MANY, Payment::class, 'cardID'),
    );
}
