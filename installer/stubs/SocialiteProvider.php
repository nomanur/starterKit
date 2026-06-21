<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialiteProvider: string
{
    case GitHub = 'github';
    case Google = 'google';
    case Facebook = 'facebook';
    case Twitter = 'twitter-oauth-2';
    case LinkedIn = 'linkedin-openid';
    case GitLab = 'gitlab';
    case Bitbucket = 'bitbucket';
    case Slack = 'slack-openid';

    public function label(): string
    {
        return match ($this) {
            self::GitHub => 'GitHub',
            self::Google => 'Google',
            self::Facebook => 'Facebook',
            self::Twitter => 'X (Twitter)',
            self::LinkedIn => 'LinkedIn',
            self::GitLab => 'GitLab',
            self::Bitbucket => 'Bitbucket',
            self::Slack => 'Slack',
        };
    }

    public function isConfigured(): bool
    {
        return filled(config('services.'.$this->value.'.client_id'))
            && filled(config('services.'.$this->value.'.client_secret'));
    }

    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    public static function configured(): array
    {
        return array_filter(self::cases(), fn (self $provider) => $provider->isConfigured());
    }
}
