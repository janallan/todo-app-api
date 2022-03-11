<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static TODO
 * @method static COMPLETED
 * @method static ARCHIVED
 * @method static DELETED
 */
final class TaskStatus extends Enum
{
    /**
     * When task initial status
     */
    const TODO = 'TODO';

    /**
     * When task is already completed
     */
    const COMPLETED = 'COMPLETED';

    /**
     * when task is moved to archives
     */
    const ARCHIVED = 'ARCHIVED';

    /**
     * When task is removed from the archives
     */
    const DELETED = 'DELETED';
}
