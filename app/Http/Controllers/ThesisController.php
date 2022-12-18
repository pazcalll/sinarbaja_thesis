<?php

namespace App\Http\Controllers;

use App\Satuan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ThesisController extends Controller
{
    public $user;
    
    public function __construct()
    {
        $this->user = new User();
    }
    
    // ========================================================================================================
    // The main algorithm
	public function rabinKarp($n, $insert)
	{
		return response()->json([
            'status' => 'success',
            'data' => $this->user->rabinKarpFormula($n, $insert)
        ], 200);
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
        $base = $this->user->itemsGetter();
        $data = array_column($base[1], 'barang_nama');
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->user->caseFolding($value);
            $preData[] = ['base' => $value, 'result'=>$tmpPreData];
        }

        $stringsUser = [['base' => $req->post('string'), 'result' => strtolower($req->post('string'))]];
        $stringsData = $preData;
        return view('analytics.casefolding')->with('stringsUser', $stringsUser)->with('stringsData', $stringsData);
    }
    
    public function pre_punctuation(Request $req)
    {
        $base = $this->user->itemsGetter();
        $data = array_column($base[1], 'barang_nama');
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->user->punctuationRemoval($this->user->caseFolding($value));
            $preData[] = ['base' => $this->user->caseFolding($value), 'result'=>$tmpPreData];
        }

        $stringsUser = [['base' => strtolower($req->post('string')), 'result' => $this->user->punctuationRemoval(strtolower($req->post('string')))]];
        $stringsData = $preData;
        return view('analytics.punctuation')->with('stringsUser', $stringsUser)->with('stringsData', $stringsData);
    }

    public function rabin_kgram(Request $req)
    {
        $base = $this->user->itemsGetter();
        $data = array_column($base[1], 'barang_nama');

        // preprocessing stage of the inserted data
        $preInsert = $this->user->punctuationRemoval($this->user->caseFolding($req->post('string')));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->user->kGram($preInsert, 4);

        // ==============================================================================================================================================================
        // preprocessing stage of the exxisting data
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->user->punctuationRemoval($this->user->caseFolding($value));
            $preData[] = $tmpPreData;
        }

        // processed data of the existing data
        $subsData = [];
        // foreach ($data as $key => $value) {
        foreach ($preData as $key => $value) {
            $tmpSubsData = $this->user->kGram($value,4);
            $subsData[] = ['base' => $value, 'result'=>implode(' | ', $tmpSubsData)];
        }

        $stringsUser = [['base' => $preInsert, 'result' => implode(' | ', $subsInsert)]];
        $stringsData = $subsData;
        // dd($stringsUser, $stringsData);
        return view('analytics.rabin-karp', compact('stringsUser', 'stringsData'));
    }

    public function rabin_hashing(Request $req)
    {
        $base = $this->user->itemsGetter();
        $data = array_column($base[1], 'barang_nama');

        // preprocessing stage of the inserted data
        $preInsert = $this->user->punctuationRemoval($this->user->caseFolding($req->post('string')));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->user->kGram($preInsert, 4);
        $hashesInsert = $this->user->rollingHash($subsInsert);

        // ==============================================================================================================================================================
        // preprocessing stage of the exxisting data
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->user->punctuationRemoval($this->user->caseFolding($value));
            $preData[] = $tmpPreData;
        }

        // processed data of the existing data
        $subsData = [];
        $subsDataVis = [];
        // foreach ($data as $key => $value) {
        foreach ($preData as $key => $value) {
            $tmpSubsData = $this->user->kGram($value, 4);
            $subsData[] = $tmpSubsData;
            $subsDataVis[] = ['base' => $value, 'result'=>implode(' | ', $tmpSubsData)];
        }
        $hashesData = [];
        $hashesDataVis = [];
        foreach ($subsData as $key => $value) {
            $tmpHashData = $this->user->rollingHash($value);
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
        $base = $this->user->itemsGetter();
        $data = array_column($base[1], 'barang_nama');

        // preprocessing stage of the inserted data
        $preInsert = $this->user->punctuationRemoval($this->user->caseFolding($req->string));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->user->kGram($preInsert, 4);
        $hashesInsert = $this->user->rollingHash($subsInsert);

        // ==============================================================================================================================================================
        // preprocessing stage of the exxisting data
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->user->punctuationRemoval($this->user->caseFolding($value));
            $preData[] = $tmpPreData;
        }

        // processed data of the existing data
        $subsData = [];
        // foreach ($data as $key => $value) {
        foreach ($preData as $key => $value) {
            $tmpSubsData = $this->user->kGram($value,4);
            $subsData[] = $tmpSubsData;
        }
        $hashesData = [];
        foreach ($subsData as $key => $value) {
            $tmpHashData = $this->user->rollingHash($value);
            $hashesData[] = $tmpHashData;
        }

        // ==============================================================================================================================================================
        // hash couples of the both user input and the existing data
        $fingerprints = [];
        $currentResult = function($value, $hashesInsert, $subsData, $subsInsert, $key) {
            if (implode('|', $this->user->fingerprintTester($value, $hashesInsert, $subsData[$key], $subsInsert)) == "") return "none";
            return implode('|', $this->user->fingerprintTester($value, $hashesInsert, $subsData[$key], $subsInsert));
        };
        foreach ($hashesData as $key => $value) {
            $fingerprints[] = ['base' => $data[$key].' = '.implode(' | ', $value), 'result' => $currentResult($value, $hashesInsert, $subsData, $subsInsert, $key)];
        }
        $stringsUser = [['base' => implode(' | ', $subsInsert), 'result' => implode(' | ', $hashesInsert)]];
        $stringsData = $fingerprints;
        return view('analytics.rabin-karp', compact('stringsUser', 'stringsData'));
    }

    public function similarity(Request $req)
    {
        $base = $this->user->itemsGetter();
        $data = array_column($base[1], 'barang_nama');
        $n = 4;
        $insert = $req->string;

        // preprocessing stage of the inserted data
        $preInsert = $this->user->punctuationRemoval($this->user->caseFolding($insert));

        // processed data of the inserted string by user
        // $subsInsert = kGram($insert, $n);
        $subsInsert = $this->user->kGram($preInsert, $n);
        $hashesInsert = $this->user->rollingHash($subsInsert);

        // ==============================================================================================================================================================
        // preprocessing stage of the exxisting data
        $preData = [];
        foreach ($data as $key => $value) {
            $tmpPreData = $this->user->punctuationRemoval($this->user->caseFolding($value));
            $preData[] = $tmpPreData;
        }

        // processed data of the existing data
        $subsData = [];
        // foreach ($data as $key => $value) {
        foreach ($preData as $key => $value) {
            $tmpSubsData = $this->user->kGram($value,$n);
            $subsData[] = $tmpSubsData;
        }
        $hashesData = [];
        foreach ($subsData as $key => $value) {
            $tmpHashData = $this->user->rollingHash($value);
            $hashesData[] = $tmpHashData;
        }

        // ==============================================================================================================================================================
        // hash couples of the both user input and the existing data
        $fingerprints = [];
        foreach ($hashesData as $key => $value) {
            $fingerprints[] = $this->user->fingerprintTester($value, $hashesInsert, $subsData[$key], $subsInsert);
        }

        // similarity between all of them based on the fingerprints and the existing hashes
        $similarities = [];
        foreach ($fingerprints as $key => $value) {
            $similarities[] = $this->user->diceSimilarity($value, $hashesData[$key], $hashesInsert);
            // dd($this->user->diceSimilarity($value, $hashesData[$key], $hashesInsert));
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

    public function speedPage(Request $request)
    {
        return view('analytics.test-speed')->with('string', $request->string);
    }

    public function speedRabin(Request $request)
    {
		return response()->json([
            'status' => 'success',
            'data' => $this->user->rabinKarpFormula(4, $request->string)
        ], 200);
    }
    
    public function speedSQL(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->user->findItemSQL($request)
        ]);
    }
}
