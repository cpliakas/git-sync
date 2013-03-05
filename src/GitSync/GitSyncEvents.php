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
     * Event thrown prior executing the `git push --mirror` command.
     *
     * @var string
     */
    const MIRROR_PRE_COMMIT = 'git.mirror.pre_commit';

    /**
     * Event thrown after executing the `git push --mirror` command.
     *
     * @var string
     */
    const MIRROR_POST_COMMIT = 'git.mirror.post_commit';
}
