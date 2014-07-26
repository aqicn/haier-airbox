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

Comment
----

You will need to edit the line 109 of the file ``haier-airbox.php`` and insert your own appkey

```php
"appKey: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", 
```

