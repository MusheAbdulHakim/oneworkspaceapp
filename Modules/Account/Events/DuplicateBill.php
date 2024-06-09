<?php

namespace Modules\Account\Events;

use Illuminate\Queue\SerializesModels;

class DuplicateBill
{
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $duplicateProduct;
    public $duplicateBil;

    public function __construct($duplicateProduct,$duplicateBil)
    {
        $this->duplicateProduct = $duplicateProduct;
        $this->duplicateBil = $duplicateBil;
    }
}
