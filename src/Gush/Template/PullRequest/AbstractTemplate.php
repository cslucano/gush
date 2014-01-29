<?php

namespace Gush\Template\PullRequest;

use Gush\Template\TemplateInterface;

abstract class AbstractTemplate implements TemplateInterface
{
    protected $parameters = null;

    public function bind($parameters)
    {
        $this->parameters = $parameters;

        $requirements = $this->getRequirements();

        foreach ($requirements as $key => $rData) {
            list($label, $default) = $rData;

            if (!isset($this->parameters[$key])) {
                $this->parameters[$key] = $default;
            }
        }
    }
}
