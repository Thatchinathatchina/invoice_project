<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BaseFormRequest
 * 
 * Centralizes the authorize logic so we don't have to write
 * `public function authorize() { return true; }` in every single request class.
 */
abstract class BaseFormRequest extends FormRequest
{
    /**
     * By default, anyone can submit these forms if they reach the route.
     * Real authorization is handled via Middleware and Policies.
     */
    public function authorize(): bool
    {
        return true;
    }
}
