<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SuccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public $message;
    public $status;

    public function __construct($request = '')
    {
        $this->message = isset($request['message']) ? $request['message'] : '';
        $this->status = isset($request['status']) ? $request['status'] : '';
    }

    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message ? $this->message : 'Success'
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        return $response->setStatusCode($this->status ? $this->status : 200);
    }
}
