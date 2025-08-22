<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal',
        'regis',
        'nama',
        'email',
        'phone',
        'first_visit',
        'interest',
        'offer',
        'status_fu',
        'tanggal_closing',
        'report',
        'alasan_depo_decline',
        'fu_jumlah',
        'fu_ke_1',
        'next_fu_2',
        'fu_2_checked',
        'fu_2_note',
        'next_fu_3',
        'fu_3_checked',
        'fu_3_note',
        'next_fu_4',
        'fu_4_checked',
        'fu_4_note',
        'next_fu_5',
        'fu_5_checked',
        'fu_5_note',
        // 'sheet_month',
        // 'notes',
        // 'followup_date',
        'is_archived',
        'archived_at',
        'archived_by'
    ];

    protected $casts = [
        'fu_checkbox_1' => 'boolean',
        'fu_checkbox_2' => 'boolean',
        'fu_checkbox_3' => 'boolean',
        'fu_checkbox_4' => 'boolean',
        'fu_checkbox_5' => 'boolean',
        // 'followup_date' => 'array',
        'is_archived' => 'boolean',
        'archived_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function archivedBy()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }

    // Accessor untuk WhatsApp link
    public function getWhatsappLinkAttribute()
    {
        if (!$this->phone) {
            return null;
        }
        
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        // Convert phone number format
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }
        
        $message = "Halo {$this->nama}, saya dari tim sales ingin melakukan follow up terkait interest Anda.";
        
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    // Accessor untuk status display
    public function getStatusDisplayAttribute()
    {
        $statusMap = [
            'normal' => 'Normal',
            'warm' => 'Warm',
            'hot' => 'Hot',
            'normal(prospect)' => 'Normal (Prospect)',
            'warm(potential)' => 'Warm (Potential)',
            'hot(closeable)' => 'Hot (Closeable)'
        ];

        return $statusMap[$this->status_fu] ?? $this->status_fu;
    }

    // Accessor untuk status badge color
    public function getStatusColorAttribute()
    {
        $colorMap = [
            'normal' => 'bg-gray-100 text-gray-800',
            'warm' => 'bg-yellow-100 text-yellow-800',
            'hot' => 'bg-red-100 text-red-800',
            'normal(prospect)' => 'bg-blue-100 text-blue-800',
            'warm(potential)' => 'bg-orange-100 text-orange-800',
            'hot(closeable)' => 'bg-green-100 text-green-800'
        ];

        return $colorMap[$this->status_fu] ?? 'bg-gray-100 text-gray-800';
    }

    // Check if follow up is overdue
    // public function getIsOverdueAttribute()
    // {
    //     return $this->followup_date && $this->followup_date->isPast() && !$this->fu_checkbox;
    // }

        public function getIsOverdueAttribute()
    {
        if (!$this->followup_date) return false;
        
        $today = Carbon::today()->format('Y-m-d');
        $dates = json_decode($this->followup_date, true);
        
        foreach ($dates as $dateItem) {
            $followupDate = Carbon::parse($dateItem['date']);
            if ($followupDate->lt(Carbon::today())) {
                return true;
            }
        }
        
        return false;
    }

    // Check if follow up is today
    // public function getIsFollowupTodayAttribute()
    // {
    //     return $this->followup_date && $this->followup_date->isToday();
    // }

        public function getTodayFollowupDateAttribute()
    {
        if (!$this->followup_date) return null;
        
        $today = Carbon::today()->format('Y-m-d');
        $dates = json_decode($this->followup_date, true);
        
        foreach ($dates as $dateItem) {
            if ($dateItem['date'] === $today) {
                return Carbon::parse($dateItem['date']);
            }
        }
        
        return null;
    }

    // Archive customer
    // Model Customer.php
public function archive($userId, $archiveType = 'keep')
{
    $this->update([
        'archived_at' => now(),
        'archived_by' => $userId,
        'archive_type' => $archiveType
    ]);
}

    public function moveToMaintain($userId)
    {
        $this->update([
            'archive_type' => 'maintain',
            'archived_by' => $userId,
            'archived_at' => now()
        ]);
    }

    // Scope untuk filter berdasarkan archive_type
    public function scopeKeepArchived($query)
    {
        return $query->whereNotNull('archived_at')
                    ->where('archive_type', 'keep');
    }

    public function scopeMaintainArchived($query)
    {
        return $query->whereNotNull('archived_at')
                    ->where('archive_type', 'maintain');
    }
    // Restore customer from archive
    public function restore()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null
        ]);
    }

    // Scope untuk filter
    public function scopeByStatus($query, $status)
    {
        $statusGroups = [
            'normal' => ['normal', 'normal(prospect)'],
            'warm' => ['warm', 'warm(potential)'],
            'hot' => ['hot', 'hot(closeable)']
        ];

        if (isset($statusGroups[$status])) {
            return $query->whereIn('status_fu', $statusGroups[$status]);
        }

        return $query->where('status_fu', $status);
    }

    public function scopeFollowupToday($query)
    {
        return $query->whereDate('followup_date', Carbon::today());
    }

    public function scopeOverdue($query)
    {
        return $query->where('followup_date', '<', Carbon::today())
                    ->where('fu_checkbox', false);
    }

    public function scopeByAgent($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeActive($query)
    {
        return $query->where('is_archived', false);
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }
    
}