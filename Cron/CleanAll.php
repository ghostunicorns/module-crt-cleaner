<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Cron;

use DateInterval;
use DateTime;
use Exception;
use GhostUnicorns\CrtBase\Exception\CrtException;
use GhostUnicorns\CrtCleaner\Model\CleanerConfigInterface;
use GhostUnicorns\CrtCleaner\Model\DeleteActivity;
use GhostUnicorns\CrtCron\Api\CronInstanceInterface;
use Monolog\Logger;

class CleanAll implements CronInstanceInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var CleanerConfigInterface
     */
    private $config;

    /**
     * @var DeleteActivity
     */
    private $deleteActivity;

    /**
     * @param Logger $logger
     * @param CleanerConfigInterface $config
     * @param DeleteActivity $deleteActivity
     */
    public function __construct(
        Logger $logger,
        CleanerConfigInterface $config,
        DeleteActivity $deleteActivity
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->deleteActivity = $deleteActivity;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        if (!$this->config->isCronEnabled()) {
            $this->logger->info(__(
                'Cron job CrtCleaner can\'t run because it is disabled'
            ));
            return;
        }

        try {
            $hoursToKeep = $this->config->getHoursToKeep();

            $untilDateTime = new DateTime('now');
            $untilDateTime->sub(new DateInterval('PT' . $hoursToKeep . 'H'));

            $count = $this->deleteActivity->execute('all', true, $untilDateTime, true, true, true);

            $this->logger->info(__(
                'Run cron job CrtCleaner - cleaned %1 activities',
                $count
            ));
        } catch (CrtException $e) {
            $this->logger->error(__(
                'Error while run cron job CrtCleaner - error: %1',
                $e->getMessage()
            ));
        }
    }
}
