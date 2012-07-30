`extract`
---------
`extract` is dangerous when working with user input and makes the code hard to
read. If you really want to use `extract` **always** use it on an array with
known keys and **always** comment what keys are extracted.

Example:
```php
<?php
function foo() {
	return array('bacon' => 'good', 'beans' => 'meh');
}

// BAD
extract(foo()); // keys: bacon, beans

// WORSE
extract($_POST); // summoning בְּלִיַּ֫עַל
```


`foreach($array as &$b)`
----------------------
Don't use references inside `foreach`, use the key to modify the array.  
If you really need a reference, **always** `unset` it after the loop. Failing
to do so will expose you to weird bugs if you re-use the variable name later.



`OR` and `AND`
---------
**Never** use the `OR` and `AND` operators. Although being more readable they
have a dangerous caveat: they have the [lowest operator precedence][1].

Example:
```php
<?php
$a = true && false;
$b = true AND false;

var_dump($a, $b);
```

Output:
```
bool(false)
bool(true)
```

[1]: http://fr2.php.net/manual/en/language.operators.precedence.php (PHP Operators Precedence)



`__get` and `__isset`
-------
If you implement `__get` in a class, **always** implement `__isset`. Not doing
it can lead to strange behavior:
```php
<?php
class Foo {
	protected $bar = true;
	public function __get($name) { return $this->$name; }
}
$foo = new Foo();
var_dump($foo->bar, empty($foo->bar));
```

Output:
```
bool(true)
bool(true)
```

Here, `$foo->bar` is `true` and empty at the same time. This is because empty
does not check the value of the property but its availability in the current
scope. Outside the object scope, every protected or private property is empty
unless you define `__isset`.



Do and don't
----------
* **Never** use `eval`.
* Never use `global` or `$GLOBALS`, if you really need a global, use a static class.
* Don't (micro-)optimize. If you need speed, just don't use PHP.
* **Never** trust user input. Consider all super-globals as user input (including `$_ENV` and `$_SERVER`).
* Use Xdebug, it's good for you.
* Use `error_reporting(-1)` to enable all errors, even in production (but
  don't display errors, just log them).
* Read the doc often even when it's not really needed. Most of PHP
  by-design bugs are documented so help yourself and know them before they
  bite you.

