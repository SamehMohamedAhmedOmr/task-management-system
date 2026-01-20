<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AddDependencyRequest",
 *     title="Add Dependency Request",
 *     description="Request body for adding a task dependency",
 *     required={"depends_on_task_id"},
 *     @OA\Property(property="depends_on_task_id", type="integer", example=2)
 * )
 */
class AddDependencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->role->key === Role::MANAGER;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'depends_on_task_id' => ['required', 'exists:tasks,id', 'different:task'],
        ];
    }
}
