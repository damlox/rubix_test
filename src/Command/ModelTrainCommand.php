<?php

declare(strict_types=1);

namespace App\Command;

use App\Util\Training\ModelTraining;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:model:train',
    description: 'Command to initiate a model training process',
)]
class ModelTrainCommand extends Command
{
    private const WARNING_NO_MODEL = 'Brak modeli do trenowania.';
    private const INFO_TRAINING_START = 'Rozpoczęto trenowanie modelu "%s"';
    private const SUCCESS_TRAINING_FINISH = 'Zakończono trenowanie modelu "%s"';

    public function __construct(
        private readonly ModelTraining $modelTraining,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $modelName = $this->modelTraining->initialize();

        if (null === $modelName) {
            $io->warning(self::WARNING_NO_MODEL);

            return Command::SUCCESS;
        }

        $io->info(sprintf(self::INFO_TRAINING_START, $modelName));
        $this->modelTraining->execute();
        $io->success(sprintf(self::SUCCESS_TRAINING_FINISH, $modelName));

        return Command::SUCCESS;
    }
}
