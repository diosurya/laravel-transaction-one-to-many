<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'date',
        'total_price',
        'total_quantity',
        'transaction_number'

    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
