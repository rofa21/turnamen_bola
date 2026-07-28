<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    protected $fillable = [
        'team_id',
        'age_category_id',
        'name',
        'nik',
        'birth_date',
        'birth_place',
        'jersey_number',
        'position',
        'registration_number',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PlayerDocument::class);
    }

    public function verification(): HasOne
    {
        return $this->hasOne(PlayerVerification::class);
    }

    /**
     * Get birth year from birth_date
     */
    public function getBirthYearAttribute(): int
    {
        return (int) $this->birth_date->format('Y');
    }

    /**
     * Auto-verify age against age category limits
     */
    public function checkAgeValidity(): bool
    {
        $category = $this->ageCategory;
        if (! $category) {
            return false;
        }

        return $category->isBirthYearValid($this->birth_year);
    }

    /**
     * Generate registration number: e.g. KU10-2026-001
     */
    public static function generateRegistrationNumber(int $ageCategoryId, int $year): string
    {
        $category = AgeCategory::find($ageCategoryId);
        $prefix = strtoupper(str_replace(['-', ' '], '', $category->name ?? 'KU'));
        
        $seq = static::where('age_category_id', $ageCategoryId)->count() + 1;

        do {
            $regNumber = "{$prefix}-{$year}-".str_pad($seq, 3, '0', STR_PAD_LEFT);
            $seq++;
        } while (static::where('registration_number', $regNumber)->exists());

        return $regNumber;
    }

    public function getFotoUrlAttribute(): ?string
    {
        $fotoDoc = $this->documents->firstWhere('type', 'foto');
        if ($fotoDoc && $fotoDoc->file_path) {
            return asset('storage/'.$fotoDoc->file_path);
        }

        return null;
    }
}
