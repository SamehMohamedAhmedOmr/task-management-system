<?php

namespace App\Http\Requests;

use App\Constants\TaskStatus;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreTaskRequest",
 *     title="Store Task Request",
 *     description="Request body for creating a task",
 *     required={"title", "status", "assigned_to"},
 *     @OA\Property(property="title", type="string", example="New Task"),
 *     @OA\Property(property="description", type="string", example="Task details"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "canceled"}, example="pending"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2024-12-31"),
 *     @OA\Property(property="assigned_to", type="integer", example=2)
 * )
 */
class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:' . implode(',', [TaskStatus::PENDING, TaskStatus::IN_PROGRESS, TaskStatus::COMPLETED, TaskStatus::CANCELED])],
            'due_date' => ['nullable', 'date', 'after:today'],
            'assigned_to' => ['required', 'exists:users,id'],
        ];
    }
}
