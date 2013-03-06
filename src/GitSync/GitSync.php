<?php

/**
 * Synchronizes Git repositories.
 *
 * @mainpage
 *
 * @license GNU General Public License, version 3
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see https://github.com/cpliakas/git-sync
 * @copyright Copyright (c) 2013 Acquia, Inc.
 */

namespace GitSync;

use GitWrapper\GitWorkingCopy;

/**
 * Base class for all sync classes.
 */
abstract class GitSync
{
    /**
     * The working copy used to perform the sync operation.
     *
     * @var GitWorkingCopy
     */
    protected $_git;

    /**
     * The Git URL of the repository that the working copy is associated with.
     *
     * @var string
     */
    protected $_repo;

    /**
     * Constructs a GitSync object.
     *
     * @param GitMirror $git
     *   The working copy used to perform the sync operation.
     * @param string $repo
     *   The Git URL of the repository that the working copy is associated with.
     */
    public function __construct(GitWorkingCopy $git, $repo)
    {
        $this->_git = $git;
        $this->_repo = $repo;
    }

    /**
     * Returns the working copy used to perform the sync operation.
     *
     * @return GitWorkingCopy
     */
    public function getWorkingCopy()
    {
        return $this->_git;
    }

    /**
     * Performs the synchronization operation.
     *
     * @param string $remote_repo
     *   The Git URL of the remote repository being synched to or from.
     */
    abstract public function sync($remote_repo);
}
