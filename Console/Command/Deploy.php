<?php

/**
 * Serverless
 *
 * Use serverless function to modify the Magento business logic.
 *
 * @package ImDigital\Serverless
 * @author Igor Ludgero Miura <igor@imdigital.com>
 * @copyright Copyright (c) 2023 Imagination Media (https://www.imdigital.com/)
 * @license Private
 */

declare(strict_types=1);

namespace ImDigital\Serverless\Console\Command;

use ImDigital\Serverless\Api\ServerlessFunctionRepositoryInterface;
use ImDigital\Serverless\Model\ServerlessFunctionConfigRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Deploy extends Command
{
    /**
     * @var ServerlessFunctionRepositoryInterface
     */
    protected ServerlessFunctionRepositoryInterface $serverlessFunctionRepository;

    /**
     * @var ServerlessFunctionConfigRepository
     */
    protected ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository;

    /**
     * @param ServerlessFunctionRepositoryInterface $serverlessFunctionRepository
     * @param ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
     * @param string|null $name
     */
    public function __construct(
        ServerlessFunctionRepositoryInterface $serverlessFunctionRepository,
        ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository,
        string $name = null
    ) {
        $this->serverlessFunctionRepository = $serverlessFunctionRepository;
        $this->serverlessFunctionConfigRepository = $serverlessFunctionConfigRepository;
        parent::__construct($name);
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('serverless:deploy:config');
        $this->setDescription('Deploy the serverless functions configuration into a text file for future use.');
    }

    /**
     * Execute command generating the serverless configuration file
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Get all enabled functions
        $enabledFunctions = $this->serverlessFunctionRepository->getFunctionsByEvent();

        // Create the serverless configuration file
        if ($this->serverlessFunctionConfigRepository->generateConfigFile($enabledFunctions)) {
            $output->writeln('Serverless Configuration File Generated Successfully.');
        } else {
            $output->writeln('Serverless Configuration File Generation Failed.');
        }
    }
}
