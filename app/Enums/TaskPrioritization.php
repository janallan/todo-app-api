<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static URGENT
 * @method static HIGH
 * @method static NORMAL
 * @method static LOW
 * @method static NONE
 */
final class TaskPrioritization extends Enum
{
    const URGENT = 'URGENT';
    const HIGH = 'HIGH';
    const NORMAL = 'NORMAL';
    const LOW = 'LOW';
    const NONE = 'NONE';
}
