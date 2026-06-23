<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventoryMovement extends Model
{
    protected $table = 'product_inventory_movements';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'batch_id',
        'concept_id',
        'control_book',
        'date',
        'invoice_id',
        'pharmacy_stock_id',
        'prescription_number',
        'quantity',
        'record_number',
        'remarks',
        'user_id',
        'pharmacy_product_request_id',
        'inventory_count_id',
        'movement_dispatch_id',
        'request_inventory_id',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'control_book' => 'boolean',
        'date' => 'date',
    ];
}
