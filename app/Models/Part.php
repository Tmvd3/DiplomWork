<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Part extends Model
{
    protected $fillable = [
        'car_id',
        'name',
        'category',
        'replacement_interval',
        'last_replacement_mileage',
        'next_replacement_mileage',
        'status',
        'notes'
    ];


    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }


    public function updateStatus(): void
    {
        $this->status = $this->car->getPartStatus($this);
        $this->save();
    }


    public function markAsReplaced(): void
    {
        $this->last_replacement_mileage = $this->car->current_mileage;
        $this->next_replacement_mileage = $this->car->current_mileage + $this->replacement_interval;
        $this->status = 'good';
        $this->save();
    }


    public function getStatusColor(): string
    {
        return match($this->status) {
            'good' => '#28a745',    // зеленый
            'warning' => '#ffc107', // желтый
            'danger' => '#dc3545',  // красный
            default => '#6c757d',   // серый
        };
    }


    public function getStatusClass(): string
    {
        return match($this->status) {
            'good' => 'success',
            'warning' => 'warning',
            'danger' => 'danger',
            default => 'secondary',
        };
    }
}
