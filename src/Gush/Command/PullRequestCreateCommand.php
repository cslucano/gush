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
use Gush\Template\PullRequest\SymfonyTemplate;
use Gush\Feature\TemplateFeature;

/**
 * Launches a pull request
 *
 * @author Luis Cordova <cordoval@gmail.com>
 * @author Daniel Leech <daniel@dantleech.com>
 */
class PullRequestCreateCommand extends BaseCommand implements GitHubFeature, TemplateFeature
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pull-request:create')
            ->setDescription('Launches a pull request')
            ->addOption('base', null, InputOption::VALUE_REQUIRED, 'Base Branch - remote branch name', 'master')
            ->addOption('head', null, InputOption::VALUE_REQUIRED, 'Head Branch - your branch name (defaults to current)')
            ->addOption('title', null, InputOption::VALUE_REQUIRED, 'PR Title')
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

        $template = $input->getOption('template');

        if (null === $head) {
            $head = $this->getHelper('git')->getBranchName();
        }

        $github = $this->getParameter('github');
        $username = $github['username'];

        if (!$title = $input->getOption('title')) {
            $title = $this->getHelper('dialog')->ask($output, 'Title: ');
        }

        if (!$template) {
            $template = 'default';
        }

        $template = $this->getHelper('template')->getTemplate('pull-request', $template);
        $this->getHelper('template')->parameterize($output, $template);
        $body = $template->render();

        $pullRequest = $this->getGithubClient()
            ->api('pull_request')
            ->create($org, $repo, [
                    'base'  => $org . ':' . $base,
                    'head'  => $username . ':' . $head,
                    'title' => $title,
                    'body'  => $body,
                ]
            )
        ;

        $output->writeln($pullRequest['html_url']);

        return self::COMMAND_SUCCESS;
    }
}
