<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Transferor;

use Exception;
use GhostUnicorns\CrtBase\Api\CrtConfigInterface;
use GhostUnicorns\CrtBase\Api\TransferorInterface;
use Monolog\Logger;

class DeleteActivityFolder implements TransferorInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CrtConfigInterface
     */
    private $config;

    /**
     * @var string
     */
    private $folderPath;

    /**
     * @var \GhostUnicorns\CrtCleaner\Model\DeleteActivityFolder
     */
    private $deleteActivityFolder;

    /**
     * @param Logger $logger
     * @param CrtConfigInterface $config
     * @param \GhostUnicorns\CrtCleaner\Model\DeleteActivityFolder $deleteActivityFolder
     * @param string $folderPath
     */
    public function __construct(
        Logger $logger,
        CrtConfigInterface $config,
        \GhostUnicorns\CrtCleaner\Model\DeleteActivityFolder $deleteActivityFolder,
        string $folderPath
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->folderPath = $folderPath;
        $this->deleteActivityFolder = $deleteActivityFolder;
    }

    /**
     * @param int $activityId
     * @param string $transferorType
     */
    public function execute(int $activityId, string $transferorType): void
    {
        $this->logger->info(__(
            'activityId:%1 ~ Transferor ~ transferorType:%2 ~ START',
            $activityId,
            $transferorType
        ));

        try {
            $this->deleteActivityFolder->execute($activityId, $this->folderPath);
        } catch (Exception $e) {
            $this->logger->error(__(
                'activityId:%1 ~ Transferor ~ transferorType:%2 ~ ERROR ~ error:%3',
                $activityId,
                $transferorType,
                $e->getMessage()
            ));
        }

        $this->logger->info(__(
            'activityId:%1 ~ Transferor ~ transferorType:%2 ~ END',
            $activityId,
            $transferorType
        ));
    }
}
