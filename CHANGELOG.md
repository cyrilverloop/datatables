# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1] - 2021-03-05
### Fixed
- Avoid `$this` leaking from the constructors of `Columns` and `Orders`.

## [2.0.0] - 2021-02-22
### Changed
- In `Columns` and `Orders` classes, the `add()` does not return `$this` anymore and has `void` as a return type.

## [1.0.0] - 2021-02-04
### Added
- DataTables classes.
