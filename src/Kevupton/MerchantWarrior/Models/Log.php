<?php namespace Kevupton\MerchantWarrior\Models;

class Log extends BaseModel {
    // table name
    protected $table = 'log';
    public $timestamps = true;

    // validation rules
    public static $rules = array();

    protected $fillable = array(
        'content'
    );
}
