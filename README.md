haier-airbox
============

Scripts for extracting real-time data from Haier's AirBox. For more information, check the aqicn experiment page at http://aqicn.org/experiments/haier-air-box/

Usage
----

```php
$reader = new HaierAirBoxReader("username","password");
$data = $reader->getData();
```

Where username should be your phonenumber.

```php
print("<pre>".print_r($data,true)."</pre>");
```

Woud return:

```php
stdClass Object
(
    [dateTime] => 20140726103848
    [temperature] => 668
    [humidity] => 384
    [pm25] => 20
    [voc] => 20
    [mark] => 88
    [markInfo] => 空气很棒 
    [rank] => 0
)
```

Comment
----

You will need to edit the line 109 of the file ``haier-airbox.php`` and insert your own appkey

```php
"appKey: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx", 
```

