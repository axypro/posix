## 0.2.0 (20.01.2024)

* #1: fix the password field in user/group info (it is "passwd", not "password")

## 0.1.2 (20.01.2024)

* Support PHP 8.3 (test in GitHub action)
* Tests refactoring
* PosixConstants: added constants that are defined in 8.3
* #2: support new functions from PHP 8.3
    * [eaccess](https://www.php.net/manual/ru/function.posix-eaccess.php)
    * [fpathconf](https://www.php.net/manual/en/function.posix-fpathconf.php)
    * [sysconf](https://www.php.net/manual/en/function.posix-sysconf.php)
    * [pathconf](https://www.php.net/manual/en/function.posix-pathconf.php)

## 0.1.1 (15.10.2023)

* Error constants (`PosixErrors`)

## 0.1.0 (14.10.2023)

* The interface (`IPosix`)
* The standard implementation (`RealPosix`)
* The listeners mechanism (`IPosixListener`)
* Throws exceptions instead return codes
* Data classes instead associative arrays
