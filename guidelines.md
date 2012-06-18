Operators
---------

**Never** use the `OR` and `AND` operators. Although being more readable they
have a dangerous caveat: they have the [lowest operator precedence][1].

Example:
```php
$a = true && false;
$b = true AND false;

var_dump($a, $b);
```

Output:
```php
bool(false)
bool(true)
```

[1]: http://fr2.php.net/manual/en/language.operators.precedence.php (PHP Operators Precedence)


Classes
-------
If you implement `__get` in a class, **always** implement `__isset`. Not doing
it can lead to strage behavior:
```php
class Foo {
	protected $bar = true;
	public function __get($name) { return $this->$name; }
}
$foo = new Foo();
var_dump($foo->bar, empty($foo->bar));
```

Output:
```php
bool(true)
bool(true)
```

Here, `$foo->bar` is `true` and empty at the same time. This is because empty
does not check the value of the property but its availability in the current
scope. Outside the object scope, every protected or private property is empty
unless you define `__isset`.

