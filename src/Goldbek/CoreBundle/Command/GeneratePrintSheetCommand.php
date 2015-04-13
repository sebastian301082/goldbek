<?php
namespace Goldbek\CoreBundle\Command;

use fpdi\FPDI;
use Goldbek\CoreBundle\Exception\FileNotReadableException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Sebastian Thoss
 */
class GeneratePrintSheetCommand extends BaseCommand
{
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

        $pageWidth = 460;
        $pageHeight = 320;

        $frontFile = $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/files/design6_printpage1.pdf';
        $rearFile = $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/files/design6_printpage2.pdf';
        $imageWidth = 110;
        $imageHeight = 152;
        $colCount = 4;
        $rowCount = 2;

        if (!is_readable($frontFile)) {
            throw new FileNotReadableException($frontFile);
        }

        if (!is_readable($rearFile)) {
            throw new FileNotReadableException($rearFile);
        }

        $marginX = round(($pageWidth - ($colCount * $imageWidth)) / 2);
        $marginY = round(($pageHeight - ($rowCount * $imageHeight)) / 2);

        $pdf = new FPDI('L', 'mm', array($pageWidth, $pageHeight));

        $pdf->AddPage();
        $pdf->setSourceFile($frontFile);
        $tplIdx = $pdf->importPage(1);
        $this->renderPage($pdf, $tplIdx, $rowCount, $colCount, $imageWidth, $imageHeight, $marginX, $marginY);

        $pdf->AddPage();
        $pdf->setSourceFile($rearFile);
        $tplIdx = $pdf->importPage(1);
        $this->renderPage($pdf, $tplIdx, $rowCount, $colCount, $imageWidth, $imageHeight, $marginX, $marginY);

        $fileName = $this->getContainer()->getParameter('kernel.root_dir') . '/Resources/files/fpdi.pdf';
        $pdf->Output($fileName, 'F');

        $output->writeln('Finish generating');
        $output->writeln(
            'File can be found here:' .
            $this->getContainer()->getParameter('kernel.root_dir') .
            '/Resources/files/fpdi.pdf'
        );

        return 0;
    }

    private function renderPage(FPDI $pdf, $tplIdx, $rowCount, $colCount, $imageWidth, $imageHeight, $marginX, $marginY)
    {
        for ($row = 1; $row <= $rowCount; $row++) {
            for ($col = 1; $col <= $colCount; $col++) {
                $x = $marginX + ($col * $imageWidth) - $imageWidth;
                $y = $marginY + ($row * $imageHeight) - $imageHeight;
                $pdf->useTemplate($tplIdx, $x, $y);

                if ($row == 1) {
                    $pdf->Line($x + 2, $y - 4, $x + 2, $y);
                    $pdf->Line($x + $imageWidth - 2, $y - 4, $x + $imageWidth - 2, $y);
                }

                if ($row == $rowCount) {
                    $pdf->Line($x + 2, $y + $imageHeight + 4, $x + 2, $y + $imageHeight);
                    $pdf->Line($x + $imageWidth - 2, $y + $imageHeight + 4, $x + $imageWidth - 2, $y + $imageHeight);
                }

                if ($col == 1) {
                    $pdf->Line($x - 4, $y + 2, $x, $y + 2);
                    $pdf->Line($x - 4, $y + $imageHeight - 2, $x, $y + $imageHeight - 2);
                }

                if ($col == $colCount) {
                    $pdf->Line($x + $imageWidth + 4, $y + 2, $x + $imageWidth, $y + 2);
                    $pdf->Line($x + $imageWidth + 4, $y + $imageHeight - 2, $x + $imageWidth, $y + $imageHeight - 2);
                }
            }
        }
    }
}
