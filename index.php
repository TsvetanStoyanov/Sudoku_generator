<?php
// include class
include(__DIR__ . '/class.Sudoku.php');

// get filename
$file_name = 'input_data.txt';
$get_file = file_get_contents($file_name);

// split every char
$split_txt = str_split($get_file);

$splicer = [9, 9, 9, 9, 9, 9, 9, 9, 9];
$result_arr = [];
foreach ($splicer as $k => $v) {
    $result_arr[$k] = array_splice($split_txt, 0, $v);
}

$game = new Sudoku();
$game->solver($result_arr);

// load file
$file = 'result.txt';
$current = file_get_contents($file);

// upload result to file
file_put_contents($file, $game->get_result());
