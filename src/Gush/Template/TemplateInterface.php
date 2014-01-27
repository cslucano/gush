<?php

namespace Gush\Template;

interface TemplateInterface
{
    /**
     * Render the template using the given parameters
     */
    public function render($params);

    /**
     * Return all the variables required by the template
     * including descriptions and default values.
     *
     * The user will be prompted for any missing variables.
     */
    public function getRequirements();

    /**
     * Return the name of this template
     */
    public function getName();
}
