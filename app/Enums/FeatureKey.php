<?php

declare(strict_types=1);

namespace App\Enums;

enum FeatureKey: string
{
    case MaxUsers = 'max_users';
    case MaxCustomers = 'max_customers';
    case ApiRatePerMinute = 'api_rate_per_minute';
    case CustomerExport = 'customer_export';
    case AnalyticsTrends = 'analytics_trends';

    public function isQuantity(): bool
    {
        return match ($this) {
            self::MaxUsers, self::MaxCustomers, self::ApiRatePerMinute => true,
            self::CustomerExport, self::AnalyticsTrends => false,
        };
    }
}
