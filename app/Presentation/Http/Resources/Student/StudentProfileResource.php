<?php

namespace App\Presentation\Http\Resources\Student;

use App\Presentation\Http\Resources\Avatar\AvatarResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $student = $this->resource['student'];

        return [
            'name' => $student->user->name,
            'avatar' => new AvatarResource($this->resource['avatar'] ?? null),
            'coins' => $student->coins,
            'xp' => $student->xp,
            'grade' => $student->classroom ? [
                'id' => $student->classroom->grade->id,
                'name' => $student->classroom->grade->name,
                'level' => $student->classroom->grade->level,
            ] : null,
            'school' => $student->school ? [
                'id' => $student->school->id,
                'name' => $student->school->name,
            ] : null,
            'total_time_spent' => $this->resource['total_time_spent'],
            'questions' => $this->resource['question_stats'],
            'books' => [
                'read' => $this->resource['books_read'],
            ],
        ];
    }
}
