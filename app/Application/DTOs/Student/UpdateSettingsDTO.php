<?php

namespace App\Application\DTOs\Student;

class UpdateSettingsDTO
{
    public function __construct(
        public readonly ?string $language = null,
        public readonly ?string $theme = null,
        public readonly ?bool $notificationsEnabled = null,
        public readonly ?bool $hapticFeedbackEnabled = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            language: $data['language'] ?? null,
            theme: $data['theme'] ?? null,
            notificationsEnabled: isset($data['notifications_enabled']) ? (bool) $data['notifications_enabled'] : null,
            hapticFeedbackEnabled: isset($data['haptic_feedback_enabled']) ? (bool) $data['haptic_feedback_enabled'] : null,
        );
    }
}

