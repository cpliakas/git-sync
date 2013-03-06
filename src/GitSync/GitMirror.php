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
use GitSync\Event\GitSyncEvent;

/**
 * Synchronizes a source repository to a mirror.
 */
class GitMirror extends GitSync
{
    /**
     * Returns the Git URL of the source repository being mirrored.
     *
     * @return string
     */
    public function getSourceRepository()
    {
        return $this->_repo;
    }

    /**
     * Mirrors the source repository to a destination repository.
     *
     * @param string $dest_repo
     *   The Git URL of the destination repository that the source is being
     *   mirrored to.
     *
     * @return GitWorkingCopy
     */
    public function sync($dest_repo)
    {
        $source_repo = $this->getSourceRepository();

        $dispatcher = $this->_git->getWrapper()->getDispatcher();
        $event = new GitSyncEvent($this, $dest_repo);

        // Clone the source repository if it isn't already cloned. Add the
        // destination repository as a remote.
        if (!$this->_git->isCloned()) {
            $this->_git->clone($source_repo, array('mirror' => true));
            $this->_git->remote('add', 'mirrored', $dest_repo);
        }

        // Download objects and refs from the source repository.
        $dispatcher->dispatch(GitSyncEvents::PRE_FETCH, $event);
        $this->_git->fetch();

        // Mirror all refs to the remote repository.
        $dispatcher->dispatch(GitSyncEvents::PRE_PUSH, $event);
        $this->_git->push('mirrored', array('mirror' => true));
        $dispatcher->dispatch(GitSyncEvents::POST_PUSH, $event);
    }
}
