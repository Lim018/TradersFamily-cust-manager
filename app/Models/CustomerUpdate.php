<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'field_name', 'old_value', 'new_value', 'synced_to_sheet'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}