<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'faculty_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin', 'admin_faculty']);
    }

    public function canAccessFaculty(int $facultyId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->faculty_id === $facultyId;
    }

    public function getAccessibleFacultyIds(): array
    {
        if ($this->isSuperAdmin()) {
            return Faculty::pluck('id')->toArray();
        }

        return $this->faculty_id ? [$this->faculty_id] : [];
    }
}
