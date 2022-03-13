<?php

namespace App\Http\Controllers;

use App\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    function rabinKarp($n, $insert)
    {
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');

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
        // dd([$similarities, $data, $base[2]]);
        return response()->json([
            'status' => 'success',
            'data' => $this->itemDetails([$similarities, $data, $base[2]])
        ], 200);
        // return $this->itemDetails([$similarities, $data, $base[2]]);
    }

    function getter()
    {
        // $get = DB::table('tbl_barang AS a')
        // ->select('a.*','b.*','c.*','d.*',DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
        // DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum'),'e.harga','e.stok');
        // if (Auth::user() != null) {
        //   $get = $get->where('d.id_user',Auth::user()->id);
        // }
        // if (!empty(Auth::user()->id_group)) {
        //   $get = $get->where('c.id_group',Auth::user()->id_group);
        // }
        // if (empty($all_data)) {
        //   $get = $get->whereNotNull('b.unit_masuk');
        // }
        // $get = $get->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
        // ->join('harga_produk_group AS c','c.id_product','a.barang_id')
        // ->leftJoin('harga_produk_user AS d','d.id_product','a.barang_id')
        // ->leftJoin('user_setting AS e','d.id_user','e.user_id')
        // ->groupBy('a.barang_id');
        // $count = $get->get();
        // return $count;
        
        $produk = [];
        $get = DB::table('tbl_log_stok AS a')
        ->select('a.id_barang AS id',
        DB::raw('SUM(a.unit_masuk) AS unit_masuk'),
        DB::raw('SUM(a.unit_keluar) AS unit_keluar'),
        'tb.barang_alias')
        ->leftJoin('tbl_barang as tb', 'tb.barang_id', 'a.id_barang')
        ->groupBy('a.id_barang')
        ->get();
        
        $tmpId = [];
        foreach ($get as $key => $value) {
          if (intval($value->unit_masuk) > intval($value->unit_keluar)) {
            $tmpId[] = $value->id;
          }
        }
        
        $barang = DB::table('tbl_barang')->whereIn('barang_id',$tmpId)->distinct()->get()->toArray();
        $id = array_column(DB::table('tbl_barang')->whereIn('barang_id',$tmpId)->distinct()->get(['barang_id'])->toArray(), 'barang_id');
        return [$get, $barang, $id];
    }

    function itemDetails($barang)
    {
        $data = [];
        $similarities = [];
        $similarities = array_filter($barang[0], function($value) {
            return $value != 0;  
        });
        $filteredItem = array_intersect_key($barang[1], $similarities);
        $barang_id = array_intersect_key($barang[2], $similarities);
        // dd($barang_id, $filteredItem, $similarities);
        // $all_data = $request["all_data"];
        // $draw = $request["draw"];
        // $search = $request['search']['value'];
        // $limit = is_null($request["length"]) ? 10 : $request["length"];
        // $offset = is_null($request["start"]) ? 0 : $request["start"];
        $get = DB::table('tbl_barang AS a')
        ->select('a.*','b.*','c.*','d.*',DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
        DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum'),'e.harga','e.stok')
        ->whereIn('a.barang_id', $barang_id);
        if (Auth::user() != null) {
          $get = $get->where('d.id_user',Auth::user()->id);
        }
        // if (!empty($search)) {
        //   $get = $get->where('a.barang_nama','like','%'.$search.'%');
        // }
        if (!empty(Auth::user()->id_group)) {
          $get = $get->where('c.id_group',Auth::user()->id_group);
        }
        if (empty($all_data)) {
          $get = $get->whereNotNull('b.unit_masuk');
        }
        $get = $get->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
        ->join('harga_produk_group AS c','c.id_product','a.barang_id')
        ->leftJoin('harga_produk_user AS d','d.id_product','a.barang_id')
        ->leftJoin('user_setting AS e','d.id_user','e.user_id')
        ->groupBy('a.barang_id');
        $count = $get->get();
        $get_count = count($count);
        // $get = $get->limit($limit)->offset($offset)->get();
        $get = $get->get();
        // dd($get);
        foreach ($get as $key => $value) {
          $stok = $value->unit_masuk_sum - $value->unit_keluar_sum;
          if(!empty(Auth::user()->id_group)){
            if (!empty($value->harga_user)) {
              $harga = 'Rp. '.number_format($value->harga_user, 2, ',', '.');
            }
          }
          else {
            $harga = 'Login untuk melihat harga';
          }
          if ($stok > 0) {
            $value->stok == 'on'?$stk_str = $stok.'  '.Satuan::where('satuan_id', $value->satuan_id)->get('satuan_nama')[0]->satuan_nama:$stk_str = null;
            $value->harga == 'on'?$harga = $harga:$harga = null;
            $data[] = array(
                      'id' => $value->barang_id,
                      'nama' => $value->barang_nama,
                      'deskripsi' => $value->barang_kode.' - '.$value->barang_alias,
                      'stok' => !empty(Auth::user())?$stk_str:null,
                      'harga' => !empty($harga)?$harga:null,
                      'btn' => ''
                    );
          }
        }
        $reIndexSimilaity = [];
        foreach ($similarities as $key => $value) {
            $reIndexSimilaity[] = $value;
        }
        // dd($reIndexSimilaity, $similarities);
        $newData = [];
        foreach ($data as $key => $value) {
            $tmpValue = $value;
            $tmpValue['similarity'] = $reIndexSimilaity[$key];
            $newData[] = $tmpValue;
        }
        $sorterCallback = function($a, $b) {
            return $a['similarity'] < $b['similarity'];
        };
        usort($newData, $sorterCallback);
        // $similaritySortdata = array_column($newData, 'similarity');
        return $newData;
        // dd($newData);
        // dd(array_multisort($reIndexSimilaity, SORT_DESC, $newData));
        // dd(collect($newData)->sortBy('similarity')->reverse()->toArray());
        // sort();
        // $numbers=array(4,6,2,22,11);
        // dd($data, $reIndexSimilaity);
    }
}
