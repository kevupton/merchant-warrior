<?php namespace Kevupton\MerchantWarrior\Models;

use Kevupton\Ethereal\Models\Ethereal;

class BaseModel extends Ethereal {

    /**
     * Defines the prefix for the table.
     * @param array $attr
     */
    public function __construct($attr = array()) {
        $this->table = mw_prefix() . $this->table;
        parent::__construct($attr);
    }
}
