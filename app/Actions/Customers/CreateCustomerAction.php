<?php

namespace App\Actions\Customers;

use App\Models\Customer;

/**
 * Handles the business logic for creating a new customer.
 * 
 * Extracts logic away from the controller. This allows us to easily reuse
 * customer creation logic in CLI commands, API endpoints, or job queues
 * without duplicating code.
 */
class CreateCustomerAction
{
    /**
     * Execute the action.
     *
     * @param array $data
     * @return Customer
     */
    public function execute(array $data): Customer
    {
        // 2026-09-18: Future-proofing - if we ever need to dispatch events 
        // like NewCustomerRegistered, it goes right here.
        return Customer::create($data);
    }
}
