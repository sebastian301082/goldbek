<?php
namespace Goldbek\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\LockHandler;

/**
 * @author Sebastian Thoss
 */
abstract class BaseCommand extends ContainerAwareCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->useLockHandler()) {
            $lockHandler = new LockHandler($this->getName());
            if (!$lockHandler->lock($this->waitForUnlock())) {
                $output->writeln("Command '{$this->getName()}' is already running.");

                return 0;
            }

            $exitCode = $this->doExecute($input, $output);

            $lockHandler->release();
            return $exitCode;
        }

        return $this->doExecute($input, $output);
    }

    /**
     * implement this and put in the logic
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    abstract protected function doExecute(InputInterface $input, OutputInterface $output);

    /**
     * If true doExecute function will be wrapped with lockHandler
     *
     * @return boolean
     */
    protected function useLockHandler()
    {
        return true;
    }

    /**
     * If true command will wait until previous running command finish
     *
     * @return boolean
     */
    protected function waitForUnlock()
    {
        return false;
    }
}
