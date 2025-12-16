<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\ValidEmail;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateUserManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_admin ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var \App\Models\User|null $user */
        $user = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user?->id),
                new ValidEmail,
            ],
        ];
    }
}
