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
    );

    protected $fillable = array(
        'cardID', 'cardKey', 'ivrCardID'
    );

    // relationships
    public static $relationsData = array(
        'payments' => array(self::HAS_MANY, Payment::class, 'cardID'),
        'info' => array(self::HAS_ONE, CardInfo::class, 'cardID'),
    );
}
