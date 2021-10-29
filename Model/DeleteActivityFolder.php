<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Model;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\DriverInterface;

class DeleteActivityFolder
{
    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(
        DriverInterface $driver
    ) {
        $this->driver = $driver;
    }

    /**
     * @param int $activityId
     * @param string $folderPath
     * @throws FileSystemException
     */
    public function execute(int $activityId, string $folderPath): void
    {
        $path = rtrim($folderPath, DIRECTORY_SEPARATOR);
        $path .= DIRECTORY_SEPARATOR;
        $path .= $activityId;
        $this->driver->deleteDirectory($path);
    }
}
