<?php

namespace CL\EnvBackup\Test;

use CL\EnvBackup\Params;
use CL\EnvBackup\NotSet;

class DummyParams implements Params
{
    public $variables = array();

    public function set($name, $value)
    {
        if ($value instanceof NotSet) {
            unset($this->variables[$name]);
        } else {
            $this->variables[$name] = $value;
        }
    }

    public function get($name)
    {
        return isset($this->variables[$name]) ? $this->variables[$name] : new NotSet;
    }

    public function has($name)
    {
        return strpos($name, 'test_') === 0;
    }
}
