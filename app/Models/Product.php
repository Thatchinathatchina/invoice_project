<?php

namespace App\Models;

use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class Product extends Model
{
    use Filterable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
        'status',
    ];

    protected $casts = [
        'type' => ProductType::class,
        'status' => \App\Enums\StatusEnum::class,
        'price' => 'decimal:2',
    ];

    public function salesInvoiceItems()
    {
        return $this->hasMany(SalesInvoiceItem::class);
    }

    public function purchaseInvoiceItems()
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }
}
