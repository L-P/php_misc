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
```
  bool(false)
  bool(true)
```

[1]: http://fr2.php.net/manual/en/language.operators.precedence.php (PHP Operators Precedence)

