<?php

namespace App\Services\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreBooksRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    public function rules(): array
    {

        return [
            '*.name' => ['required'],
            '*.author' => ['required'],
            '*.year' => ['required', 'integer'],
            '*.genre' => ['required'],
            '*.pages' => ['required', 'integer'],
            '*.language' => ['required'],
            '*.quantity' => ['required', 'integer']
        ];
    }
}
