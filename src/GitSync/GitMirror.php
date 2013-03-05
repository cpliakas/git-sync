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
use GitWrapper\GitWrapper;
use GitSync\Event\GitMirrorEvent;

/**
 * Synchronizes a source repository to a mirror.
 */
class GitMirror
{
    /**
     * The working copy of the source repository being mirrored.
     *
     * @var GitWorkingCopy
     */
    protected $_git;

    /**
     * The URL of the source repository being mirrored.
     *
     * @var string
     */
    protected $_sourceRepo;

    /**
     * Constructs a GitMirror object.
     *
     * @param GitMirror $git
     *   The working copy of the cloned source repository.
     * @param string $source_repo
     *   The Git URL of the source repository.
     */
    public function __construct(GitWorkingCopy $git, $source_repo)
    {
        $this->_git = $git;
        $this->_sourceRepo = $source_repo;
    }

    /**
     * Mirrors the source repository to a destination repository.
     *
     * @param string $dest_repo
     *   The Git URL of the destination repository that the source is being
     *   mirrored to.
     * @param string $directory
     *   The directory that the source repository will be cloned to. Pass null
     *   for
     *
     * @return GitWorkingCopy
     */
    public function sync($dest_repo)
    {
        $dispatcher = $this->_git->getWrapper()->getDispatcher();
        $event = new GitMirrorEvent($this->_git, $this->_sourceRepo, $dest_repo);

        $directory = $this->_git->getDirectory();
        if (null === $directory) {
            $directory = GitWrapper::parseRepositoryName($this->_sourceRepo);
        }

        // Checkout the repository if it doesn't exist.
        // @todo Add a more sophisticated check.
        if (!is_dir($directory)) {
            $this->_git->clone($this->_sourceRepo, $directory, array('mirror' => true));
            $this->_git->remote('add', 'mirrored', $dest_repo);
        }

        $this->_git->fetch();

        $dispatcher->dispatch(GitSyncEvents::MIRROR_PRE_COMMIT);
        $this->_git->push('mirrored', array('mirror' => true));
        $dispatcher->dispatch(GitSyncEvents::MIRROR_POST_COMMIT);
    }
}
