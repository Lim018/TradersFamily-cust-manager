<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'tanggal', 'regis', 'nama', 'email', 'phone',
        'first_visit', 'interest', 'offer', 'status_fu', 'tanggal_closing',
        'report', 'alasan_depo_decline', 'fu_jumlah', 'fu_ke_1',
        'fu_checkbox', 'next_fu', 'fu_dates', 'notes', 'followup_date', 'sheet_month'
    ];

    protected $casts = [
        'tanggal' => 'string',
        'tanggal_closing' => 'string',
        'fu_ke_1' => 'string',
        'next_fu' => 'string',
        'followup_date' => 'date',
        'fu_checkbox' => 'boolean',
        'fu_dates' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper method for WhatsApp redirect
    public function getWhatsAppUrl()
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        return "https://wa.me/{$phone}";
    }
}