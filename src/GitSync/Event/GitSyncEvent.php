<?php

/**
 * Synchronizes Git repositories.
 *
 * @license GNU General Public License, version 3
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @see https://github.com/cpliakas/git-sync
 * @copyright Copyright (c) 2013 Acquia, Inc.
 */

namespace GitSync\Event;

use GitSync\GitSync;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event thrown for mirror events.
 */
class GitSyncEvent extends Event
{
    /**
     * The GitSync object performing the operation.
     *
     * @var GitSync
     */
    protected $_sync;

    /**
     * The Git URL of the remote repository being synched to or from.
     *
     * @var string
     */
    protected $_remoteRepo;

    /**
     * The branch being pushed.
     *
     * @var string
     */
    protected $_branch;

    /**
     * Constructs a GitEvent object.
     *
     * @param GitWorkingCopy $git
     *   The GitSync object performing the operation.
     * @param string $remote_repo
     *   The Git URL of the remote repository being synched to or from.
     */
    public function __construct(GitSync $git, $remote_repo)
    {
        $this->_sync = $git;
        $this->_remoteRepo = $remote_repo;
    }

    /**
     * The GitSync object performing the operation.
     *
     * @return GitSync
     */
    public function getSynchronizer()
    {
        return $this->_sync;
    }

    /**
     * The Git URL of the remote repository being synched to or from.
     *
     * @return string
     */
    public function getRemoteRepo()
    {
        return $this->_remoteRepo;
    }

    /**
     * Sets the branch being pushed.
     *
     * @param string $branch
     *   The branch being pushed.
     *
     * @return GitSyncEvent
     */
    public function setBranch($branch)
    {
        $this->_branch = $branch;
        return $this;
    }

    /**
     * Returns the branch being pushed.
     *
     * @return string|null
     */
    public function getBranch($branch)
    {
        return $this->_branch;
    }

    /**
     * Removes the stored branch. This is usually called after the branch has
     * been pushed.
     *
     * @return GitSyncEvent
     */
    public function unsetBranch()
    {
        unset($this->_branch);
        return $this;
    }
}
