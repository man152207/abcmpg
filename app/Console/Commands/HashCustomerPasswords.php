<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class HashCustomerPasswords extends Command
{
    protected $signature = 'customer:hash-passwords';
    protected $description = 'Hashes plain-text passwords for customers';

    public function handle()
{
    $customers = \App\Models\Customer::all();

    foreach ($customers as $customer) {
        if (!Hash::check($customer->phone, $customer->password)) {
            $customer->password = Hash::make($customer->phone); // Use phone as the password
            $customer->save();
            $this->info("Password updated for customer: {$customer->email}");
        }
    }

    $this->info('All customer passwords have been updated.');
}
}
