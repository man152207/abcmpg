<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\Ad;

class CustomerObserver
{
    public function updated(Customer $customer)
    {
        if ($customer->wasChanged('phone')) {
            Ad::where('customer', $customer->getOriginal('phone'))->update(['customer' => $customer->phone]);
        }
    }
}
