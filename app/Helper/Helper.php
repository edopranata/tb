<?php
if(function_exists('format_IDR')){
    function format_IDR($value){
        return "Rp. " . number_format($value, 2);
    }
}

if (function_exists('format_NUM')){
    function numeric_IDR($value){
        return preg_replace('#[^0-9\.]#', '', $value);
    }
}
