Dev.php
=======

Helpers for PHP dev.  Provides:

- `d()`, `TJM\Dev::dump()`, and `TJM\Dev::getDump()` for dumping.  Outputs (or gets) a string result of `var_dump()` or `dump()`.  However, when passed a class name or callable that is user defined, it will output the file with line numbers where that class or callable is defined and its definition from that file.
- `TJM\Dev\Test\TestCase`: Test case for PHPUnit, subclass of `PHPUnit\Framework\TestCase` with some assertions / helpers.

License
------

<footer>
<p>SPDX-License-Identifier: 0BSD OR Unlicense OR CC0-1.0</p>
</footer>
