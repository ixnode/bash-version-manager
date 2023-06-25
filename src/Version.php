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

namespace Ixnode\BashVersionManager;

use Composer\Autoload\ClassLoader;
use Ixnode\PhpContainer\File;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\ArrayType\ArrayKeyNotFoundException;
use Ixnode\PhpException\Case\CaseInvalidException;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\File\FileNotReadableException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use JsonException;
use ReflectionClass;

/**
 * Class Version
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.1 (2023-06-24)
 * @since 0.1.1 (2023-06-24) Refactoring. Add more versions.
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

    public const INDEX_PHP = 'php-version';

    public const INDEX_COMPOSER = 'composer-version';

    public const INDEX_PHP_EXCEPTION = 'php-exception';

    protected const APP_COMPOSER = 'composer';

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
     * @throws CaseInvalidException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
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
     * @throws CaseInvalidException
     * @throws FileNotFoundException
     * @throws FileNotReadableException
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
     * Returns the composer version.
     *
     * @return string
     */
    public function getVersionComposer(): string
    {
        return $this->executeComposerCommand(sprintf('%s -V', self::APP_COMPOSER));
    }

    /**
     * Returns the php version of this application.
     *
     * @return string
     */
    public function getVersionPhp(): string
    {
        return phpversion();
    }

    /**
     * Shows the composer package version.
     *
     * @param string $package
     * @return string
     */
    public function getVersionComposerPackage(string $package): string
    {
        return $this->executeComposerCommand(sprintf('%s show | grep "%s"', self::APP_COMPOSER, $package));
    }

    /**
     * Returns all information.
     *
     * @return array{version: string, license: string, authors: array<int, string>}
     * @throws ArrayKeyNotFoundException
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
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
            self::INDEX_PHP => $this->getVersionPhp(),
            self::INDEX_COMPOSER => $this->getVersionComposer(),
            self::INDEX_PHP_EXCEPTION => $this->getVersionComposerPackage('ixnode/php-exception'),
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
     * @throws ArrayKeyNotFoundException
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     * @throws CaseInvalidException
     * @throws FileNotReadableException
     */
    public function getComposerKey(string|array $keys): string
    {
        $json = new Json($this->getComposerFile());

        return $json->getKeyString($keys);
    }

    /**
     * Executes the composer command and returns the version.
     *
     * @param string $command
     * @return string
     */
    private function executeComposerCommand(string $command): string
    {
        $output = [];

        $returnValue = null;

        exec($command, $output, $returnValue);

        if ($returnValue !== 0) {
            return sprintf('%s is not available', self::APP_COMPOSER);
        }

        $string = implode("\n", $output);

        $matches = [];

        $result = preg_match('~[0-9]+\.[0-9]+\.[0-9]+~', $string, $matches);

        if ($result !== 1) {
            return sprintf('Unable to get %s version.', self::APP_COMPOSER);
        }

        return strval(current($matches));
    }
}
