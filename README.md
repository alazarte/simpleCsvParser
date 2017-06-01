# Simple CSV Parser in PHP

This repo contains a simple script written in PHP, that parses a csv file in 
a specific format, to another csv.

The script takes as an argument the name of the csv file that you want to 
parse. The csv has to have the following format:

```
[device][objetct][indicator][timestamp][value]
```

The output csv reads each line and generates files depending on the timestamp 
found in the input file. The files generated contains the summation of all 
the values, and its average, grouped by device and object.

The name of the output files should be identified by timestamp:

```
results_[timestamp].csv
```

The output files should have the following format:

```
[device][objetct][sum][timestamp][sum all values]
```
```
[device][objetct][count][timestamp][avg all values]
```

The idea to store the result was having a multidimensional array, to 
distinguish timestamps. Then, each timestamp have its own set of arrays to 
store its average and summations, where the key in each one is the device and 
the object.

### Reference sites

#### Functions from PHP manual

Some of the functions of the program, the manual helped me to know how they 
work:

[fgetcsv()](http://php.net/manual/en/function.fgetcsv.php)
Split lines in the csv files to arrays.

[foreach()](http://php.net/manual/en/control-structures.foreach.php)
Iterate trough the arrays declared.

[explode()](http://php.net/manual/en/function.explode.php)
Just to check if the input file is '.csv'.

[file\_put\_contents()](http://php.net/manual/en/function.file-put-contents.php)
Write contents to file without open() nor close().

[$argv](http://php.net/manual/en/reserved.variables.argv.php)
Command line arguments in PHP.

[class and visibility](http://php.net/manual/en/language.oop5.late-static-bindings.php)
Encapsulate everything in a class.

#### Similar repositories

[PHPCsvParser](https://github.com/kzykhys/PHPCsvParser)

#### Stackoverflow questions

[Search in multidimensional array](https://stackoverflow.com/questions/8102221/php-multidimensional-array-searching-find-key-by-specific-value)

[Delete key from array](https://stackoverflow.com/questions/5450148/php-remove-key-from-associative-array)

[Get last element of array w/o deleting it](https://stackoverflow.com/questions/3687358/whats-the-best-way-to-get-the-last-element-of-an-array-without-deleting-it)
