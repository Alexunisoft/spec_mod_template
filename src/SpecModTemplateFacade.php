<?php

namespace Alexunisoft\SpecModTemplate;

use Illuminate\Support\Facades\Facade;

class SpecModTemplateFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'spec_mod_template';
    }
}