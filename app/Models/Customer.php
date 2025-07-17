<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheet_name', 'row_number', 'tanggal', 'regis', 'nama', 'email', 
        'phone', 'first_visit', 'interest', 'offer', 'status_fu', 
        'tanggal_closing', 'report', 'alasan_depo_decline', 'fu'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_closing' => 'date',
    ];

    public function updates()
    {
        return $this->hasMany(CustomerUpdate::class);
    }

    public function scopeByMonth($query, $month)
    {
        return $query->where('sheet_name', $month);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status_fu', $status);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}