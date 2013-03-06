Overview
========

GitSync is a PHP library that synchronizes a source repository to a
destination repository. This project integrates with the
[Git Wrapper](https://github.com/cpliakas/git-wrapper) library.

Usage
=====

This example mirrors the Git Wrapper repository into a local repository that
was initialized with `git init --bare /var/git/mirror/git-wrapper`.

```php

use GitWrapper\GitWrapper;
use GitSync\GitMirror;

require_once 'vendor/autoload.php';

$wrapper = new GitWrapper();
$git = $wrapper->workingCopy('./working-copy');

$mirror = new GitMirror($git, 'git://github.com/cpliakas/git-wrapper.git');
$mirror->sync('file:///var/git/mirror/git-wrapper');
```
