<?php

namespace App\Policies;

use App\Models\Car;
use App\Models\User;

class CarPolicy
{
    /**
     * Просмотр автомобиля — только владелец.
     */
    public function view(User $user, Car $car): bool
    {
        return (int) $car->user_id === (int) $user->id;
    }

    /**
     * Редактирование автомобиля — только владелец.
     */
    public function update(User $user, Car $car): bool
    {
        return (int) $car->user_id === (int) $user->id;
    }

    /**
     * Удаление автомобиля — только владелец.
     */
    public function delete(User $user, Car $car): bool
    {
        return (int) $car->user_id === (int) $user->id;
    }
}
