<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Model;

use Exception;
use GhostUnicorns\CrtActivity\Model\ActivityModel;
use GhostUnicorns\CrtActivity\Model\ResourceModel\Activity\ActivityCollectionFactory;

class DeleteAllActivity
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
     * @return int
     * @throws Exception
     */
    public function execute(
        string $type = '',
        bool $soft = false
    ): int {
        $activityCollection = $this->activityCollectionFactory->create();

        if ($type != '') {
            $activityCollection->addFieldToFilter(ActivityModel::TYPE, ['eq' => $type]);
        }

        return $this->deleteCollection->execute($activityCollection, $soft);
    }
}
