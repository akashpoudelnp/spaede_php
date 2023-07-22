<?php

namespace Spaede\Support;

class BaseController
{
    public function __construct(protected Application $application)
    {
    }
}