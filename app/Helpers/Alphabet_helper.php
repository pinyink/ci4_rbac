<?php

function number_to_alphabet($number) {
    $number = intval($number);
    if ($number <= 0) {
       return '';
    }
    $alphabet = '';
    while($number != 0) {
       $p = ($number - 1) % 26;
       $number = intval(($number - $p) / 26);
       $alphabet = chr(65 + $p) . $alphabet;
   }
   return $alphabet;
  }

 function alphabet_to_number($string) {
     $string = strtoupper($string);
     $length = strlen($string);
     $number = 0;
     $level = 1;
     while ($length >= $level ) {
         $char = $string[$length - $level];
         $c = ord($char) - 64;        
         $number += $c * (26 ** ($level-1));
        $level++;
     }
    return $number;
 }