<?php

namespace Gush\Template\PullRequest;

use Gush\Helper\TableHelper;
use Symfony\Component\Console\Output\BufferedOutput;

class SymfonyTemplate implements TemplateInterface
{
    public function render($params)
    {
        $output = new BufferedOutput();
        $table = new TableHelper();
        $table->setHeaders(array('Q', 'A'));
        $table->setLayout(TableHelper::LAYOUT_GITHUB);

        $description = $params['description'];
        unset($params['description']);

        foreach ($params as $key => $value) {
            $table->addRow($key, $value);
        }

        $tableHelper->render($output);

        $out = array();
        $out[] = $output->fetch();
        $out[] = '';
        $out[] = $params['description'];

        return implode('\n', $out);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequirements()
    {
        return array(
            'bug_fix' => array('Bug Fix?', 'n'),
            'new_feature' => array('New Feature?', 'n'),
            'bc_breaks' => array('BC Breaks?', 'n'),
            'deprecations' => array('Deprecations', 'n'),
            'tests_pass' => array('Tests Pass?', 'n'),
            'fixed_tickets' => array('Fixed Tickets', ''),
            'license' => array('License', 'MIT'),
            'doc_pr' => array('Doc PR', ''),
            'description' => array('Description', ''),
        );
    }

    public function getName()
    {
        return 'symfony';
    }
}

