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
 * Synchronizes a destination repository with a source repository.
 */
class GitFork extends GitSync
{
    /**
     * Whether to skip the master branch.
     *
     * @var boolean
     */
    protected $_skipMaster = false;

    /**
     * The URL of the destination repository that the source is being synced to.
     *
     * @return string
     */
    public function getDestinationRepository()
    {
        return $this->_repo;
    }

    /**
     * Sets whether to skip syncing the master branch.
     *
     * @param boolean $skip
     *   Whether to skip syncing the master branch.
     *
     * @return GitFork
     */
    public function skipMaster($skip)
    {
        $this->_skipMaster = (bool) $skip;
        return $this;
    }

    /**
     * Fetches remotes via the `git remote` command.
     *
     * @return array
     */
    public function fetchRemotes()
    {
        $git = clone $this->_git;
        $git->clearOutput();
        $output = (string) $git->remote();
        return preg_split("/\r\n|\n|\r/", rtrim($output));
    }

    /**
     * Returns a regex pattern to extract the local branch from remotes.
     *
     * @param array $remotes
     *   An array of remotes.
     *
     * @return string
     */
    public function branchPattern(array $remotes)
    {
        $parts = array();
        foreach ($remotes as $remote) {
            if ($remote != 'origin') {
                $parts[] = preg_quote($remote, '@');
            }
        }
        return '@^(?:' . join('|', $parts) . ')/(.*)$@';
    }

    /**
     * Synchronizes the destination repository with a source repository.
     *
     * @param string $source_repo
     *   The Git URL of the source repository that the destination is being
     *   synced from.
     *
     * @return GitWorkingCopy
     */
    public function sync($source_repo)
    {
        $dispatcher = $this->_git->getWrapper()->getDispatcher();
        $event = new GitSyncEvent($this, $source_repo);

        // Clone the destination repository if it isn't already cloned. Add the
        // source repository as a remote.
        if (!$this->_git->isCloned()) {
            $this->_git->clone($this->getDestinationRepository());
            $this->_git->remote('add', 'upstream', $source_repo);
        }

        $dispatcher->dispatch(GitSyncEvents::PRE_FETCH, $event);
        $this->_git->fetchAll();

        $branches = $this->_git->getBranches();
        $all_branches = array_flip($branches->all());
        $pattern = $this->branchPattern($this->fetchRemotes());

        $dispatcher->dispatch(GitSyncEvents::PRE_PUSH, $event);
        foreach ($branches->remote() as $remote_branch) {

            // Extract the local branch from the remote.
            if (!preg_match($pattern, $remote_branch, $matches)) {
                continue;
            }

            $local_branch = $matches[1];
            if ('master' == $local_branch && $this->_skipMaster) {
                continue;
            }

            if (!isset($all_branches[$local_branch])) {
                $this->_git->checkout($remote_branch, array('b' => $local_branch));
            }
            else {
                $this->_git->checkout($local_branch);
                $this->_git->pull('upstream', $local_branch);
            }

            $event->setBranch($source_repo);
            $dispatcher->dispatch(GitSyncEvents::PRE_PUSH_BRANCH, $event);
            $this->_git->push('origin', $local_branch);
            $dispatcher->dispatch(GitSyncEvents::POST_PUSH_BRANCH, $event);
            $event->unsetBranch($source_repo);
        }
        $dispatcher->dispatch(GitSyncEvents::POST_PUSH, $event);
    }
}
