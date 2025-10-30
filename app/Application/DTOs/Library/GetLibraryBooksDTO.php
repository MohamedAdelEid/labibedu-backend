<?php

namespace App\Application\DTOs\Library;

use App\Domain\Enums\LibraryScope;

class GetLibraryBooksDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly LibraryScope $scope = LibraryScope::ALL,
        public readonly ?int $levelId = null,
        public readonly ?string $search = null,
        public readonly int $page = 1,
        public readonly int $perPage = 10,
    ) {
    }

    public static function fromRequest(array $data, int $studentId): self
    {
        $scopeValue = $data['scope'] ?? 'all';
        $scope = LibraryScope::fromRequest($scopeValue);

        return new self(
            studentId: $studentId,
            scope: $scope,
            levelId: $data['level_id'] ?? null,
            search: $data['search'] ?? null,
            page: $data['page'] ?? 1,
            perPage: $data['per_page'] ?? 10,
        );
    }
}

