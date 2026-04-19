<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Use validation rules from User model
        $rules = User::registrationRules();

        // Add terms validation if needed
        if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
            $rules['terms'] = ['accepted', 'required'];
        }

        // Add password rules
        $rules['password'] = $this->passwordRules();

        Validator::make($input, $rules)->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
