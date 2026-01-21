<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TaskCollection",
 *     title="Task Collection",
 *     description="Paginated list of tasks",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Task")
 *     ),
 *     @OA\Property(
 *         property="pagination",
 *         ref="#/components/schemas/Pagination"
 *     )
 * )
 */

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'due_date' => $this->due_date ? $this->due_date->format('Y-m-d') : null,
            'assigned_to' => UserResource::make($this->whenLoaded('assignedTo')),
            'created_by' => UserResource::make($this->whenLoaded('createdBy')),
            'dependencies' => TaskResource::collection($this->whenLoaded('dependencies')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
