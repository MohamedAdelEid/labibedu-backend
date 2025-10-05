<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamTrainingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => app()->getLocale() === 'ar' ? $this->title_ar : $this->title,
            'description' => app()->getLocale() === 'ar' ? $this->description_ar : $this->description,
            'type' => $this->type->value,
            'group' => $this->group ? [
                'id' => $this->group->id,
                'name' => $this->group->name,
            ] : null,
            'classroom' => $this->group->classroom ? [
                'id' => $this->group->classroom->id,
                'name' => $this->group->classroom->name,
            ] : null,
            'school' => $this->group->classroom->school ? [
                'id' => $this->group->classroom->school->id,
                'name' => $this->group->classroom->school->name,
            ] : null,
            'duration' => $this->duration,
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'lockedAfterDuration' => $this->locked_after_duration,
            'subject' => $this->subject ? [
                'id' => $this->subject->id,
                'name' => $this->subject->name,
            ] : null,
            'createdBy' => $this->creator ? [
                'id' => $this->creator->id,
                'name' => $this->creator->user->name,
            ] : null,
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}