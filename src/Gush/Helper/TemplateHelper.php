<?php

namespace Gush\Helper;

use Gush\Template\PullRequest\SymfonyTemplate;
use Symfony\Component\Console\Helper\Helper;
use Gush\Template\TemplateInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;

class TemplateHelper extends Helper
{
    protected $templates;
    protected $dialog;

    public function __construct(DialogHelper $dialog)
    {
        $this->registerTemplate(new SymfonyTemplate());
        $this->dialog = $dialog;
    }

    public function registerTemplate(TemplateInterface $template)
    {
        $this->templates[$template->getName()] = $template;
    }

    public function getName()
    {
        return 'template';
    }

    public function getTemplate($domain, $name)
    {
        $key = $domain . '/' . $name;
        if (!isset($this->templates[$key])) {
            throw new \InvalidArgumentException(sprintf(
                'Template with name "%s" has not been registered', $key
            ));
        }

        return $this->templates[$key];
    }

    public function parameterize(InputInterface $input, OutputInterface $output, TemplateInterface $template)
    {
        $params = array();
        foreach ($template->getRequirements() as $key => $requirement) {
            if (!$input->hasOption($key)) {
                list($prompt, $default) = $requirement;
                $this->dialog->ask($output, $prompt, $default);
            }
        }
    }
}
