<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Banner\BannerResource;
use App\Http\Resources\Banner\BannerResourceCollection;
use App\Http\Resources\errorResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BannerController extends Controller
{
    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        $user = request()->user();

        try {
            $data = Banner::where('user_id', $user->user_id)
                ->where('status', true)
                ->orderBy('id', 'desc')
                ->get();

            return BannerResource::collection($data);
        } catch (\Throwable $th) {
            Log::warning($th->getMessage());
            return new errorResource();
        }
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
