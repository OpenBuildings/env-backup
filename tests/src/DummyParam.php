<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\ParamInterface;

class DummyParam implements ParamInterface
{
    public function apply()
    {
    }

    public function restore()
    {
    }
}
