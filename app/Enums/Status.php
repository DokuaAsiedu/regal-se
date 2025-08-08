<?php

namespace App\Enums;

enum Status: string
{
    case active = 'active';
    case inActive = 'inActive';
    case pending = 'pending';
    case approved = 'approved';
    case completed = 'completed';
    case declined = 'declined';
    case rejected = 'rejected';
    case suspended = 'suspended';
    case success = 'success';
    case failed = 'failed';
    case abandoned = 'abandoned';
    case cancelled = 'cancelled';
    case invalid = 'invalid';
    case unknown = 'unknown';
}
