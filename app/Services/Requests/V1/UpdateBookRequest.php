<?php

declare(strict_types=1);

namespace App\Services\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    public function rules(): array
    {
        $method = $this->method();
        $sometimes = null;
        if ($method == 'PATCH') {
            $sometimes = 'sometimes';
        }
        return [
            'name' => [$sometimes, 'required'],
            'author' => [$sometimes, 'required'],
            'year' => [$sometimes, 'required', 'integer'],
            'genre' => [$sometimes, 'required'],
            'pages' => [$sometimes, 'required', 'integer'],
            'language' => [$sometimes, 'required'],
            'quantity' => [$sometimes, 'required', 'integer']
        ];
    }
}
