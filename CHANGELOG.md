# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] 22-05-2021
### Changes
- Included a configuration file. The default array key (`presentable`) can now be configured.
- Some simplification in the `IsPresentable` trait.
- Protected method `getBaseAttrinbutes` has been renamed to `getOriginalAttributes`.

## [0.2.0] 25-03-2021
### Changed
- Renamed the `present()` to `presentable()` so that it now matches the key name when converting the model to an array.
- Added `getBaseAttributes()` method to the `IsPresentable` trait which will now test for the existence of a `toArray` method on the parent class before trying to call `parent::toArray()`. This might allow for the use of `IsPresentable` outside of Laravel projects (not tested).
- Updated the `toArray` method on `IsPresentable` to get an array of attributes from the  `getBaseAttributes` method.
