<?php

namespace App\Application\DTOs\Avatar;

use App\Presentation\Http\Requests\Avater\CreateAvatarRequest;
use Illuminate\Http\UploadedFile;

class CreateAvatarDTO
{
    public function __construct(
        public readonly int $categoryId,
        public readonly UploadedFile $avatar,
        public readonly int $coins = 0
    ) {
    }

    public static function fromRequest(CreateAvatarRequest $request): self
    {
        return new self(
            categoryId: $request->category_id,
            avatar: $request->avatar,
            coins: $request->coins
        );
    }
}
