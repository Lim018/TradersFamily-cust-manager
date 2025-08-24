<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'agent_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Relationship dengan Maintain data
    public function maintainData()
    {
        return $this->hasMany(Maintain::class, 'agent_code', 'agent_code');
    }

    // Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Check if user is agent
    public function isAgent()
    {
        return $this->role === 'agent';
    }

    // Get agent display name
    public function getAgentDisplayAttribute()
    {
        return $this->agent_code ? "{$this->name} ({$this->agent_code})" : $this->name;
    }

    // Get full agent info
    public function getFullAgentInfoAttribute()
    {
        return $this->agent_code ? "Agent {$this->agent_code} - {$this->name}" : $this->name;
    }
}