<?php


namespace App\Http\Requests;


class UserPostRequest extends RestfulRequest
{
    public function rules() :array
    {
        return [
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email',
            'role_id' => 'int|required|exists:roles,id',
            'phone' => 'string|min:8|required',
            'address' => 'string|required',
            'time' => 'date',
            'password' => 'string|required'
        ];
    }
}