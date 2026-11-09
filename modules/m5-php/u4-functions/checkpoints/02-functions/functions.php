<?php
// Functions

// Copy by Value example

// function additionByValue($num) {
//   return $num+=10;
// }

// $a = 4;

// echo $a;
// additionByValue(10);
// echo $a;

// -------- Copying by reference

// function additionByRef(&$num) {
//   return $num+= 10;
// }

// $b = 5;
// echo $b;
// additionByRef($b);
// echo $b;


// function multiplyBy2($a, $b = 2){
//   return $a * $b;
// }

// echo multiplyBy2(4);

// $multiply = function () {
//   $total = 1;
//   foreach (func_get_args() as $num) {
//     $total *= $num;
//   }
//   printf('this is the variable inside of the function %d', $total);
//   return $total;
// };

// $add = fn($a, $b) => $a + $b;

// printf('This is the sum of 78 + 83 = %d', $add(78, 83));


// $productPrices = [23.23, 35.89, 89.21];
// $result = array_map(fn($price) => '€' . number_format($price, 2,'.','') .'', $productPrices);
// print_r($result);

// Closures

// $cartTotals = [23.4, 59.23, 49.12];
// $freeThreshold = 40;

// $processedCart = array_map(
//   function ($price) use ($freeThreshold) {
//     $delivery = '';
//     if ($price > $freeThreshold) {
//       $delivery = 'Free Delivery!!';
//   }
//    return '€' . number_format($price, 2, '.', ',') . "$delivery";
//   },
//   $cartTotals
// );

// print_r($processedCart);  

function multiply (int $a, int $b): void {
  echo $a * $b;
}

multiply (2, 20);