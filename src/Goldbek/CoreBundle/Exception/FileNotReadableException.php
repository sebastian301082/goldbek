<?php
namespace Goldbek\CoreBundle\Exception;

use Exception;
use RuntimeException;

/**
 * @author Sebastian Thoss
 */
class FileNotReadableException extends RuntimeException
{
    public function __construct($fileName = null, $code = 1, Exception $previous = null)
    {
        $message = sprintf('File "%s" not readable', $fileName);
        parent::__construct($message, $code, $previous);
    }
}
