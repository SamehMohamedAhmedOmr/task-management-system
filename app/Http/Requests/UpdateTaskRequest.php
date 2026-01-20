<?php

namespace App\Http\Requests;

use App\Constants\TaskStatus;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateTaskRequest",
 *     title="Update Task Request",
 *     description="Request body for updating a task",
 *     @OA\Property(property="title", type="string", example="Updated Task"),
 *     @OA\Property(property="description", type="string", example="Updated details"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "canceled"}, example="completed"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2024-12-31"),
 *     @OA\Property(property="assigned_to", type="integer", example=2)
 * )
 */
class UpdateTaskRequest extends FormRequest
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
        $rules = [
            'status' => ['sometimes', 'required', 'in:' . implode(',', [TaskStatus::PENDING, TaskStatus::IN_PROGRESS, TaskStatus::COMPLETED, TaskStatus::CANCELED])],
        ];

        if ($this->user()->role->key === Role::MANAGER) {
            $rules = array_merge($rules, [
                'title' => ['sometimes', 'required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'due_date' => ['nullable', 'date', 'after:today'],
                'assigned_to' => ['sometimes', 'required', 'exists:users,id'],
            ]);
        }

        return $rules;
    }
}
