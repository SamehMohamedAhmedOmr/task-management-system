<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Task",
 *     title="Task",
 *     description="Task model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Implement Login"),
 *     @OA\Property(property="description", type="string", example="Create login endpoint with validation"),
 *     @OA\Property(property="status", type="string", enum={"pending", "in_progress", "completed", "canceled"}, example="pending"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2024-12-31"),
 *     @OA\Property(property="assigned_to", type="integer", example=2),
 *     @OA\Property(property="created_by", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Task extends Model
{
    use HasFactory;

    public const PENDING = 'pending';
    public const IN_PROGRESS = 'in_progress';
    public const COMPLETED = 'completed';
    public const CANCELED = 'canceled';

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_date',
        'assigned_to',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dependencies(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id');
    }

    public function dependentTasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id');
    }
}
