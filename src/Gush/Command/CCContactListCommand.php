<?php

namespace Gush\Command;

use Ctct\Components\Contacts\Contact as Contact;
use Ctct\Components\ResultSet;
use Ctct\ConstantContact;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CCContactListCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('gush:contactlist')
            ->setDescription('Retrieve a list of contact')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ctct = new ConstantContact('zy2ffkwggvh34wtsaurb54xb');

        /** @var ResultSet $contacts */
        $contacts = $ctct->getContacts('68c18de9-4de3-4e7d-9999-1c796da0919c');

        print_r($contacts); die();
    }
}
