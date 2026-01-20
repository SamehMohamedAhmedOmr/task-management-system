<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Role",
 *     description="Role model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="key", type="string", example="manager"),
 *     @OA\Property(property="name", type="string", example="Manager"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Role extends Model
{
    use HasFactory;

    public const MANAGER = 'manager';
    public const USER = 'user';

    protected $fillable = ['key', 'name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
