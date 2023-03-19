<?php

namespace Modules\Crypto\Http\Resources\DepositApi;

use App\Http\Resources\ResourceJsonBase;
use Illuminate\Http\Request;

class UserResource extends ResourceJsonBase
{
    /**
     * @OA\Schema(
     *     schema="UserResource",
     *     type="object",
     *     @OA\Property(property="id", type="string", description="id", format="uuid", example="9505cd79-7c13-4df1-9ce7-f3c794823976"),
     *     @OA\Property(property="email", type="string", description="email", example="test-gamer@getoken.io"),
     *     @OA\Property(property="createTime", type="string", description="date-time", example="2020-01-01T08:00:00+00:00"),
     *     @OA\Property(property="updateTime", type="string", description="date-time", example="2020-01-01T08:00:00+00:00"),
     * )
     */
    protected function getPayload(Request $request): array
    {
        return [
            'id'         => $this->uuid,
            'email'      => $this->email,
            'createTime' => $this->created_at,
            'updateTime' => $this->updated_at,
        ];
    }
}
