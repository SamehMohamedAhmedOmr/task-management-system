<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Pagination",
 *     title="Pagination",
 *     description="Pagination metadata for paginated responses",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="first_page_url", type="string", example="http://api.test/api/tasks?page=1"),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="last_page", type="integer", example=10),
 *     @OA\Property(property="last_page_url", type="string", example="http://api.test/api/tasks?page=10"),
 *     @OA\Property(property="next_page_url", type="string", nullable=true, example="http://api.test/api/tasks?page=2"),
 *     @OA\Property(property="path", type="string", example="http://api.test/api/tasks"),
 *     @OA\Property(property="per_page", type="integer", example=15),
 *     @OA\Property(property="prev_page_url", type="string", nullable=true, example=null),
 *     @OA\Property(property="to", type="integer", example=15),
 *     @OA\Property(property="total", type="integer", example=145)
 * )
 */
class PaginationResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'current_page' => $this['current_page'],
            'first_page_url' => $this['first_page_url'],
            'from' => $this['from'],
            'last_page' => $this['last_page'],
            'last_page_url' => $this['last_page_url'],
            'next_page_url' => $this['next_page_url'],
            'path' => $this['path'],
            'per_page' => $this['per_page'],
            'prev_page_url' => $this['prev_page_url'],
            'to' => $this['to'],
            'total' => $this['total'],
        ];
    }
}