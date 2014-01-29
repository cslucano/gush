<?php

namespace Gush\Template\PullRequest;

use Gush\Helper\TableHelper;
use Symfony\Component\Console\Output\BufferedOutput;

class DefaultTemplate extends AbstractTemplate
{
    public function render()
    {
        $out = array();
        return implode("\n", $out);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirements()
    {
        return array(
        );
    }

    public function getName()
    {
        return 'pull-request/default';
    }
}


