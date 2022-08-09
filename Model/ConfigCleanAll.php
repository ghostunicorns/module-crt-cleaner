<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Model;

use GhostUnicorns\CrtBase\Api\CrtConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class ConfigCleanAll implements CleanerConfigInterface
{
    /** @var string */
    const crt_CLEANER_ALL_IS_ENABLED_CONFIG_PATH = 'crt/cleaner/cleaner_all_enabled';

    /** @var string */
    const crt_CLEANER_ALL_CRON_EXPRESSION_CONFIG_PATH = 'crt/cleaner/cleaner_all_cron_expression';

    /** @var string */
    const crt_CLEANER_ALL_HOURS_TO_KEEP_CONFIG_PATH = 'crt/cleaner/cleaner_all_hours_to_keep';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CrtConfigInterface
     */
    private $crtConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CrtConfigInterface $crtConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->crtConfig = $crtConfig;
    }

    /**
     * @return bool
     */
    public function isCronEnabled(): bool
    {
        return $this->crtConfig->isEnabled() && $this->scopeConfig->isSetFlag(
            self::crt_CLEANER_ALL_IS_ENABLED_CONFIG_PATH,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getHoursToKeep(): string
    {
        return $this->scopeConfig->getValue(
            self::crt_CLEANER_ALL_HOURS_TO_KEEP_CONFIG_PATH,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @return string
     */
    public function getCronExpression(): string
    {
        return $this->scopeConfig->getValue(
            self::crt_CLEANER_ALL_CRON_EXPRESSION_CONFIG_PATH,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
