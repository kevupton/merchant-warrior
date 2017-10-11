<?php namespace Kevupton\MerchantWarrior\Models;

class Card extends BaseModel
{
    // table name
    protected $table = 'cards';
    protected $primaryKey = 'cardID';
    public $incrementing = false;

    protected $hidden = ['cardKey', 'ivrCardID', 'user_id', 'cardNumber', 'created_at', 'updated_at', 'cardAdded', 'cardName'];

    protected $fillable = array(
        'cardID', 'cardKey', 'ivrCardID', 'cardNumber', 'cardName', 'cardExpiryMonth', 'cardAdded',
        'cardExpiryYear', 'cardNumberFirst', 'cardNumberLast', 'user_id'
    );

    public function payments ()
    {
        return $this->hasMany(Payment::class, 'cardID');
    }

    public function getCardNumberAttribute ($value) {
        return decrypt($value);
    }

    public function setCardNumberAttribute ($value) {
        $this->attributes['cardNumber'] = encrypt($value);
    }

    public function getCardNumberFirstAttribute ($value) {
        return decrypt($value);
    }

    public function setCardNumberFirstAttribute ($value) {
        $this->attributes['cardNumberFirst'] = encrypt($value);
    }

    public function getCardNumberLastAttribute ($value) {
        return decrypt($value);
    }

    public function setCardNumberLastAttribute ($value) {
        $this->attributes['cardNumberLast'] = encrypt($value);
    }
}
