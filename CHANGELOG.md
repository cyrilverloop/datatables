# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [4.0.0] - 2022-11-25
### Added
- Infection.
- Phpcbf.
- Test groups.

### Changed
- Documentation for continuous integration.
- `Order::DIR_ASC` and `Order::DIR_DESC` are replaced by `Direction` enumeration.
- Requires PHP 8.1 as a minimum version.

## [3.1.0] - 2022-04-06
### Added
- phpDocumentor.
- Documentation for development.

### Changed
- "build" directory to "ci".

## [3.0.1] - 2022-01-27
### Fixed
- README minimum compatibility of PHP 8.0.

## [3.0.0] - 2022-01-27
### Added
- Compatibility with PHP 8.1.

### Changed
- Requires PHP 8.0 as a minimum version.

## [2.0.2] - 2021-09-23
### Changed
- Bumped dependency of `cyril-verloop/iterator` from 1.1.0 to 2.0.0.

## [2.0.1] - 2021-03-05
### Fixed
- Avoid `$this` leaking from the constructors of `Columns` and `Orders`.

## [2.0.0] - 2021-02-22
### Changed
- In `Columns` and `Orders` classes, the `add()` does not return `$this` anymore and has `void` as a return type.

## [1.0.0] - 2021-02-04
### Added
- DataTables classes.
