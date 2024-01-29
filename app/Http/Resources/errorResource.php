<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class errorResource extends JsonResource
{
    public static $wrap = '';
    public $message;
    public $status;

    public function __construct($request = '')
    {
        $this->message = isset($request['message']) ? $request['message'] : '';
        $this->status = isset($request['status']) ? $request['status'] : '';
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => $this->message ? $this->message : 'Terjadi kesalahan, cobalah kembali'
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        return $response->setStatusCode($this->status ? $this->status : 500);
    }
}
