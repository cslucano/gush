<?php

/*
 * This file is part of Gush.
 *
 * (c) Luis Cordova <cordoval@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Gush\Command;

use Gush\Model\BufferedOutput;
use Gush\Model\Question;
use Gush\Model\Questionary;
use Gush\Model\SymfonyDocumentationQuestionary;
use Gush\Model\SymfonyQuestionary;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gush\Feature\GitHubFeature;
use Gush\Feature\TableFeature;
use Symfony\Component\Console\Input\InputOption;

/**
 * Launches a pull request
 *
 * @author Luis Cordova <cordoval@gmail.com>
 */
class PullRequestCreateCommand extends BaseCommand implements TableFeature, GitHubFeature
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pull-request:create')
            ->setDescription('Launches a pull request')
            ->addOption('base', 'b', InputOption::VALUE_REQUIRED, 'Base Branch', 'master')
            ->addOption('head', 'h', InputOption::VALUE_REQUIRED, 'Head Branch')
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> command gives a pat on the back to a PR's author with a random template:

    <info>$ gush %command.full_name% 12</info>
EOF
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $org = $input->getOption('org');
        $repo = $input->getOption('repo');

        $base = $input->getOption('base');
        $head = $input->getOption('head');

        if (null === $head) {
            $head = $this->getHelper('git')->getBranchName();
        }

        $github = $this->getParameter('github');
        $username = $github['username'];

        if (!$title = $input->getOption('title')) {
            $title = $this->askQuestion('Title');
        }

        // to be replaced with something like
        //
        // $this->getHelper('template')->get($input->getOption('template') ?: 'symfony');
        //
        $template = new SymfonyTemplate;
        $this->getHelper('template')->getTemplate($template);
        $this->getHelper('template')->parameterize($output, $input, $template);
        $body = $template->render($params);

        $pullRequest = $this->getGithubClient()
            ->api('pull_request')
            ->create($org, $repo, [
                    'base'  => $org . ':' . $base,
                    'head'  => $username . ':' . $head,
                    'title' => $title,
                    'body'  => $description,
                ]
            )
        ;

        $output->writeln($pullRequest['html_url']);

        return self::COMMAND_SUCCESS;
    }
}
