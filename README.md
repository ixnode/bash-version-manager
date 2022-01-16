# BashVersionManager

The `bin/version-manager` tool helps you to _maintain_ the versions in the current path. It increments
the patch, minor or major version and replaces them in the corresponding files.

## Create new `VERSION` file

The `VERSION` file is used as the source for the version number. A missing file can be created
automatically.

```bash
❯ bin/version-manager --current

[2022-01-16 16:51:41] (error)   → Version file "~/ixno/bash-version-manager/VERSION" not found.

Do you want to create a version file "VERSION" with version "0.1.0"? y

[2022-01-16 16:51:43] (error)   → Version file "~/ixno/bash-version-manager/VERSION" successfully created.
```

## Optional: Create `.env` file

Currently, the variable `VERSION_APP` can be replaced in the `.env` file:

```bash
❯ cat <<EOT >> .env
# Version of this app
VERSION_APP=$(cat VERSION)

# Version of this app
VERSION_APP_LATEST=latest
EOT
```

## Show current version

If you want to see the current version, use the following command.

```bash
❯ bin/version-manager --current

Current version: 0.1.0

Next major version: 1.0.0 (Use bin/version-manager --major)
Next minor version: 0.2.0 (Use bin/version-manager --minor)
Next patch version: 0.1.1 (Use bin/version-manager --patch)
```

## Switch patch version

To change the patch version use the flag `--patch`.

```bash
❯ bin/version-manager --patch

Current version: 0.1.0
New version:     0.1.1

Do you want to set the new version "0.1.1" (y/n)? y

[2022-01-16 16:57:46] (info)    → Set version "0.1.1" to "~/ixno/bash-version-manager/VERSION"
[2022-01-16 16:57:46] (success) → Done.
[2022-01-16 16:57:46] (info)    → Set version "0.1.1" to "~/ixno/bash-version-manager/.env"
[2022-01-16 16:57:46] (success) → Done.
```

## Switch minor version

To change the minor version use the flag `--minor`.

```bash
❯ bin/version-manager --minor

Current version: 0.1.0
New version:     0.2.0

Do you want to set the new version "0.2.0" (y/n)? y

[2022-01-16 16:58:32] (info)    → Set version "0.2.0" to "~/ixno/bash-version-manager/VERSION"
[2022-01-16 16:58:32] (success) → Done.
[2022-01-16 16:58:32] (info)    → Set version "0.2.0" to "~/ixno/bash-version-manager/.env"
[2022-01-16 16:58:32] (success) → Done.
```

## Switch major version

To change the major version use the flag `--major`.

```bash
❯ bin/version-manager --major

Current version: 0.1.0
New version:     1.0.0

Do you want to set the new version "1.0.0" (y/n)? y

[2022-01-16 16:59:06] (info)    → Set version "1.0.0" to "~/ixno/bash-version-manager/VERSION"
[2022-01-16 16:59:06] (success) → Done.
[2022-01-16 16:59:06] (info)    → Set version "1.0.0" to "~/ixno/bash-version-manager/.env"
[2022-01-16 16:59:06] (success) → Done.
```

## Switch to given version

A custom version (e.g. `1.2.3`) for changing versions can be specified as a parameter.

```bash
❯ bin/version-manager 1.2.3

Current version: 0.1.0
New version:     1.2.3

Do you want to set the new version "1.2.3" (y/n)? y

[2022-01-16 18:11:41] (info)    → Set version "1.2.3" to "/home/bjoern/Development/ixno/bash-version-manager/VERSION"
[2022-01-16 18:11:41] (success) → Done.
[2022-01-16 18:11:41] (info)    → Set version "1.2.3" to "/home/bjoern/Development/ixno/bash-version-manager/.env"
[2022-01-16 18:11:41] (success) → Done.
```

## Use another working directory

Usually the tool uses the current working directory. This can be changed.

```bash
❯ bin/version-manager --working-dir test --current

Current version: 0.2.1

Next major version: 1.0.0 (Use bin/version-manager --major)
Next minor version: 0.3.0 (Use bin/version-manager --minor)
Next patch version: 0.2.2 (Use bin/version-manager --patch)
```

## Show help

Shows the parameters and arguments of the tool.

```bash
❯ bin/version-manager --help

version-manager 0.1.0 (2022-01-16) - Björn Hempel <bjoern@hempel.li>

Usage: version-manager [...options] [version]

 -c,    --current                     Shows the current version.

 -X,    --major                       Will increase the major version (x.0.0).
 -m,    --minor                       Will increase the minor version (0.x.0).
 -p,    --patch                       Will increase the patch version (0.0.x).

 -w,    --working-dir                 Sets the current working-dir (default: "~/ixno/bash-version-manager")

 -s,    --show-hints                  Shows some hints.
 -h,    --help                        Shows this help.
 -V,    --version                     Shows the version number.
```

## Show git hints

Shows some useful commands for committing to the Git repository.

```bash
❯ bin/version-manager --show-hints

Now you can do the following:


→ Edit your CHANGELOG.md file

❯ vi CHANGELOG.md


→ Usually version changes are set in the main or master branch

❯ git checkout main && git pull


→ Commit your changes to your repo

❯ git add CHANGELOG.md && git commit -m "Add version $(cat VERSION)" && git push


→ Tag your version

❯ git tag -a "$(cat VERSION)" -m "Version $(cat VERSION)" && git push origin "$(cat VERSION)"
```

## Execute tests

If you want to make changes, the test mode helps to check the current changes. Test mode uses the
files in the test folder:

* `.env` (source file)
* `.env.major` (comparison file with major change)
* `.env.minor` (comparison file with minor change)
* `.env.patch` (comparison file with patch change)
* `VERSION` (source file)
* `VERSION.major` (comparison file with major change)
* `VERSION.minor` (comparison file with minor change)
* `VERSION.patch` (comparison file with patch change)

```bash
❯ bin/version-manager --test

[2022-01-16 16:50:57] (info)    → The sha1 keys match (major).
[2022-01-16 16:50:57] (info)    → The sha1 keys match (major).

[2022-01-16 16:50:57] (info)    → The sha1 keys match (minor).
[2022-01-16 16:50:57] (info)    → The sha1 keys match (minor).

[2022-01-16 16:50:57] (info)    → The sha1 keys match (patch).
[2022-01-16 16:50:57] (info)    → The sha1 keys match (patch).

[2022-01-16 16:50:57] (success) → All tests were completed successfully.
```

## License

This tool is licensed under the MIT License - see the [LICENSE.md](/LICENSE.md) file for details
