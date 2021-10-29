<?php
/*
  * Copyright © Ghost Unicorns snc. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace GhostUnicorns\CrtCleaner\Console\Command;

use Exception;
use GhostUnicorns\CrtBase\Api\CrtListInterface;
use GhostUnicorns\CrtBase\Logger\Handler\Console;
use GhostUnicorns\CrtCleaner\Model\DeleteActivity;
use GhostUnicorns\CrtCleaner\Model\DeleteAllActivity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommand extends Command
{
    /** @var string */
    const TYPE = 'type';

    /** @var string */
    const ALL = 'all';

    /** @var string */
    const SOFT = 'soft';

    /**
     * @var Console
     */
    private $consoleLogger;

    /**
     * @var CrtListInterface
     */
    private $crtList;

    /**
     * @var DeleteActivity
     */
    private $deleteActivity;

    /**
     * @var DeleteAllActivity
     */
    private $deleteAllActivity;

    /**
     * @param Console $consoleLogger
     * @param CrtListInterface $crtList
     * @param DeleteActivity $deleteActivity
     * @param DeleteAllActivity $deleteAllActivity
     * @param null $name
     */
    public function __construct(
        Console $consoleLogger,
        CrtListInterface $crtList,
        DeleteActivity $deleteActivity,
        DeleteAllActivity $deleteAllActivity,
        $name = null
    ) {
        parent::__construct($name);
        $this->consoleLogger = $consoleLogger;
        $this->crtList = $crtList;
        $this->deleteActivity = $deleteActivity;
        $this->deleteAllActivity = $deleteAllActivity;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        $text = [];
        $text[] = __('Available CollectorList types: ')->getText();
        $allDownlaoderList = $this->crtList->getAllCollectorList();
        foreach ($allDownlaoderList as $name => $downlaoderList) {
            $text[] = $name;
            $text[] = ', ';
        }
        $text[] = __('Available RefinerList types: ')->getText();
        $allRefinerList = $this->crtList->getAllRefinerList();
        foreach ($allRefinerList as $name => $refinerList) {
            $text[] = $name;
            $text[] = ', ';
        }
        $text[] = __('Available TransferorList types: ')->getText();
        $allUplaoderList = $this->crtList->getAllTransferorList();
        foreach ($allUplaoderList as $name => $uplaoderList) {
            $text[] = $name;
            $text[] = ', ';
        }
        array_pop($text);
        return implode('', $text);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription('Crt: Delete transfered Activity, all and/or for a specific Type');

        $this->addArgument(
            self::TYPE,
            InputArgument::OPTIONAL,
            'Type name',
            ''
        );

        $this->addOption(
            self::ALL,
            'a',
            InputOption::VALUE_NONE,
            'Delete all (transfered too)',
            null
        );

        $this->addOption(
            self::SOFT,
            's',
            InputOption::VALUE_NONE,
            'Delete only Original Data',
            null
        );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->consoleLogger->setConsoleOutput($output);
        $type = $input->getArgument(self::TYPE);
        $all = (bool)$input->getOption(self::ALL);
        $soft = (bool)$input->getOption(self::SOFT);
        if ($all) {
            $this->deleteAllActivity->execute($type, $soft);
        } else {
            $this->deleteActivity->execute($type, $soft);
        }
    }
}
