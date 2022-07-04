<?php

namespace App\Http\Requests;

use App\Rules\CardNumber;
use App\Rules\TransactionRange;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
	    return [
		    'card_source' => ['required', 'string', new CardNumber()],
		    'card_destination' => ['required', 'string', new CardNumber()],
		    'amount' => ['required', 'integer', new TransactionRange()]
	    ];
    }


	protected function prepareForValidation()
	{
		$this->merge([
			'amount' => to_english_numbers($this->input('amount')),
			'card_source' => to_english_numbers($this->input('card_source')),
			'card_destination' => to_english_numbers($this->input('card_destination'))
		]);
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json([
			'success' => false,
			'response'    => $validator->errors(),
		]));
	}
}
