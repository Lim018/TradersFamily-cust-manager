<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'action',
        'description',
        'old_data',
        'new_data'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Get formatted changes
    public function getChangesAttribute()
    {
        if (!$this->old_data || !$this->new_data) {
            return [];
        }

        $changes = [];
        $fieldsToTrack = [
            'status_fu' => 'Status',
            'notes' => 'Notes',
            'followup_date' => 'Follow-up Date',
            'fu_checkbox' => 'Follow-up Completed',
            'tanggal_closing' => 'Closing Date',
            'report' => 'Report'
        ];

        foreach ($fieldsToTrack as $field => $label) {
            if (isset($this->old_data[$field]) && isset($this->new_data[$field])) {
                if ($this->old_data[$field] !== $this->new_data[$field]) {
                    $changes[] = [
                        'field' => $label,
                        'old' => $this->old_data[$field],
                        'new' => $this->new_data[$field]
                    ];
                }
            }
        }

        return $changes;
    }
}