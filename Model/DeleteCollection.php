<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Model;

use GhostUnicorns\CrtActivity\Model\ActivityModel;
use GhostUnicorns\CrtActivity\Model\ResourceModel\ActivityResourceModel;
use GhostUnicorns\CrtEntity\Api\EntityRepositoryInterface;
use GhostUnicorns\CrtEntity\Model\ResourceModel\EntityResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class DeleteCollection
{
    /**
     * @var ActivityResourceModel
     */
    private $activityResourceModel;

    /**
     * @var EntityRepositoryInterface
     */
    private $entityRepository;

    /**
     * @var EntityResourceModel
     */
    private $entityResourceModel;

    /**
     * @param ActivityResourceModel $activityResourceModel
     * @param EntityRepositoryInterface $entityRepository
     * @param EntityResourceModel $entityResourceModel
     */
    public function __construct(
        ActivityResourceModel $activityResourceModel,
        EntityRepositoryInterface $entityRepository,
        EntityResourceModel $entityResourceModel
    ) {
        $this->activityResourceModel = $activityResourceModel;
        $this->entityRepository = $entityRepository;
        $this->entityResourceModel = $entityResourceModel;
    }

    public function execute(
        AbstractCollection $activityCollection,
        bool $soft
    ): int {
        $activityCollection->getItems();

        $count = $activityCollection->count();
        if (!$count) {
            return 0;
        }

        /** @var ActivityModel $activity */
        foreach ($activityCollection as $activity) {
            if ($soft) {
                $entities = $this->entityRepository->getAllByActivityId((int)$activity->getId());

                foreach ($entities as $entity) {
                    $entity->setDataOriginal('');
                    $this->entityResourceModel->save($entity);
                }
            } else {
                $this->activityResourceModel->delete($activity);
            }
        }

        return $count;
    }
}
