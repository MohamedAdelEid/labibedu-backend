<?php

namespace App\Presentation\Http\Resources\Student;

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
            'avatar' => $this->resource['avatar'],
            'coins' => $student->coins,
            'xp' => $student->xp,
            'grade' => $student->group ? $student->group->name : null,
            'school' => $student->school ? $student->school->name : null,
            'total_time_spent' => $this->resource['total_time_spent'],
            'questions' => $this->resource['question_stats'],
            'books' => [
                'read' => $this->resource['books_read'],
            ],
        ];
    }
}
