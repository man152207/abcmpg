<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        $customers = Customer::whereNotNull('password')->take(5)->get(['name', 'phone', 'password']);
        dd($customers);
    }
}
