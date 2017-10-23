<?php

$a=['a','b','c'];
$b=['b','c','d'];



$c=array_merge($a,$b);
$c=array_combine($c,$c);
print_r($c);