<?php

namespace App\Actions\Customers;

use App\Models\Customer;

/**
 * Handles the business logic for updating an existing customer.
 */
class UpdateCustomerAction
{
    /**
     * Execute the action.
     *
     * @param Customer $customer
     * @param array $data
     * @return Customer
     */
    public function execute(Customer $customer, array $data): Customer
    {
        // 2026-09-18: If we ever need to log address changes for audit purposes,
        // we can hook into this action easily.
        $customer->update($data);

        return $customer;
    }
}
