![KoalaVM](http://dpr.clayfreeman.com/1kRYJ+ "KoalaVM")

`libkoalaweb`
=============

`libkoalaweb` is a collection of classes that aid in the communication with
`koalasignd` and `koalad` from a web environment.

Install
=======

To install `libkoalaweb`, add this repository as a submodule in your site root
`git submodule add https://github.com/KoalaVM/libkoalaweb.git`.

Usage
=====

First, begin by calling `require_once(...)` with the path to `koalad.php`:

```php
<?php
  require_once(dirname(__FILE__)."/libkoalaweb/koalad.php");
?>
```

Next, instantiate a copy of the `koalad` class with a string containing either
the hostname or IP address of the machine running `koalad`:

```php
<?php
  $koalad = new koalad("hostname.here.example.org");
?>
```

Finally, you can now use the `sendCommand` method of the `koalad` class which,
upon success, will return an array regarding the status of your command:

```php
<?php
  $response = $koalad->sendCommand("startvm", array(
    "type" => "xen",
    "name" => "xen101"
  ));
?>
```

If there was a problem sending your command, `false` will be returned instead.

Example
=======

The following is an example use-case for `libkoalaweb` that will use values from
`$_GET["command"]` and `$_GET["data"]` to issue commands and show their results.

```php
<?php
  require_once(dirname(__FILE__)."/libkoalaweb/koalad.php");
  $koalad = new koalad("hostname.here.example.org");
  $response = $koalad->sendCommand($_GET["command"], $_GET["data"]);

  if ($response != false && is_array($response)) {
    echo "<table border='1' style='margin:100px;'>\n";
    foreach ($response as $key => $val) {
      echo "<tr>\n";
      echo "<td style='padding:10px;'>".$key."</td>\n";
      if (is_array($val))
        echo "<td style='padding:10px;'>".str_ireplace("\n", "<br />\n",
          str_ireplace(" ", "&nbsp;", trim(var_export($val, true))."\n")).
          "</td>\n";
      else
        echo "<td style='padding:10px;'>".$val."</td>\n";
      echo "</tr>\n";
    }
    echo "</table>\n";
  }
?>
```
