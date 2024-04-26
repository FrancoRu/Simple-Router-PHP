# Changelog

Change log for **Simple-Router-PHP**

## [1.0.0] - 2024-04-17

### Added
- **Initial Release:**
  * 4 initial methods, GET, PUT, POST, and DELETE.
  * Ability to use function chaining for custom middleware usage.
  * Construction of global URLs for subsequent processing.

### Additional Notes
- **Initial Implementation for Routes Without Params Only:** The nature of the router itself will lead us to use params, but for now, this functionality will be left for future testing.
- **Testing:** Testing will be added periodically. Warning!!! Version *EXPERIMENTAL ONLY*.

## [1.0.1] - 2024-04-23

### Added
* The addition of four functions for utilizing HTTP verbs was implemented.

### Changed
* The `endpoint` function was modified to make it private and use the new functions.

## [1.0.2] - 2024-04-26

### Added
* Added support for grouping routes with common prefix path or middleware using the `group` method.
