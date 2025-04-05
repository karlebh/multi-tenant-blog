<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'   => ['required', 'exists:users,id'],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'post_id'   => ['required', 'exists:posts,id'],
            'content'   => ['required', 'string'],
            'files.*'     => ['file', 'mimes:jpg,png,pdf,docx', 'max:2048'],
        ];
    }
}
