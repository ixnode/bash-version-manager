<?php

declare(strict_types=1);

/*
 * This file is part of the ixno/bash-version-manager project.
 *
 * (c) Björn Hempel <https://www.hempel.li/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Ixnode\BashVersionManager\Tests\Unit;

use Ixnode\BashVersionManager\Version;
use Ixnode\PhpContainer\File;
use Ixnode\PhpException\File\FileNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Class VersionTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2022-12-30)
 * @since 0.1.0 (2022-12-30) First version.
 * @link Version
 */
final class VersionTest extends TestCase
{
    /**
     * Test wrapper (Version).
     *
     * @test
     *
     * @throws FileNotFoundException
     */
    public function wrapper(): void
    {
        /* Arrange */
        $versionString = $this->getVersionString();
        $dateString = $this->getDateString();
        $versionArray = [
            Version::INDEX_VERSION => $versionString,
            Version::INDEX_DATE => $dateString,
            Version::INDEX_LICENSE => Version::VALUE_LICENSE,
            Version::INDEX_AUTHORS => Version::VALUE_AUTHORS,
        ];

        /* Act */
        $version = new Version();

        /* Assert */
        $this->assertEquals($versionString, $version->getVersion());
        $this->assertEquals($versionArray, $version->getAll());
    }

    /**
     * Returns the version of this application.
     *
     * @return string
     */
    protected function getVersionString(): string
    {
        $versionFile = (new Version())->getVersionFile();

        return (new File($versionFile))->getContentAsTextTrim();
    }

    /**
     * Returns the date of version of this application.
     *
     * @return string
     * @throws FileNotFoundException
     */
    protected function getDateString(): string
    {
        $versionFile = (new Version())->getVersionFile();

        $mtime = filemtime($versionFile);

        if ($mtime === false) {
            throw new FileNotFoundException($versionFile);
        }

        return date ('l, F d, Y - H:i:s', $mtime);
    }
}
