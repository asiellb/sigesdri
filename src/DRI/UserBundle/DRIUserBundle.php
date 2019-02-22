<?php

namespace DRI\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DRIUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
