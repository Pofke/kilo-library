<?php

declare(strict_types=1);

namespace App\Requests\V1;

use DateTime;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property int $bookId
 * @property int $userId
 * @property ?string $status
 * @property ?DateTime $extendedDate
 * @property ?DateTime $returnedDate
 */
class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('create');
    }

    public function rules(): array
    {
        return [
            'bookId' => ['required', 'integer'],
            'userId' => ['required', 'integer'],
            'status' => ['sometimes', 'required', Rule::in(['T', 'E', 'R'])],
            'extendedDate' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
            'returnedDate' => ['sometimes', 'required', 'date_format:Y-m-d H:i:s'],
        ];
    }


    protected function prepareForValidation(): void
    {
        $data = [];
        $data['book_id'] = $this->bookId;
        $data['user_id'] = $this->userId;
        if ($this->extendedDate) {
            $data['extended_date'] = $this->extendedDate;
        }
        if ($this->returnedDate) {
            $data['returned_date'] = $this->returnedDate;
        }
        $this->merge($data);
    }
}
