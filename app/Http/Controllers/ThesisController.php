<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThesisController extends Controller
{
    // preprocessing stage
    function caseFolding($str)
    {
        return strtolower($str);
    }

    function punctuationRemoval($str)
    {
        return str_replace([
            '?', '!', '.', '<', '>', 
            '/', ';', '\'', ':', '\"', 
            '[', ']', '{', '}', '-', 
            '_', '+', '=', '@', '#', 
            '$', '%', '^', '&', '*',
            '(', ')', '`', '~', '|', 
            ' ', ',', '\\'
        ], '', $str);
    }

    // =========================================================================================================

    function kGram($str, $n)
    {
        // to save substrings
        $subsInsert = [];
        // save string length of the inserted string
        $str_length = strlen($str);
        // get the substrings and save them into $subsInsert variable
        for ($i=0; $i <= $str_length-$n; $i++) { 
            $tmpSubstr = substr($str, $i, $n);
            $subsInsert[] = $tmpSubstr;
        }
        return $subsInsert;
    }

    function rollingHash($subStrings)
    {
        // to save the hash value of the operation
        $hashesInsert = [];
        // hash value before modulo operation
        $subStrHash = [];
        // operation before modulo
        foreach ($subStrings as $key => $value) {
            $tmpSubstr = $value;
            $tmpHash = [];
            // multiply the existing character of the substring by using prime number (11) powered by the index
            for ($j=0; $j < strlen($tmpSubstr); $j++) { 
                // powering prime number comes first bcs operation of power must be done first before multiplication
                $primesMultiplicator = pow(11, strlen($tmpSubstr)-($j+1));
                // split the existing character of the substring by index 
                $tmpHashChar = ord(str_split($tmpSubstr)[$j])*$primesMultiplicator;
                // save the result into a temporary array variable
                $tmpHash[] = $tmpHashChar;
            }
            // sum the entire hash of the temporary array variable and push it into a new array variable
            $subStrHash[] = array_sum($tmpHash);
            // loop ends and it will reset the values of the existing temporary variables
        }
        // modulo operation of the rolling hash
        foreach ($subStrHash as $key => $value) {
            // pushes the result of the modulo operation into an array variable for each hash
            $hashesInsert[] = $value % 10007;
        }
        // returns the value of the operation
        return $hashesInsert;
    }

    function fingerprints($hashes1, $hashes2)
    {
        // the fingerprint looks for the hash couples or the intersection of hash between the both existing hash collections
        return array_intersect($hashes1, $hashes2);
    }

    function window($hashes, $w)
    {
        // to save temporary window values of the hash collection
        $window = [];
        for ($i=0; $i <= count($hashes)-$w; $i++) { 
            // to save each array of a window
            $tmpSubWindow = [];
            for ($j=0; $j < $w; $j++) {
                $tmpSubWindow[] = $hashes[$j+$i];
            }
            $window[] = $tmpSubWindow;
        }
        return $window;
    }

    function windowString($window)
    {
        // to save array values of a window in a shape of a string with "|" as separator
        $windowString = [];
        foreach ($window as $key => $array) {
            // it contains value of a window in order to be pushed into the $windowString variable
            $tmpWindowString = "";
            foreach ($array as $key2 => $value) {
                if ($key2 == count($array)) {
                    $tmpWindowString .= $value;
                }else{
                    $tmpWindowString .= $value."|";
                }
            }
            $windowString[] = $tmpWindowString;
            // print_r($array);
        }
        return $windowString;
    }

    function windowFingerprint($windowInsert, $windowData)
    {
        // takes only matching windows from the both arrays
        $windowFingerprint = array_intersect($windowInsert, $windowData);
        return $windowFingerprint;
        // echo "<br>";
        // print_r($windowInsert);
        
        // echo "<br>";
        // print_r($windowData);

        // echo "<br>";
        // intersection of both windowInsert and windowData
        // return array_intersect($windowInsertString, $windowDataString);
    }
    
    function diceSimilarity($fingerprint, $hashesData, $hashesInsert)
    {
        // implementation of dice similarity formula S = (2*C)/(A+B)
        // C is the fingerprint or hash intersection of the both hash collection
        // A and B are containing the existing hash amount of the respective strings
        $c = count($fingerprint);
        $a = count($hashesData);
        $b = count($hashesInsert);

        $s = (2*$c)/($a+$b);
        return $s * 100;
    }


    // ========================================================================================================
    // The main algorithm
    function rabinKarp($data, $n, $insert)
    {
        // preprocessing stage of the inserted data
        $preInsert = $this->punctuationRemoval($this->caseFolding($insert));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->kGram($preInsert, $n);
        $hashesInsert = $this->rollingHash($subsInsert);

        // ==============================================================================================================================================================
        // preprocessing stage of the exxisting data
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->punctuationRemoval($this->caseFolding($value));
            $preData[] = $tmpPreData;
        }

        // processed data of the existing data
        $subsData = [];
        // foreach ($data as $key => $value) {
        foreach ($preData as $key => $value) {
            $tmpSubsData = $this->kGram($value,$n);
            $subsData[] = $tmpSubsData;
        }
        $hashesData = [];
        foreach ($subsData as $key => $value) {
            $tmpHashData = $this->rollingHash($value);
            $hashesData[] = $tmpHashData;
        }

        // ==============================================================================================================================================================
        // hash couples of the both user input and the existing data
        $fingerprints = [];
        foreach ($hashesData as $key => $value) {
            $fingerprints[] = $this->fingerprints($value, $hashesInsert);
        }

        // similarity between all of them based on the fingerprints and the existing hashes
        $similarities = [];
        foreach ($fingerprints as $key => $value) {
            $similarities[] = $this->diceSimilarity($value, $hashesData[$key], $hashesInsert);
        }
        return $similarities;
    }
}
