# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## Releases

### [0.1.8] - 2023-08-14

* Change copyright year from 2022 to 2023

### [0.1.7] - 2023-06-25

* Add composer package version parser

### [0.1.6] - 2023-06-25

* Add php and composer version

### [0.1.5] - 2022-12-30

* Add name and description to version information

### [0.1.4] - 2022-12-30

* Add Version class

### [0.1.3] - 2022-12-18

* Update README.md

### [0.1.2] - 2022-12-18

* Add composer.json to install this library via PHP composer package

### [0.1.1] - 2022-04-06

* Change order of adding new version
* Change documentation (README.md)

### [0.1.0] - 2022-01-03

* Initial release
* Add help and version area
* Add patch, minor and major mode
* Add direct version change mode
* Add Show hints area
* Add test modus
* Add README.md
* Add LICENSE.md

## Add new version

```bash
# Checkout master branch
$ git checkout main && git pull

# Check current version
$ bin/version-manager --current

# Increase patch version
$ bin/version-manager --patch

# Change changelog
$ vi CHANGELOG.md

# Push new version
$ git add CHANGELOG.md VERSION && git commit -m "Add version $(cat VERSION)" && git push

# Tag and push new version
$ git tag -a "$(cat VERSION)" -m "Version $(cat VERSION)" && git push origin "$(cat VERSION)"
```
