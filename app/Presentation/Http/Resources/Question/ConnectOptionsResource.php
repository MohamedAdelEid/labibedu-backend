<?php

namespace App\Presentation\Http\Resources\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectOptionsResource extends JsonResource
{
    private bool $shuffle;

    public function __construct($resource, bool $shuffle = false)
    {
        parent::__construct($resource);
        $this->shuffle = $shuffle;
    }

    public function toArray(Request $request): array
    {
        $leftOptions = $this->resource->where('side', 'left')->map(function ($option) {
            return [
                'id' => $option->id,
                'text' => $option->text,
                'image' => $option->image,
            ];
        })->values();

        $rightOptions = $this->resource->where('side', 'right')->map(function ($option) {
            return [
                'id' => $option->id,
                'text' => $option->text,
                'image' => $option->image,
            ];
        })->values();

        // Shuffle if requested (for training)
        if ($this->shuffle) {
            $leftOptions = $leftOptions->shuffle()->values();
            $rightOptions = $rightOptions->shuffle()->values();
        }

        return [
            'left' => $leftOptions,
            'right' => $rightOptions,
        ];
    }
}