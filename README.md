Env Backup
==========

[![Build Status](https://travis-ci.org/clippings/env-backup.png?branch=master)](https://travis-ci.org/clippings/env-backup)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/clippings/env-backup/badges/quality-score.png?s=5e12a9e615449e2b63cc5bae31fc92f6bb977ca4)](https://scrutinizer-ci.com/g/clippings/env-backup/)
[![Code Coverage](https://scrutinizer-ci.com/g/clippings/env-backup/badges/coverage.png?s=bf4be88c910271150acc5fb0ce2bd1d0585ea524)](https://scrutinizer-ci.com/g/clippings/env-backup/)
[![Latest Stable Version](https://poser.pugx.org/clippings/env-backup/v/stable.png)](https://packagist.org/packages/clippings/env-backup)

Backup/restore environment variables: globals and static vars

Each environment group that you add allows has a unique name of "naming" its variables so that it knows how to handle their backup

 - '\_POST', '\_GET', '\_FILES', '\_SERVER', '\_COOKIE' and '\_SESSION' are handled by the `GlobalParam`,
 - 'REMOTE\_HOST', 'CLIENT\_IP' and all the other variables inside the '\_SERVER' variable are handled by `ServerParam` (this is used to easily backup restore only sertain variables of the $_SERVER super global)
 - 'SomeClass::$variable' is used to handle static variables by `StaticParam`. It can backup / restore public, protected and private ones

Example:

```php
use CL\EnvBackup\Env;
use CL\EnvBackup\GlobalParam;
use CL\EnvBackup\ServerParam;
use CL\EnvBackup\StaticParam;

$env = new Env(
    new GlobalParam('_POST', array('new post name' => 'val')),
    new ServerParam('REMOTE_ADDR', '1.1.1.1'),
    new StaticParam('MyClass', 'private_var', 10)
);

// Do some stuff that changes / uses these variables
// ...

$env->restore();
```

## License

Copyright (c) 2014, Clippings Ltd. Developed by Ivan Kerin as part of [clippings.com](http://clippings.com)

Under BSD-3-Clause license, read LICENSE file.
