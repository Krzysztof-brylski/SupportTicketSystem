<?php

namespace App\Rules;

use App\Enums\UserRolesEnum;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UserIsAgent implements Rule
{

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (User::where('id',$value)->first()->role) == UserRolesEnum::AGENT;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Selected user must have AGENT role';
    }
}
