Env Backup
==========

[![Build Status](https://travis-ci.org/clippings/env-backup.png?branch=master)](https://travis-ci.org/clippings/env-backup)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/clippings/env-backup/badges/quality-score.png?s=5e12a9e615449e2b63cc5bae31fc92f6bb977ca4)](https://scrutinizer-ci.com/g/clippings/env-backup/)
[![Code Coverage](https://scrutinizer-ci.com/g/clippings/env-backup/badges/coverage.png?s=bf4be88c910271150acc5fb0ce2bd1d0585ea524)](https://scrutinizer-ci.com/g/clippings/env-backup/)
[![Latest Stable Version](https://poser.pugx.org/clippings/env-backup/v/stable.png)](https://packagist.org/packages/clippings/env-backup)

Backup/restore environment variables: globals and static vars. This is very useful for initializing environment for a test. Keep the current state of the environment from interfiering with other tests.

You can add "Parameters" to the environment, each "applying" and "restoring" a specific super global or static property of a class, you can even keep a precice state of the filesystem.

 - `GlobalParam` - used for setting / restoring '\_POST', '\_GET', '\_FILES', '\_SERVER', '\_COOKIE' and '\_SESSION'
 - `ServerParam` - used specifically for '\_SERVER' super global so you can set / restore only some of its contents, e.g. REMOTE\_HOST', 'CLIENT\_IP ...
 - `StaticParam` - used for setting / restoring static properties of classes, it can handle protected and private ones too.
 - `FileParam`   - used for setting / restoring files and their contents. Ensures a certain file and its content are present, and restore previous state
 - `DirectoryParam` - used for setting / restoring a whole dir with all of its contents. Ensures a certain dir and its content are present, and restore previous state

Example:

```php
use CL\EnvBackup\Env;
use CL\EnvBackup\GlobalParam;
use CL\EnvBackup\ServerParam;
use CL\EnvBackup\StaticParam;

$env = new Env(array(
    new GlobalParam('_POST', array('new post name' => 'val')),
    new ServerParam('REMOTE_ADDR', '1.1.1.1'),
    new StaticParam('MyClass', 'private_var', 10)
    new FileParam('/path/to/file', 'file content')
    new DirectoryParam('/path/to/dir', array(
        'file1.txt' => 'file contents',
        'inner_dir' => array(
            'inner_file1.csv' => 'a, b',
            'inner_file2.txt' => 'test',
        )
    ))
));

$env->apply();

// Do some stuff that changes / uses these variables
// ...

$env->restore();
```

ServerParam in CLI
------------------

In CLI environment, $\_SERVER variables are very different than if the script is run by apache or cgi. If you want to use env-backup in such an environment, you can pass ServerParam::CLI like this:

```php
$env = new Env(array(
    new ServerParam('HOME', '/test/home', ServerParam::CLI),
    new ServerParam('argc', 4, ServerParam::CLI),
));
```

## License

Copyright (c) 2014, Clippings Ltd. Developed by Ivan Kerin as part of [clippings.com](http://clippings.com)

Under BSD-3-Clause license, read LICENSE file.
