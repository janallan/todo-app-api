<?php

namespace App\Http\Resources\V1_0_0;

use Illuminate\Http\Resources\Json\JsonResource;

class LoginResponseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'access_token' => $this['token'],
            'token_type' => 'bearer',
            'user' => UserResource::make($this['user']),
        ];
    }
}
