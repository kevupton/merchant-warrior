<?php namespace Kevupton\MerchantWarrior\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function getTable ()
    {
        return mw_prefix() . parent::getTable();
    }
}
