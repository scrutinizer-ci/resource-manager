<?php

namespace Scrutinizer\ResourceManager\Resource;

use Scrutinizer\ResourceManager\Resource;
use Symfony\Component\Process\Process;

class SymfonyProcessResource implements Resource
{
    private $process;
    private $wait;
    private $signal;

    public function __construct(Process $process, $wait = 5, $signal = null)
    {
        $this->process = $process;
        $this->wait = $wait;
        $this->signal = $signal;
    }

    public function destroy()
    {
        if ( ! $this->process->isRunning()) {
            return;
        }

        $this->process->stop($this->wait, $this->signal);
    }
}