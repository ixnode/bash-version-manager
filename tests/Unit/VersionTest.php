<?php

/*
 * This file is part of the ixnode/bash-version-manager project.
 *
 * (c) Björn Hempel <https://www.hempel.li/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ixnode\BashVersionManager\Tests\Unit;

use Ixnode\BashVersionManager\Version;
use Ixnode\PhpException\ArrayType\ArrayKeyNotFoundException;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use JsonException;
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
     * @throws ArrayKeyNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     * @throws JsonException
     */
    public function wrapper(): void
    {
        /* Arrange */
        $versionString = $this->getVersionString();
        $dateString = $this->getDateString();
        $versionArray = [
            Version::INDEX_NAME => 'ixnode/bash-version-manager',
            Version::INDEX_DESCRIPTION => 'Bash Version Manager',
            Version::INDEX_VERSION => $versionString,
            Version::INDEX_DATE => $dateString,
            Version::INDEX_LICENSE => Version::VALUE_LICENSE,
            Version::INDEX_AUTHORS => Version::VALUE_AUTHORS,
            Version::INDEX_PHP => '8.2.7',
            Version::INDEX_COMPOSER => '2.5.1',
            Version::INDEX_PHP_EXCEPTION => '0.1.19',
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

        return $versionFile->getContentAsTextTrim();
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

        $mtime = filemtime($versionFile->getPathReal());

        if ($mtime === false) {
            throw new FileNotFoundException($versionFile->getPath());
        }

        return date ('l, F d, Y - H:i:s', $mtime);
    }
}
