<?php

namespace App\Policies;

use App\Models\CourseInstruction;
use App\Models\User;

class CourseInstructionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CourseInstruction $instruction): bool
    {
        return $user->id === $instruction->instructor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === 'instructor';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CourseInstruction $instruction): bool
    {
        return $user->id === $instruction->instructor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CourseInstruction $instruction): bool
    {
        return $user->id === $instruction->instructor_id;
    }
}
