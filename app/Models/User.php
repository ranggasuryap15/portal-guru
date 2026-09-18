<?php

/**
 * ==============================================================================
 * Tujuan: Model Pengguna (User) yang mencakup role Admin dan Guru dengan dukungan multi-mapel.
 * Dipakai Oleh: AuthController, TeacherController, RoleMiddleware, Guard Auth
 * Dependensi: Illuminate\Foundation\Auth\User, TeachingAssignment, Subject
 * Daftar Fungsi Utama: isAdmin(), isGuru(), teachingAssignments(), subjects()
 * Side Effect: Query DB users, autentikasi session
 * ==============================================================================
 */

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'phone',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Type casting atribut.
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

    /**
     * Cek apakah user adalah administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah guru.
     */
    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    /**
     * Relasi ke penugasan mengajar guru.
     */
    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'teacher_id');
    }

    /**
     * Relasi ke seluruh mata pelajaran yang diampu guru ini (bisa multi-mapel).
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teaching_assignments', 'teacher_id', 'subject_id')
            ->withPivot(['classroom_id', 'academic_year', 'semester'])
            ->distinct();
    }
}
