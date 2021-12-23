# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0-beta.2] 23-12-2021
### Additions
- The `Presenter` class now has a `toArray()` method which will return ALL presentables as an array (including hidden presentables).

## [2.0.0-beta.1] 14-12-2021
### Additions
- Model classes can now specify presentable classes by passing the attribute name and the class path to the `$presentables` array attribute.
- If the class cannot be found, a new `InvalidPresentableClass` exception will be thrown.
- Added the ability to hide presenters from the array by implementing the `IsHidden` interface.
- Added the presenter options. Options can be passed to presenter classes by passing an array to each item in the `$presentables` array.
- Added the ability for presenter classes to have access to the attribute from the `$presentables` array.
- Default presentables can be added to the `presentable` config file and will be added to all `IsPresentable` classes.

## [1.1.0] 06-07-2021
### Changes
- The signature for the `Presenter` class has been changed. The first `Model` parameter has been removed as it was never actually used.
- The `IsPresentable` trait has been updated to reflect the above change.
- This is a potential breaking change if the `Presenter` class is overridden.

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
