<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Model;

use DateTime;
use GhostUnicorns\CrtActivity\Model\ActivityModel;
use GhostUnicorns\CrtActivity\Model\ActivityStateInterface;
use GhostUnicorns\CrtActivity\Model\ResourceModel\Activity\ActivityCollectionFactory;
use GhostUnicorns\CrtBase\Exception\CrtException;

class DeleteActivity
{
    /**
     * @var ActivityCollectionFactory
     */
    private $activityCollectionFactory;

    /**
     * @var DeleteCollection
     */
    private $deleteCollection;

    /**
     * @param ActivityCollectionFactory $activityCollectionFactory
     * @param DeleteCollection $deleteCollection
     */
    public function __construct(
        ActivityCollectionFactory $activityCollectionFactory,
        DeleteCollection $deleteCollection
    ) {
        $this->activityCollectionFactory = $activityCollectionFactory;
        $this->deleteCollection = $deleteCollection;
    }

    /**
     * @param string $type
     * @param bool $soft
     * @param DateTime|null $untilAt
     * @param bool $deleteTransfered
     * @param bool $deleteErrors
     * @param bool $deleteWorking
     * @return int
     * @throws CrtException
     */
    public function execute(
        string $type = '',
        bool $soft = false,
        DateTime $untilAt = null,
        bool $deleteTransfered = true,
        bool $deleteErrors = false,
        bool $deleteWorking = false
    ): int {
        $activityCollection = $this->activityCollectionFactory->create();

        if ($type != '' && $type !== 'all') {
            $activityCollection->addFieldToFilter(ActivityModel::TYPE, ['eq' => $type]);
        }

        if ($untilAt) {
            $activityCollection->addFieldToFilter(ActivityModel::CREATED_AT, ['lt' => $untilAt]);
        }

        if (!$deleteTransfered && !$deleteErrors && !$deleteWorking) {
            throw new CrtException(__('Nothing to delete with this call!'));
        }

        $statuses = [];

        if ($deleteTransfered) {
            $statuses[] = ActivityStateInterface::TRANSFERED;
        }

        if ($deleteErrors) {
            $statuses[] = ActivityStateInterface::COLLECT_ERROR;
            $statuses[] = ActivityStateInterface::REFINE_ERROR;
            $statuses[] = ActivityStateInterface::TRANSFER_ERROR;
        }

        if ($deleteWorking) {
            $statuses[] = ActivityStateInterface::COLLECTING;
            $statuses[] = ActivityStateInterface::COLLECTED;
            $statuses[] = ActivityStateInterface::REFINING;
            $statuses[] = ActivityStateInterface::REFINED;
            $statuses[] = ActivityStateInterface::TRANSFERING;
        }

        $activityCollection->addFieldToFilter(
            ActivityModel::STATUS,
            ['in' => $statuses]
        );

        return $this->deleteCollection->execute($activityCollection, $soft);
    }
}
