<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TransactionRange implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if( $value < 10000 || $value > 500000000 ) // Between 10,000 Rial and 500 Million Rial
        	return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Amount must be between 10000 and 500 million Rial.';
    }
}
