<?php

/**
 * Synchronizes Git repositories.
 *
 * @license GNU General Public License, version 3
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see https://github.com/cpliakas/git-sync
 * @copyright Copyright (c) 2013 Acquia, Inc.
 */

namespace GitSync;

/**
 * Static list of events thrown by this library.
 */
final class GitSyncEvents
{
    /**
     * Event thrown prior executing the `git fetch` command.
     *
     * @var string
     */
    const PRE_FETCH = 'git.sync.all.pre_fetch';

    /**
     * Event thrown prior to executing the `git push` command.
     *
     * @var string
     */
    const PRE_PUSH = 'git.sync.all.pre_push';

    /**
     * Event thrown after executing the `git push` command.
     *
     * @var string
     */
    const POST_PUSH = 'git.sync.all.pre_push';

    /**
     * Event thrown prior to executing the `git push` command on a branch.
     *
     * @var string
     */
    const PRE_PUSH_BRANCH = 'git.sync.branch.pre_push';

    /**
     * Event thrown after executing the `git push` command on a branch.
     *
     * @var string
     */
    const POST_PUSH_BRANCH = 'git.sync.branch.pre_push';
}
