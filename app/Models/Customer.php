<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'tax_number',
        'status',
    ];

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, SalesInvoice::class, 'customer_id', 'payable_id')
            ->where('payments.payable_type', SalesInvoice::class);
    }
}
