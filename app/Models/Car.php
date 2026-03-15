<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = [
        'user_id',
        'brand',
        'model',
        'year',
        'vin',
        'license_plate',
        'color',
        'initial_mileage',
        'current_mileage',
        'image'
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }


    public function getRemainingMileageForPart(Part $part): int
    {
        $mileageSinceReplacement = $this->current_mileage - $part->last_replacement_mileage;
        return max(0, $part->replacement_interval - $mileageSinceReplacement);
    }


    public function getPartWearPercentage(Part $part): int
    {
        $mileageSinceReplacement = $this->current_mileage - $part->last_replacement_mileage;
        $percentage = ($mileageSinceReplacement / $part->replacement_interval) * 100;

        return min(100, max(0, $percentage));
    }


    public function getPartStatus(Part $part): string
    {
        $percentage = $this->getPartWearPercentage($part);

        if ($percentage >= 100) {
            return 'danger';
        } elseif ($percentage >= 80) {
            return 'warning';
        } else {
            return 'good';
        }
    }


    public function getPartsNeedingReplacement(): array
    {
        return $this->parts->filter(function ($part) {
            return $this->getPartStatus($part) === 'danger';
        })->all();
    }
}
