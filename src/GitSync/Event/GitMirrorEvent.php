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

use GitWrapper\GitWorkingCopy;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event thrown for mirror events.
 */
class GitMirrorEvent extends Event
{
    /**
     * The working copy of the source repository being mirrored.
     *
     * @var GitWorkingCopy
     */
    protected $_git;

    /**
     * The Git URL of the source repository being mirrored.
     *
     * @var string
     */
    protected $_sourceRepo;

    /**
     * The Git URL of the destination repository that the source is being
     * mirrored to.
     *
     * @var string
     */
    protected $_destRepo;

    /**
     * Constructs a GitEvent object.
     *
     * @param GitWorkingCopy $git
     *   The working copy of the source repository being mirrored.
     * @param string $source_repo
     *   The Git URL of the source repository being mirrored.
     * @param string $dest_repo
     *   The Git URL of the destination repository that the source is being
     *   mirrored to.
     */
    public function __construct(GitWorkingCopy $git, $source_repo, $dest_repo)
    {
        $this->_git = $git;
        $this->_sourceRepo = $source_repo;
        $this->_destRepo = $dest_repo;
    }

    /**
     * Returns the working copy of the source repository being mirrored.
     *
     * @return GitWorkingCopy
     */
    public function getWorkingCopy()
    {
        return $this->_git;
    }

    /**
     * The Git URL of the source repository being mirrored.
     *
     * @return string
     */
    public function getSourceRepo()
    {
        return $this->_sourceRepo;
    }

    /**
     * The Git URL of the destination repository that the source is being
     * mirrored to.
     *
     * @return string
     */
    public function getDestRepo()
    {
        return $this->_destRepo;
    }
}
