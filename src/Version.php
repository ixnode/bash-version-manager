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

namespace Ixnode\BashVersionManager;

use Composer\Autoload\ClassLoader;
use Ixnode\PhpContainer\File;
use Ixnode\PhpException\File\FileNotFoundException;
use ReflectionClass;

/**
 * Class Version
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2022-12-30)
 * @since 0.1.0 (2022-12-30) First version.
 */
class Version
{
    public const VALUE_LICENSE = 'Copyright (c) 2022 Björn Hempel';

    public const VALUE_AUTHORS = [
        'Björn Hempel <bjoern@hempel.li>',
    ];

    public const PATH_VERSION = 'VERSION';

    public const INDEX_VERSION = 'version';

    public const INDEX_DATE = 'date';

    public const INDEX_LICENSE = 'license';

    public const INDEX_AUTHORS = 'authors';

    protected ?string $rootDir = null;

    /**
     * Version constructor.
     *
     * @param string|null $rootDir
     */
    public function __construct(?string $rootDir = null)
    {
        $this->rootDir = $rootDir;

        if (is_null($rootDir)) {
            $reflection = new ReflectionClass(ClassLoader::class);
            $this->rootDir = dirname($reflection->getFileName(), 3);
        }
    }

    /**
     * Returns the version of this application.
     *
     * @return string
     */
    public function getVersion(): string
    {
        $versionFile = $this->getVersionFile();

        return (new File($versionFile))->getContentAsTextTrim();
    }

    /**
     * Returns the date of version of this application.
     *
     * @return string
     * @throws FileNotFoundException
     */
    public function getDate(): string
    {
        $versionFile = $this->getVersionFile();

        $mtime = filemtime($versionFile);

        if ($mtime === false) {
            throw new FileNotFoundException($versionFile);
        }

        return date ('l, F d, Y - H:i:s', $mtime);
    }

    /**
     * Returns the license of this application.
     *
     * @return string
     */
    public function getLicense(): string
    {
        return self::VALUE_LICENSE;
    }

    /**
     * Returns the author of this application.
     *
     * @return array<int, string>
     */
    public function getAuthors(): array
    {
        return self::VALUE_AUTHORS;
    }

    /**
     * Returns all information.
     *
     * @return array{version: string, license: string, authors: array<int, string>}
     * @throws FileNotFoundException
     */
    public function getAll(): array
    {
        return [
            self::INDEX_VERSION => $this->getVersion(),
            self::INDEX_DATE => $this->getDate(),
            self::INDEX_LICENSE => $this->getLicense(),
            self::INDEX_AUTHORS => $this->getAuthors(),
        ];
    }

    /**
     * Returns the version file.
     *
     * @return string
     */
    public function getVersionFile(): string
    {
        return sprintf('%s/%s', $this->rootDir, self::PATH_VERSION);
    }
}
