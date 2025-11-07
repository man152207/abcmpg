<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Customize the data you want to export
        return Customer::all()->map(function ($customer) {
            return [
                'name' => $customer->name,
                'display_name' => $customer->display_name,
                'email' => $customer->email,
                'phone' => $customer->phone, // Assuming 'contact' is a field in your model
                'customer_since' => $customer->created_at // Replace with your actual field
            ];
        });
    }

    public function headings(): array
    {
        // Define the headings for your Excel columns
        return [
            'Name',
            'Display Name',
            'Email',
            'Phone',
            'Customer Since'
        ];
    }
}
