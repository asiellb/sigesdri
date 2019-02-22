<?php

namespace DRI\DTBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DRIDTBundle extends Bundle
{
    public function getParent()
    {
        return 'SgDatatablesBundle';
    }
}
