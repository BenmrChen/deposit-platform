<?php

namespace Modules\Crypto\Http\Controllers\ShopApi\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Passport\Http\Resources\UserResource;

class MeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/v1/me",
     *     tags={"users"},
     *     summary="get information about current login account",
     *     security={{"session": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="success.",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="data",
     *                     type="object",
     *                     ref="#/components/schemas/UserResource",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return (new UserResource(Auth::user()))
            ->response()
            ->setStatusCode(200);
    }
}
