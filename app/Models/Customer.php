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
        'fu_notes_1',
        'fu_checked_1',
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
        'is_archived',
        'archived_at',
        'archived_by'
    ];

    protected $casts = [
        'fu_checked_1' => 'boolean',
        'fu_2_checked' => 'boolean',
        'fu_3_checked' => 'boolean',
        'fu_4_checked' => 'boolean',
        'fu_5_checked' => 'boolean',
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

    // Check if customer has follow-up today
    public function hasFollowupToday()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        return $this->fu_ke_1 == $today ||
               $this->next_fu_2 == $today ||
               $this->next_fu_3 == $today ||
               $this->next_fu_4 == $today ||
               $this->next_fu_5 == $today;
    }

    // Check if customer has pending follow-up today (not completed)
    public function hasPendingFollowupToday()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // First follow-up is always pending if today
        if ($this->fu_ke_1 == $today) {
            return true;
        }
        
        // Check other follow-ups (only pending if not completed)
        for ($i = 2; $i <= 5; $i++) {
            $fuField = "next_fu_{$i}";
            $checkedField = "fu_{$i}_checked";
            
            if ($this->$fuField == $today && !$this->$checkedField) {
                return true;
            }
        }
        
        return false;
    }

    // Check if customer has completed follow-up today
    public function hasCompletedFollowupToday()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // Check follow-ups 2-5 that are scheduled today and completed
        for ($i = 2; $i <= 5; $i++) {
            $fuField = "next_fu_{$i}";
            $checkedField = "fu_{$i}_checked";
            
            if ($this->$fuField == $today && $this->$checkedField) {
                return true;
            }
        }
        
        return false;
    }

    // Get today's follow-up details
    public function getTodayFollowupDetails()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        if ($this->fu_ke_1 == $today) {
            return [
                'type' => 'First Follow-up',
                'number' => 1,
                'is_completed' => false, // First FU doesn't have checkbox
                'date' => $this->fu_ke_1,
                'note' => null
            ];
        }
        
        for ($i = 2; $i <= 5; $i++) {
            if ($this->{"next_fu_{$i}"} == $today) {
                return [
                    'type' => $this->getFollowupTypeText($i),
                    'number' => $i,
                    'is_completed' => $this->{"fu_{$i}_checked"} ?? false,
                    'date' => $this->{"next_fu_{$i}"},
                    'note' => $this->{"fu_{$i}_note"}
                ];
            }
        }
        
        return null;
    }

    private function getFollowupTypeText($number)
    {
        $types = [
            2 => '2nd Follow-up',
            3 => '3rd Follow-up', 
            4 => '4th Follow-up',
            5 => '5th Follow-up'
        ];
        
        return $types[$number] ?? "{$number}th Follow-up";
    }

    // Mark specific follow-up as completed
    public function markFollowupCompleted($followupNumber)
    {
        if ($followupNumber >= 2 && $followupNumber <= 5) {
            $this->update([
                "fu_{$followupNumber}_checked" => true
            ]);
        }
    }

    // Get WhatsApp link
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
        
        $message = "Halo {$this->nama}, ini dari Traders Family. Ada waktu untuk follow-up hari ini?";
        
        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    // Get status display
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

        return $statusMap[$this->status_fu] ?? ($this->status_fu ?: 'No Status');
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        $colorMap = [
            'normal' => 'bg-green-100 text-green-800',
            'warm' => 'bg-yellow-100 text-yellow-800',
            'hot' => 'bg-red-100 text-red-800',
            'normal(prospect)' => 'bg-green-100 text-green-800',
            'warm(potential)' => 'bg-yellow-100 text-yellow-800',
            'hot(closeable)' => 'bg-red-100 text-red-800'
        ];

        return $colorMap[$this->status_fu] ?? 'bg-gray-100 text-gray-800';
    }

    // Archive methods
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

    // Restore customer from archive
    public function restore()
    {
        $this->update([
            'is_archived' => false,
            'archived_at' => null,
            'archived_by' => null
        ]);
    }

    // Scopes
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

    // Scope for follow-up today - checks all follow-up dates
    public function scopeFollowupToday($query)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        return $query->where(function($q) use ($today) {
            $q->where('fu_ke_1', $today)
              ->orWhere('next_fu_2', $today)
              ->orWhere('next_fu_3', $today)
              ->orWhere('next_fu_4', $today)
              ->orWhere('next_fu_5', $today);
        });
    }

    // Scope for pending follow-up today (not completed)
    public function scopePendingFollowupToday($query)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        return $query->where(function($q) use ($today) {
            // First follow-up (always pending as it has no checkbox)
            $q->where('fu_ke_1', $today)
              // Other follow-ups that are not checked
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_2', $today)->where('fu_2_checked', false);
              })
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_3', $today)->where('fu_3_checked', false);
              })
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_4', $today)->where('fu_4_checked', false);
              })
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_5', $today)->where('fu_5_checked', false);
              });
        });
    }

    public function scopeOverdue($query)
    {
        $today = Carbon::today()->format('Y-m-d');
        
        return $query->where(function($q) use ($today) {
            // First follow-up overdue
            $q->where('fu_ke_1', '<', $today)
              // Other follow-ups overdue and not completed
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_2', '<', $today)->where('fu_2_checked', false);
              })
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_3', '<', $today)->where('fu_3_checked', false);
              })
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_4', '<', $today)->where('fu_4_checked', false);
              })
              ->orWhere(function($subQ) use ($today) {
                  $subQ->where('next_fu_5', '<', $today)->where('fu_5_checked', false);
              });
        });
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