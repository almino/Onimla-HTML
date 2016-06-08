# Example #1
```php
$select = new \Onimla\HTML\Select('foo');
$option = new \Onimla\HTML\Option(7, 'Ultimate');


$select->appendOption('bar', 'FooBar');
$select->appendOption(FALSE, 'bar');
$select->append($option);

# Select second option
$select->eq(1)->select();

if ($row->id == $option->value()->getValue()) {
    $select->deselectAll();
    $option->select();
}

echo $select;
```
will output

```html
<select name="foo">
  <option value="bar">FooBar</option>
  <option>bar</option>
  <option value="7" selected="selected">Ultimate</option>
</select>
```
# Example #2 (best use case)
```php
echo new Onimla\HTML\BrazilDateInterval(date('Y-03-27 9:00'), date('Y-03-27 17:00'));
```
will output

```
27 de março, 09h às 17h
```

```html
<span class="DatePeriod date-period DateInterval date-interval DateTimePeriod datetime-period date-time-period DateTimeInterval datetime-interval date-time-interval year-now same-day equal-day day-equal">
	<time datetime="2016-03-27T09:00:00-03:00" class="begins ini init inits initial start starts">
    <span class="date_medium date-medium begin-datedateinitial-datestart-date" data-value="2016-03-27">27 de março</span><span class="date-separatorseparator">, </span>
    <span class="before-hour"></span>
    <span class="time_short time-short begin-timeinitial-timestart-timetime" data-value="09:00:00">09h</span>
    </time>
    <span class="time-separatorbetween-time"> às </span>
    <time datetime="2016-03-27T17:00:00-03:00" class="end ends">
    	<span class="time_short time-short" data-value="17:00:00">17h</span>
    </time>
</span>
```
