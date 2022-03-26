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
                $primesMultiplicator = pow(26, strlen($tmpSubstr)-($j+1));
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

        return response()->json([
            'status' => 'success',
            'data' => $this->itemDetails([$similarities, $data, $base[2]])
        ], 200);
    }

    function getter()
    {
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
        $produk = [$get, $barang, $tmpId];
        return $produk;
    }

    function itemDetails($barang)
    {
        $data = [];
        $similarities = [];
        $similarities = array_filter($barang[0], function($value) {
            return $value != 0;  
        });
        $barang_id = array_intersect_key($barang[2], $similarities);
        $get = DB::table('tbl_barang AS a')
        ->select('a.*','b.*','c.*','d.*',DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
        DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum'),'e.harga','e.stok')
        ->whereIn('a.barang_id', $barang_id);
        if (Auth::user() != null) {
          $get = $get->where('d.id_user',Auth::user()->id);
        }
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
        $get = $get->get();
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
                'kategori' => $value->barang_alias,
                'stok' => !empty(Auth::user())?$stk_str:null,
                'harga' => !empty($harga)?$harga:null,
            );
          }
        }
        $reIndexSimilaity = [];
        foreach ($similarities as $key => $value) {
            $reIndexSimilaity[] = $value;
        }
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
        return $newData;
    }


    // ================================================================================================================================================================
    // ANALYTICS PURPOSES
    // ================================================================================================================================================================

    
    public function analytics($string)
    {
        // dd($string);
        return view('analytics/index', ['string' => $string]);
    }

    public function preprocessing($string)
    {
        return view('analytics/preprocessing', ['string' => $string]);
    }

    public function table(Request $req)
    {
        dd($req->all());
        return view('analytics.table');
    }

    public function pre_casefolding(Request $req)
    {
        // dd($req->all());
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->caseFolding($value);
            $preData[] = ['base' => $value, 'result'=>$tmpPreData];
        }

        $stringsUser = [['base' => $req->post('string'), 'result' => strtolower($req->post('string'))]];
        $stringsData = $preData;
        return view('analytics.casefolding')->with('stringsUser', $stringsUser)->with('stringsData', $stringsData);
    }
    
    public function pre_punctuation(Request $req)
    {
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->punctuationRemoval($this->caseFolding($value));
            $preData[] = ['base' => $this->caseFolding($value), 'result'=>$tmpPreData];
        }

        $stringsUser = [['base' => strtolower($req->post('string')), 'result' => $this->punctuationRemoval(strtolower($req->post('string')))]];
        $stringsData = $preData;
        return view('analytics.punctuation')->with('stringsUser', $stringsUser)->with('stringsData', $stringsData);
    }

    public function rabin_kgram(Request $req)
    {
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');

        // preprocessing stage of the inserted data
        $preInsert = $this->punctuationRemoval($this->caseFolding($req->post('string')));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->kGram($preInsert, 4);

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
            $tmpSubsData = $this->kGram($value,4);
            $subsData[] = ['base' => $value, 'result'=>implode(' | ', $tmpSubsData)];
        }

        $stringsUser = [['base' => $preInsert, 'result' => implode(' | ', $subsInsert)]];
        $stringsData = $subsData;
        // dd($stringsUser, $stringsData);
        return view('analytics.rabin-karp', compact('stringsUser', 'stringsData'));
    }

    public function rabin_hashing(Request $req)
    {
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');

        // preprocessing stage of the inserted data
        $preInsert = $this->punctuationRemoval($this->caseFolding($req->post('string')));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->kGram($preInsert, 4);
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
        $subsDataVis = [];
        // foreach ($data as $key => $value) {
        foreach ($preData as $key => $value) {
            $tmpSubsData = $this->kGram($value, 4);
            $subsData[] = $tmpSubsData;
            $subsDataVis[] = ['base' => $value, 'result'=>implode(' | ', $tmpSubsData)];
        }
        $hashesData = [];
        $hashesDataVis = [];
        foreach ($subsData as $key => $value) {
            $tmpHashData = $this->rollingHash($value);
            $hashesData[] = $tmpHashData;
            $hashesDataVis[] = ['base' => implode(' | ', $value), 'result'=>implode('|', $tmpHashData)];
        }

        $stringsUser = [['base' => implode(' | ', $subsInsert), 'result' => implode(' | ', $hashesInsert)]];
        $stringsData = $hashesDataVis;
        // dd($stringsUser, $stringsData);
        return view('analytics.rabin-karp', compact('stringsUser', 'stringsData'));
    }

    public function rabin_intersect(Request $req)
    {
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');

        // preprocessing stage of the inserted data
        $preInsert = $this->punctuationRemoval($this->caseFolding($req->string));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->kGram($preInsert, 4);
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
            $tmpSubsData = $this->kGram($value,4);
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
            // dd($value);
            $currentResult = function($value, $hashesInsert) {
                if (implode('|', $this->fingerprints($value, $hashesInsert)) == "") return "none";
                return implode('|', $this->fingerprints($value, $hashesInsert));
            };
            // dd($currentResult);
            $fingerprints[] = ['base' => $data[$key].' = '.implode(' | ', $value), 'result' => $currentResult($value, $hashesInsert)];
        }
        $stringsUser = [['base' => implode(' | ', $subsInsert), 'result' => implode(' | ', $hashesInsert)]];
        $stringsData = $fingerprints;
        return view('analytics.rabin-karp', compact('stringsUser', 'stringsData'));
    }

    public function similarity(Request $req)
    {
        $base = $this->getter();
        $data = array_column($base[1], 'barang_nama');
        $n = 4;
        $insert = $req->string;

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
            // dd($this->diceSimilarity($value, $hashesData[$key], $hashesInsert));
        }
        $similaritiesAnalytics = [];
        foreach ($data as $key => $value) {
            $similaritiesAnalytics[] = ['base' => $value, 'result'=>$similarities[$key].'%'];
        }
        // dd($similaritiesAnalytics);
        $stringsUser = [['base' => $req->string, 'result' => implode(' | ', $hashesInsert)]];
        $stringsData = $similaritiesAnalytics;
        return view('analytics.rabin-karp', compact('stringsUser', 'stringsData'));
        // dd($req->all());
    }
}
