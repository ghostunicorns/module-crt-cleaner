<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Model;

use GhostUnicorns\CrtCron\Api\CronConfigInterface;

interface CleanerConfigInterface extends CronConfigInterface
{
    /**
     * @return string
     */
    public function getHoursToKeep(): string;
}
