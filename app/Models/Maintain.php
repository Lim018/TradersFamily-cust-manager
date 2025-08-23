<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintain extends Model
{
    protected $table = 'maintain';
    
   protected $fillable = [
        'agent_code',
        'nama',
        'tanggal',
        'regis',
        'alasan_depo',
        'deposit',
        'wd',
        'nmi',
        'lot',
        'profit',
        'last_balance',
        'status_data',
        'upsell',
        'fu_jumlah',
        'fu_1_date',
        'fu_1_checked',
        'fu_1_note',
        'fu_2_date',
        'fu_2_checked',
        'fu_2_note',
        'fu_3_date',
        'fu_3_checked',
        'fu_3_note',
        'fu_4_date',
        'fu_4_checked',
        'fu_4_note',
        'fu_5_date',
        'fu_5_checked',
        'fu_5_note',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'deposit' => 'decimal:2',
        'wd' => 'decimal:2',
        'nmi' => 'decimal:2',
        'lot' => 'decimal:2',
        'profit' => 'decimal:2',
        'last_balance' => 'decimal:2',
        'fu_jumlah' => 'integer',
        'fu_1_date' => 'date',
        'fu_1_checked' => 'boolean',
        'fu_2_date' => 'date',
        'fu_2_checked' => 'boolean',
        'fu_3_date' => 'date',
        'fu_3_checked' => 'boolean',
        'fu_4_date' => 'date',
        'fu_4_checked' => 'boolean',
        'fu_5_date' => 'date',
        'fu_5_checked' => 'boolean',
    ];
}