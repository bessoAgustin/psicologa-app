<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
    Validator::make($input, [
        ...$this->profileRules(),
        'password' => $this->passwordRules(),
    ])->validate();

    $user = User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => $input['password'],
    ]);

    $nombreParts = explode(' ', $user->name, 2);

    \App\Models\Paciente::create([
    'user_id' => $user->id,
    'nombre' => $nombreParts[0],
    'apellido' => $input['apellido'] ?? ($nombreParts[1] ?? ''),
    'email' => $user->email,
    'prefijo' => $input['prefijo'] ?? '',
    'telefono' => $input['telefono'] ?? '',
    ]);

    return $user;
    }
}
