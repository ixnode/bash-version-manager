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
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\ArrayType\ArrayKeyNotFoundException;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use JsonException;
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

    public const PATH_COMPOSER_JSON = 'composer.json';

    public const INDEX_VERSION = 'version';

    public const INDEX_NAME = 'name';

    public const INDEX_DESCRIPTION = 'description';

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
        return $this->getVersionFile()->getContentAsTextTrim();
    }

    /**
     * Returns the name of this application.
     *
     * @return string
     * @throws ArrayKeyNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     */
    public function getName(): string
    {
        return $this->getComposerKey(self::INDEX_NAME);
    }

    /**
     * Returns the description of this application.
     *
     * @return string
     * @throws ArrayKeyNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     */
    public function getDescription(): string
    {
        return $this->getComposerKey(self::INDEX_DESCRIPTION);
    }

    /**
     * Returns the date of version of this application.
     *
     * @return string
     * @throws FileNotFoundException
     */
    public function getDate(): string
    {
        return $this->getVersionFile()->getDate();
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
            self::INDEX_NAME => $this->getName(),
            self::INDEX_DESCRIPTION => $this->getDescription(),
            self::INDEX_VERSION => $this->getVersion(),
            self::INDEX_DATE => $this->getDate(),
            self::INDEX_LICENSE => $this->getLicense(),
            self::INDEX_AUTHORS => $this->getAuthors(),
        ];
    }

    /**
     * Returns the version file.
     *
     * @return File
     */
    public function getVersionFile(): File
    {
        return new File(sprintf('%s/%s', $this->rootDir, self::PATH_VERSION));
    }

    /**
     * Returns the composer json file.
     *
     * @return File
     */
    public function getComposerFile(): File
    {
        return new File(sprintf('%s/%s', $this->rootDir, self::PATH_COMPOSER_JSON));
    }

    /**
     * Returns a value from composer.json given by key name.
     *
     * @param string|string[] $keys
     * @return string
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     * @throws JsonException
     * @throws ArrayKeyNotFoundException
     */
    public function getComposerKey(string|array $keys): string
    {
        $json = new Json($this->getComposerFile());

        return $json->getKeyString($keys);
    }
}
