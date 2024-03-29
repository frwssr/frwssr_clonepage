# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## v0.0.1 - 2021-03-23
### Added
- Initial testing version.

## v0.0.2 - 2021-03-24
### Updated…
- `README.md`

## v0.0.3 - 2021-03-31
### Changed…
- `index.php` to fix issues with cloning failing on some pages.

## v0.0.4 - 2021-04-14
### Fixed…
- …the filepath of a cloned home page, so it does not sport a double slash (‘//’) at its front.

## v0.1 - 2022-12-08
### Fixed…
- …issue with double slashes (`/`) in front of the page path on level 2 pages.
- …issue with assuming the database prefix was `perch3_`. Now uses constant `PERCH_DB_PREFIX` to work with different setups.
### Updated…
- …to work with physical and virtual pages. Fieldtpe now usable in Perch Runway 4.x, too.

## v1.0 - 2023-05-26
### Fixed…
- …issue with cloning failing due to custom page attribute fields’ names starting with `page` or `template`.
- …`README.md` to link to my Mastodon profile (rather than the abandoned Twitter one).
### Added…
- …an (albeit rough) error message, for when creation of the new page fails.
### Updated…
- …the `buttonbg` attribute to allow for more complex styling of the field. THIS MIGHT BE A BREAKING CHANGE DEPENDING ON YOUR SETUP TO DATE!
- …the `buttonbg` attribute description in `README.md`.