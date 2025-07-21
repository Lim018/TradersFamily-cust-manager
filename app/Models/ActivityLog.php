<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'customer_id', 'action', 'description', 'old_data', 'new_data'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}