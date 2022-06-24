<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Profil;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'name', 'email', 'address', 'no_handphone', 'password', 'group_id', 'id_group'
        'id','name', 'email', 'address', 'no_handphone', 'password', 'id_group'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function create($req)
    {
        $payload = $req->validate([
            'name' => ['required'],
            'address' => ['required'],
            'email' => ['required', 'unique:users'],
            'no_handphone' => ['required', 'min:11', 'max:13'],
            'password' => ['nullable', 'min:8']
        ],
        [
            'email.unique' => 'Email telah digunakan!',
            'no_handphone.min' => 'Panjang no. telepon kurang',
            'no_handphone.max' => 'Panjang no. telepon berlebih',
            'password.min' => 'Password anda kurang dari 8 huruf',
        ]);
        $payload['id_group'] = 2;
        $payload['password'] = Hash::make($payload['password']);
        // dd($payload);
        DB::beginTransaction();
        $create = DB::table('users')->insertGetId($payload);
        $pricePerGroup = DB::table('harga_produk_group')->where('id_group', $payload['id_group'])->groupBy('id_group', 'id_product', 'harga_group')->get(['id_group', 'id_product', 'harga_group']);
        // $insertUserPrices = HargaProdukUser::create()
        $insertions = [];
        foreach ($pricePerGroup as $key => $value) {
            $insertions[$key]['id_user'] = $create;
            $insertions[$key]['id_group'] = $value->id_group;
            $insertions[$key]['id_product'] = $value->id_product;
            $insertions[$key]['harga_user'] = $value->harga_group;
        }
        $insert = DB::table('harga_produk_user')->insert($insertions);
        DB::commit();
        return $this->login($req);
    }
    
    public function login($req) {
        $payload = $req->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        if (Auth::attempt($payload)) {
            $req->session()->regenerate();
            $user = Auth::user();
            if(Auth::user()->id_group != 1) {
                return redirect(url('/'));
            }
            else if(Auth::user()->id_group == 1) {
                return redirect(url('/dashboard'));
            }
        }

        return back()->withErrors('Email atau password anda salah.');
    }

    public function logout ($req) {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();
        return redirect('/');
    }

    // =========================================== ALGORITHM PREREQUISITES ====================================
    public function itemsGetter()
    {
        $produk = [];
        $get = DB::table('tbl_log_stok AS a');
        $get = $get->select('a.id_barang AS id',
            DB::raw('SUM(a.unit_masuk) AS unit_masuk'),
            DB::raw('SUM(a.unit_keluar) AS unit_keluar'),
            'tb.barang_alias');
        $get = $get->leftJoin('tbl_barang as tb', 'tb.barang_id', 'a.id_barang')
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

    public function searchItemDetails($barang)
    {
        $data = [];
        $similarities = [];
        $similarities = array_filter($barang[0], function($value) {
            return $value != 0;  
        });
        $barang_id = array_intersect_key($barang[2], $similarities);
        $get = DB::table('tbl_barang AS a');
        if (Auth::user() != null) {
            $get = $get->select('a.barang_nama', 'a.barang_id', 'a.barang_alias', 'a.satuan_id', 'd.harga_user', 'e.satuan_nama',
                DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
                DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum')
            )
                ->whereIn('a.barang_id', $barang_id)
                ->where('d.id_user', Auth::user()->id)
                ->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
                ->leftJoin('harga_produk_user AS d','d.id_product','a.barang_id')
                ->leftJoin('tbl_satuan AS e','e.satuan_id','a.satuan_id');
            $get = $get->groupBy('a.barang_id')->get();
            $stok = 0;
            foreach ($get as $key => $value) {
                $stok = $value->unit_masuk_sum - $value->unit_keluar_sum;
                if ($stok > 0) {
                    $harga = 'Rp. '.number_format($value->harga_user, 2, ',', '.');
                    $stk_str = $stok.'  '.$value->satuan_nama;
                    $data[] = array(
                        'id' => $value->barang_id,
                        'nama' => $value->barang_nama,
                        'kategori' => $value->barang_alias,
                        'stok' => $stk_str,
                        'harga' => $harga,
                    );
                }
            }
        }
        else{
            $get = $get->select('a.barang_nama', 'a.barang_id', 'a.barang_alias', 'a.satuan_id', 'e.satuan_nama',
                DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
                DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum')
            )
                ->whereIn('a.barang_id', $barang_id)
                ->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
                ->leftJoin('tbl_satuan AS e','e.satuan_id','a.satuan_id');
            $get = $get->groupBy('a.barang_id')->get();
            $stok = 0;
            foreach ($get as $key => $value) {
                $stok = $value->unit_masuk_sum - $value->unit_keluar_sum;
                if ($stok > 0) {
                    $harga = 'Login untuk melihat harga';
                    $stk_str = $stok.'  '.$value->satuan_nama;
                    $data[] = array(
                        'id' => $value->barang_id,
                        'nama' => $value->barang_nama,
                        'kategori' => $value->barang_alias,
                        'stok' => $stk_str,
                        'harga' => $harga,
                    );
                }
            }
        }
        
        $reIndexSimilaity = [];
        foreach ($similarities as $key => $value) {
            $reIndexSimilaity[] = $value;
        }
        $newData = [];
        foreach ($data as $key => $value) {
            if ($value['harga'] != "Rp. 0,00") {
                $tmpValue = $value;
                $tmpValue['similarity'] = $reIndexSimilaity[$key];
                $newData[] = $tmpValue;
            }
        }
        $sorterCallback = function($a, $b) {
            return $a['similarity'] < $b['similarity'];
        };
        
        usort($newData, $sorterCallback);
        return $newData;
    }
    // ======================================================================================================

    // =================================== SET OF RABIN KARP ALGORITHM =================================
    // preprocessing stage
    public function caseFolding($str)
    {
        return strtolower($str);
    }

    public function punctuationRemoval($str)
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

    public function kGram($str, $n)
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

    public function rollingHash($subStrings)
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

    public function fingerprints($hashes1, $hashes2)
    {
        // the fingerprint looks for the hash couples or the intersection of hash between the both existing hash collections
        return array_intersect($hashes1, $hashes2);
    }

    public function fingerprintTester($hashes1, $hashes2, $subsData, $subsInsert)
    {
        try {
            // the fingerprint looks for the hash couples or the intersection of hash between the both existing hash collections
            $tmpIntersect = array_intersect($hashes2, $hashes1);
            // in order to get the correct hash value, there will be a public function to check its real substring
            // if the both substrings are also match, the hash couple will be counted
            // dd($tmpIntersect);
            $toReturn = [];
            $checkpoint = 0;
            foreach ($tmpIntersect as $key => $value) {
                // if ($hashes1 == $hashes2) {
                    
                //     return array_intersect($hashes1, $hashes2);
                // }
                if ($value == $hashes2[$key]) {
                    for ($i=$checkpoint; $i < count($subsData); $i++) { 
                        if ($subsInsert[$key] == $subsData[$i]) {
                            $toReturn[$key] = $value;
                            $checkpoint = $key;
                        }
                    }
                }
            }
            return $toReturn;
            // dd($key, $subsData[$key], $subsInsert[$key]);
        } catch (\Throwable $th) {
            return $th;
        }
    }
    
    public function diceSimilarity($fingerprint, $hashesData, $hashesInsert)
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

    public function rabinKarpFormula($n, $insert)
    {
        $base = $this->itemsGetter();
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
            $fingerprints[] = $this->fingerprintTester($value, $hashesInsert, $subsData[$key], $subsInsert);
        }

        // similarity between all of them based on the fingerprints and the existing hashes
        $similarities = [];
        foreach ($fingerprints as $key => $value) {
            $similarities[] = $this->diceSimilarity($value, $hashesData[$key], $hashesInsert);
        }
		return $this->searchItemDetails([$similarities, $data, $base[2]]);
    }

    public function findItemSQL($request)
    {
        $data = [];
        $get = DB::table('tbl_barang AS a');
        if (Auth::user() != null) {
            $get = $get->select('a.barang_nama', 'a.barang_id', 'a.barang_alias', 'a.satuan_id', 'd.harga_user', 'e.satuan_nama',
                DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
                DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum')
            )
                ->where('a.barang_nama', 'like', '%'.$request->string.'%')
                ->where('d.id_user',Auth::user()->id)
                ->whereNotNull('b.unit_masuk')
                ->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
                ->leftJoin('harga_produk_user AS d','d.id_product','a.barang_id')
                ->leftJoin('tbl_satuan AS e','e.satuan_id','a.satuan_id')
                ->groupBy('a.barang_id')
                ->get();
            foreach ($get as $key => $value) {
                $stok = $value->unit_masuk_sum - $value->unit_keluar_sum;
                if ($stok > 0) {
                    $harga = 'Rp. '.number_format($value->harga_user, 2, ',', '.');
                    $stk_str = $stok.'  '.$value->satuan_nama;
                    $harga = $harga;
                    $data[] = array(
                        'id' => $value->barang_id,
                        'nama' => $value->barang_nama,
                        'kategori' => $value->barang_alias,
                        'stok' => $stk_str,
                        'harga' => $harga
                    );
                }
            }
        }
        else{
            $get = $get->select('a.barang_nama', 'a.barang_id', 'a.barang_alias', 'a.satuan_id', 'e.satuan_nama',
                DB::raw('SUM(b.unit_masuk) AS unit_masuk_sum'),
                DB::raw('SUM(b.unit_keluar) AS unit_keluar_sum')
            )
                ->where('a.barang_nama', 'like', '%'.$request->string.'%')
                ->whereNotNull('b.unit_masuk')
                ->leftJoin('tbl_log_stok AS b','a.barang_id','b.id_barang')
                ->leftJoin('tbl_satuan AS e','e.satuan_id','a.satuan_id')
                ->groupBy('a.barang_id')
                ->get();
            foreach ($get as $key => $value) {
                $stok = $value->unit_masuk_sum - $value->unit_keluar_sum;
                if ($stok > 0) {
                    $harga = 'Login untuk melihat harga';
                    $stk_str = $stok.'  '.$value->satuan_nama;
                    $harga = $harga;
                    $data[] = array(
                        'id' => $value->barang_id,
                        'nama' => $value->barang_nama,
                        'kategori' => $value->barang_alias,
                        'stok' => $stk_str,
                        'harga' => $harga
                    );
                }
            }
        }
		return $data;
    }





    // function window($hashes, $w)
    // {
    //     // to save temporary window values of the hash collection
    //     $window = [];
    //     for ($i=0; $i <= count($hashes)-$w; $i++) { 
    //         // to save each array of a window
    //         $tmpSubWindow = [];
    //         for ($j=0; $j < $w; $j++) {
    //             $tmpSubWindow[] = $hashes[$j+$i];
    //         }
    //         $window[] = $tmpSubWindow;
    //     }
    //     return $window;
    // }

    // function windowString($window)
    // {
    //     // to save array values of a window in a shape of a string with "|" as separator
    //     $windowString = [];
    //     foreach ($window as $key => $array) {
    //         // it contains value of a window in order to be pushed into the $windowString variable
    //         $tmpWindowString = "";
    //         foreach ($array as $key2 => $value) {
    //             if ($key2 == count($array)) {
    //                 $tmpWindowString .= $value;
    //             }else{
    //                 $tmpWindowString .= $value."|";
    //             }
    //         }
    //         $windowString[] = $tmpWindowString;
    //         // print_r($array);
    //     }
    //     return $windowString;
    // }

    // function windowFingerprint($windowInsert, $windowData)
    // {
    //     // takes only matching windows from the both arrays
    //     $windowFingerprint = array_intersect($windowInsert, $windowData);
    //     return $windowFingerprint;
    // }
}
