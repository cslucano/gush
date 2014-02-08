<?php

/**
 * This file is part of Gush.
 *
 * (c) Luis Cordova <cordoval@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Gush\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gush\Feature\GitHubFeature;

class VersionEyeCommand extends BaseCommand implements GitHubFeature
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('p:version-eye')
            ->setDescription('versioneye')
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> command :

    <info>$ gush %command.full_name%</info>
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
        // $projectId = '52f57f71ec1375fd0b0000b6'; --> gush
        $projectId = '52f580fbec137591740000a8'; // this is gush-sandbox

        $versionEyeClient = new \Guzzle\Http\Client();
        $versionEyeClient->setBaseUrl('https://www.versioneye.com');
        $results = $versionEyeClient->get(
            sprintf('/api/v2/projects/%s?api_key=6d18dc4533885a6fadd0', $projectId)
        )->send();

        $response = json_decode($results->getBody());
        foreach ($response->dependencies as $dependency) {
            if ($dependency->outdated) {
                $this->getHelper('process')->runCommands(
                    [
                        'line' => sprintf('composer require %s %s --no-update', $dependency->name, $dependency->version_current),
                        'allow_failure' => true,
                    ]
                );
            }
        }

        $output->writeln('Please check the modifications on your composer.json for\nupdated dependencies.');

        return self::COMMAND_SUCCESS;
    }
}
