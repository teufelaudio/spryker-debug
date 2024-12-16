Database Shell
==============

Attempts to launch the Postgres shell using the current project database
configuration.

Installation
------------

Add the `DatabaseShellConsole` to your `ConsoleDependencyProvider`:

```php
<?php

namespace Pyz\Zed\Console;

// ...
use Teufelaudio\Zed\SprykerDebug\Communication\Console\DatabaseShellConsole;

class ConsoleDependencyProvider extends SprykerConsoleDependencyProvider
{
    // ...

    protected function getConsoleCommands(Container $container)
    {
        return [
            // ...
            new DatabaseShellConsole(),
        ];
    }
}
```

Usage
-----

Connect to the `psql` shell:

```bash
$ ./vendor/bin/console debug:database:shell
```

Note that the `psql` shell will need to be available.
