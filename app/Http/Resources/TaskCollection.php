<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TaskCollection",
 *     title="Task Collection",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Task")),
 *     @OA\Property(property="pagination", type="object",
 *         @OA\Property(property="total", type="integer"),
 *         @OA\Property(property="count", type="integer"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="total_pages", type="integer")
 *     )
 * )
 */
class TaskCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = TaskResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'total_pages' => $this->lastPage(),
            ],
        ];
    }
}
