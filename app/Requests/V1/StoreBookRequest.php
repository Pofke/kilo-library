<?php

declare(strict_types=1);

namespace App\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    public function rules(): array
    {

        return [
            'name' => ['required'],
            'author' => ['required'],
            'year' => ['required', 'integer'],
            'genre' => ['required'],
            'pages' => ['required', 'integer'],
            'language' => ['required'],
            'quantity' => ['required', 'integer']
        ];
    }
}
