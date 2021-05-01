```php
$nik = new NIK('1102210110900001');
print_r($nik->parse());
// Array
// (
//     [nik] => 1102210110900001
//     [unique] => 0001
//     [provinsi] => ACEH
//     [kota] => KAB. ACEH TENGGARA
//     [kabupaten] => KAB. ACEH TENGGARA
//     [kecamatan] => 
//     [birthday] => 01/10/1990
// )
```

```php

if($nik->isValid()) {
    echo "NIK valid";
}

```
