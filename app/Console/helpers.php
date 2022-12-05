<?php
function make_slug($string){
    return preg_replace('/\s+/u','-',trim($string));
}

    function generateSKU()
    {
        $number=mt_rand(10000,99999);
        if (checkSKU($number)){
            return generateSKU();
        }else{
            return (string)$number;
        }
    }

    function checkSKU($number)
    {
        return \App\Models\Product::where('sku','=',$number)->exists();
    }





