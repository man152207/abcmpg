<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;

class ImpersonationMiddleware
{
    public function handle($request, Closure $next)
    {
        // Check if impersonation session exists
        if (Session::has('impersonate_customer_id')) {
            $customerId = Session::get('impersonate_customer_id');

            // Log in as the impersonated customer
            $customer = Customer::find($customerId);
            if ($customer) {
                Auth::guard('customer')->login($customer);
            }
        }

        return $next($request);
    }
}
