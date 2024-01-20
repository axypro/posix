# axy/posix

Mockable wrapper for POSIX functions.

[![Latest Stable Version](https://img.shields.io/packagist/v/axy/posix.svg?style=flat-square)](https://packagist.org/packages/axy/posix)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg?style=flat-square)](https://php.net/)
[![Tests](https://github.com/axypro/posix/actions/workflows/test.yml/badge.svg)](https://github.com/axypro/posix/actions/workflows/test.yml)
[![Coverage Status](https://coveralls.io/repos/github/axypro/posix/badge.svg?branch=master)](https://coveralls.io/github/axypro/posix?branch=master)
[![License](https://poser.pugx.org/axy/posix/license)](LICENSE)

The library provides the interface `axy\posix\IPosix` which almost completely reproduces [the POSIX functions list](https://www.php.net/manual/en/ref.posix.php).
The standard implementation is also provided.
It is the `RealPosix` class that just delegates execution to those functions.

What benefits of using this?
Except of some minor improvements (see below), using an object with a specified interface allows you to change implementation.
Firstly, this is useful for tests.
The `RealPosix` itself provides the listener functionality (see below).

## IPosix

The interface contains methods similar to standard `posix_*()` functions.

* Method names doesn't contain prefix `posix_`. Otherwise, the names as identical. It can be possible change they to more human-readable style, but it is the standard, you know.
* An exception is thrown when an error is happened (standard functions usually return `FALSE`).
* For same reason there are no methods for get the last error code.
* Structures that are returned by some methods (`getgrgid()` for example) are converted to objects of certain data-classes (standard functions return associative arrays).

## Exceptions

Are located in `axy\posix\exceptions`:

* `IPosixException` - the main interface
    * `PosixException` - a standard function error (extends `LogicException`, just because)
        * The constructor takes an error code as only argument
        * An exception object has readonly properties:
            * `$posixErrorCode (int)` - the origin error code, is equal to the exception code
            * `$posixErrorConstant (?string)` - the error constant name (`EPERM` for example, NULL if not defined)
            * `$posixErrorMessage (string)` - the error message as `posix_strerror()` returned
    * `PosixNotImplementedException` - the corresponding function is not defined (for example, `getpgid` is not defined on all systems, some functions were added in 8.3)

## Structures

All the following structures is data classes.
Each of them has the property `$data` that contains the original array and other named properties for elements of that array.

* `PosicUserInfo` - returned by `getpwnam()`, `getpwuid()`
* `PosixGroupInfo` - returned by `getgrgid()`, `getgrnam()`
* `PosixTimesInfo` - returned by `times()`
* `PosixUnameInfo` - returned by `uname()`
* `PosixResourceLimits` - returned by `getrlimits()`, contains two subobjects `hard` and `soft` with a similar structure

## Constants

All constants that used as method arguments are collected in the `PosixConstants` class.
These are just copies of [the standard constants](https://www.php.net/manual/en/posix.constants.access.php) (without the prefix `POSIX_`).
The PHP version allows collect these in the `IPosix` but this would clutter the interface with rarely used elements.

## Error codes

Error codes constants are collected in `PosixErrors`.
There is a method `PosixErrors::getConstName(int $code): ?string` that returns the constant name for a code.

## Listener

Listeners for `RealPosix` allow to make a logger or a simple mock.

```php
$posix = new RealPosix($listener);
```

A listener must implement `axy\posix\IPosixListener` or extend `PosixListener`.
There are two methods:

* `before(string $method, array $args, ?int &$code = null): mixed`
* `after(string $method, array $args, mixed $result, ?int &$code = null): mixed`

`before()` is called before the call of a real function.
It takes the method name (such "getgid", without the prefix) and the arguments list.
If it returns any value except `NULL` it is considered as the result and the real function is not called.

`after()` is called, respectively, at the end and takes also `$result` (as the result of performance, it also can be the result of `before()`).
It can change the result and return it.
If you don't want change result here just return the `$result` argument.

* Both methods must return values in the standard function format. For `getgrgid()` it is an array, not a data object.
* The methods must follow the return value type. If they return `int` when `string` is required it will lead to the fatal error.
* To signal an error they can throw `PosixException` or return the standard value for this case (usually it is `FALSE` or -1).
* Both method takes `$code` by a link and can change it.
  It will be used as the error code if an error will be detected.
  `before()` takes it as `NULL`, `after()` takes it changed by `before()` or the real error code.
  If an error is happened and `$code` was not changed by listeners will be used the real code from `posix_get_last_error()`.
