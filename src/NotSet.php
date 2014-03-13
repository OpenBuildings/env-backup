<?php

namespace CL\EnvBackup;

/**
 * This class is used to denote an 'not set' varible.
 * If a parameter was not present at all before a ->set() call we use this class to remember that,
 * and later on '->restore()' the parameter is restored to its previous state e.g. 'not present'
 *
 * @author    Ivan Kerin <ikerin@gmail.com>
 * @copyright (c) 2014 Clippings Ltd.
 * @license   http://spdx.org/licenses/BSD-3-Clause
 */
class NotSet
{
}
