<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Every workspace record belongs to exactly one freelancer, who alone may see or change it.
 */
class OwnedRecordPolicy
{
    public function view(User $user, Model $record): bool
    {
        return $this->owns($user, $record);
    }

    public function update(User $user, Model $record): bool
    {
        return $this->owns($user, $record);
    }

    public function delete(User $user, Model $record): bool
    {
        return $this->owns($user, $record);
    }

    private function owns(User $user, Model $record): bool
    {
        return $record->getAttribute('user_id') === $user->id;
    }
}
