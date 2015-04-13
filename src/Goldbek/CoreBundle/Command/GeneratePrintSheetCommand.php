<?php
namespace Goldbek\CoreBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Sebastian Thoss
 */
class GeneratePrintSheetCommand extends BaseCommand
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('goldbek:generate:print-sheet')
            ->setDescription('This command reads given access.log file and writes it into database')
            /*
            ->addOption('frontFile', null, InputArgument::REQUIRED, 'Full path and file name')
            ->addOption('rearFile', null, InputArgument::REQUIRED, 'Full path and file name')
            ->addOption('imageWidth', null, InputArgument::REQUIRED, 'Full image width')
            ->addOption('imageHeight', null, InputArgument::REQUIRED, 'Full image height')
            ->addOption('colCount', null, InputArgument::REQUIRED, 'number columns per row')
            ->addOption('rowCount', null, InputArgument::REQUIRED, 'number of rows')
            //*/
        ;
    }

    protected function doExecute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start generating');

        //TODO Fill with logic

        $output->writeln('Finish generating');

        return 0;
    }
}
