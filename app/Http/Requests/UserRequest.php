<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Crypt;


class UserRequest extends FormRequest
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
        if (isset(request()->id)) {

            $id = Crypt::decrypt(request()->id);
            return [
                'name' => 'required|string|max:100|regex:/(^([a-zA-Z])$)/u',
                'email' => 'required|string|email|max:200|unique:users,email,'.$id,
                'password' => isset(request()->password) ? 'required|string|min:8|confirmed|regex:/(^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)/u' : null,
            ];
        }

        return [
            'name' => 'required|string|max:100|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'email' => 'required|string|email|max:200|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/(^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)/u',
        ];
    }



}
