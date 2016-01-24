<?php namespace Kevupton\MerchantWarrior\Models;

class Card extends BaseModel {
    // table name
    protected $table = 'cards';
    public $timestamps = true;

    // validation rules
    public static $rules = array(
        'cardID' => 'required|string|max:32',
        'cardKey' => 'required|string|max:64',
        'ivrCardID' => 'required|string|max:32',
    );

    protected $fillable = array(
        'cardID', 'cardKey', 'ivrCardID'
    );
}
