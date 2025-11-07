<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;

class ShowCustomerData extends Command
{
    protected $signature = 'show:customers';
    protected $description = 'Display customers with non-null passwords';

    public function handle()
    {
        $customers = Customer::whereNotNull('password')->take(5)->get(['name', 'phone', 'password']);
        $this->info($customers);
    }
}
