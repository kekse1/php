<img src="https://kekse.biz/github.php?draw&override=github:php" />

<br>

# PHP
Many, many years ago I was a Moderator (and l8rs even a "Technical Administrator")
in the PHP area of the "Buschhacker" forum (doesn't exist any longer today).
Maybe I didn't forget *that* much about PHP!?

Since I just wrote another `.php` script for [my **website**](https://kekse.biz/)
and I hadn't a repository for it, I created this new one. That's the reason.

> [!NOTE]
> Since this repository has just been created, I didn't already collected
> so much scripts for here (even if I created really a mass of them..).
> So this one will hopefully grow in time (today my heart doesn't beats
> so much for PHP than before ~20 years...)!

<br>

### News
* \[**2025-04-21**\] Updated the first/only script [`rewrite`.php](#rewritephp), v**0.5.0**;
* \[**2025-04-10**\] Just created this repository.

<br><br>

## Index
1. [News](#news)
2. [Scripts](#php-scripts)
    * [`rewrite`.php](#rewritephp)
3. [Contact](#contact)
4. [Copyright and License](#copyright-and-license)

<br><br><br>

## PHP scripts

<br>

### `rewrite.php`
<a href="src/rewrite.php">
<img id="rewritephp" src="https://kekse.biz/github.php?override=github:php&draw&angle=3&size=56&fg=140,130,20&font=OpenSans&ro&readonly&v=48&h=48&text=%60rewrite.php%60" />
</a>

This script cleans and rewrites HTTP querys in some ways which are useful for [**me**](https://github.com/kekse1/v4/).

* [Version v**0.5.0**](src/rewrite.php) (updated **2025-04-21**)

The main reason was: the [JavaScript](https://github.com/kekse1/javascripts/) part of my websites utilizes
both the `?` search and `#` hash parts of the URL in my own way. The `?fbclid` etc. disturbed my logics.

> [!TIP]
> Maybe of interest: my own `isIP()`, `isIPv4()` and `isIPv6()` functions.
> I could have used `filter_var()`, but this ain't that funny. ^_^

So the most important thing this script does is to remove any `?fbclid` and `?gclid` parameter in the URL/Query.

```
//mit erklaerung: ich wollte das selbst sauber loesen,
//ohne integrierte php-funktionalitaet. grund: meine
//website und anderes interpretieren den query-string
//selbst.. da brauch ich 'ne "saubere" loesung quasi..
//bspw. koennen bei mir auch verteilt mehrere '?' auf-
//tauchen.. und manche params duerfen nicht mit '=' enden,
//und sowas halt. ^_^
```

<br><br>

# Contact
<img src="https://kekse.biz/github.php?override=github:php&draw&text=php@kekse.biz&angle=6&size=38pt&fg=150,20,90&font=OpenSans&ro&readonly&h=64&v=16" />

# Copyright and License
The Copyright is [(c) Sebastian Kucharczyk](./COPYRIGHT.txt),
and it's licensed under the [MIT](./LICENSE.txt) (also known as 'X' or 'X11' license).

<a href="https://kekse.biz/">
<img src="favicon.png" alt="Favicon" />
</a>

