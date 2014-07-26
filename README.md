haier-airbox
============

Scripts for extracting real-time data from Haier's AirBox

Usage
----

```php

$reader = new HaierAirBoxReader("username","password");
$data = $reader->getData();

print("<pre>".print_r($data,true)."</pre>");

```