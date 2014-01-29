<?php

namespace Gush\Template\PullRequest;

use Gush\Helper\TableHelper;
use Symfony\Component\Console\Output\BufferedOutput;

class SymfonyTemplate extends AbstractTemplate
{
    public function render()
    {
        if (null === $this->parameters) {
            throw new \RuntimeException('Template has not been bound');
        }

        $output = new BufferedOutput();
        $table = new TableHelper();
        $table->setHeaders(array('Q', 'A'));
        $table->setLayout(TableHelper::LAYOUT_GITHUB);

        $description = $this->parameters['description'];
        unset($this->parameters['description']);
        $requirements = $this->getRequirements();

        foreach ($this->parameters as $key => $value) {
            $label = $requirements[$key][0];
            $table->addRow([$label, $value]);
        }

        $table->render($output);

        $out = array();
        $out[] = $output->fetch();
        $out[] = $description;

        return implode("\n", $out);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirements()
    {
        return array(
            'bug_fix' => array('Bug Fix?:', 'n'),
            'new_feature' => array('New Feature? :', 'n'),
            'bc_breaks' => array('BC Breaks?:', 'n'),
            'deprecations' => array('Deprecations:', 'n'),
            'tests_pass' => array('Tests Pass?:', 'n'),
            'fixed_tickets' => array('Fixed Tickets:', ''),
            'license' => array('License:', 'MIT'),
            'doc_pr' => array('Doc PR:', ''),
            'description' => array('Description:', ''),
        );
    }

    public function getName()
    {
        return 'pull-request/symfony';
    }
}

