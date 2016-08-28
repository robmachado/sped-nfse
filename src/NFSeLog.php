<?php

namespace NFePHP\NFSe;

use Psr\Log\LoggerInterface;

class NFSeLog
{
    public $logger;
    
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    public function log($level, $message)
    {
        if ($this->logger) {
            $this->logger->log($level, $message);
        }
    }
}
