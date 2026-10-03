<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionEventType: string
{
    case Created = 'created';
    case PlanChanged = 'plan_changed';
    case Renewed = 'renewed';
    case PastDue = 'past_due';
    case Cancelled = 'cancelled';
    case Expired = 'expired';
}
