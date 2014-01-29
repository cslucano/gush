<?php

namespace Gush\Helper;

use Gush\Template\PullRequest\SymfonyTemplate;
use Symfony\Component\Console\Helper\Helper;
use Gush\Template\TemplateInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputAwareInterface;
use Gush\Template\PullRequest\DefaultTemplate;

class TemplateHelper extends Helper implements InputAwareInterface
{
    protected $templates;
    protected $dialog;
    protected $input;

    public function __construct(DialogHelper $dialog)
    {
        $this->registerTemplate(new SymfonyTemplate());
        $this->registerTemplate(new DefaultTemplate());
        $this->dialog = $dialog;
    }

    public function setInput(InputInterface $input)
    {
        $this->input = $input;
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

    public function parameterize(OutputInterface $output, TemplateInterface $template)
    {
        $params = array();
        foreach ($template->getRequirements() as $key => $requirement) {
            if (!$this->input->hasOption($key) || !$this->input->getOption($key)) {
                list($prompt, $default) = $requirement;
                $prompt  = $default ? $prompt . ' (' . $default . ')' : $prompt;
                $v = $this->dialog->ask($output, $prompt . ' ', $default);
            } else {
                $v = $this->input->getOption($key);
            }

            $params[$key] = $v;
        }

        $template->bind($params);
    }
}
