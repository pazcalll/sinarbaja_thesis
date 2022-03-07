<?php

namespace App\Http\Controllers;

use App\Tagihan;
use App\Profil;
use App\User;
use App\Gudang;
use App\Order;
use App\Payment;
use App\PurchaseOrder;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.kirim-tagihan');
    }

    public function bayar()
    {
        return view('dashboard.bayar-tagihan');
    }

    // public function upload(array $data) {
    public function upload(Request $request) {
        // nilai-nilai untuk kolom 'valid' di tabel Payment sebagai berikut:
        // 0 = memiliki arti bukti transfer ditolak
        // 1 = memiliki arti bukti transfer diterima sebagian
        // 2 = memiliki arti bukti transfer diterima semua
        // 9 = memiliki arti bukti transfer belum diproses admin

        try {
            //code...
            // dd($request->all());
            DB::beginTransaction();
            $validator = \Validator::make($request->all(), [
                // 'nominal_terkirim' => 'required',
                'jumlahBayarInput' => 'required|numeric|min:100',
                'inputBukti' => 'required|image'
            ],[
                // 'nominal_terkirim.required' => 'Jumlah yang dibayar tidak boleh kosong',
                'jumlahBayarInput.required' => 'Jumlah yang dibayar tidak boleh kosong',
                'jumlahBayarInput.min' => 'Pembayaran tidak boleh kurang dari 100 Rupiah',
                'inputBukti.required' => 'image required',
                'inputBukti.image' => 'file must be an image'
            ]);
            if ($validator->fails()) {
                return response(['message'=>$validator->errors()->toArray()], 400);
            }else{
                $filename =time().'_'.$request->file('inputBukti')->getClientOriginalName();
                $request->file('inputBukti')->storeAs('public/tagihan', $filename);
                $po_id = $request['po_id'];
                $tagihan_id = $request['id'];
                $nominal_bayar = $request['jumlahBayarInput'];

                // if($request['nominal_terkirim'] < $nominal_bayar) $nominal_bayar = $request['nominal_terkirim'];
                if($request['jumlahBayarInput'] < $nominal_bayar) $nominal_bayar = $request['jumlahBayarInput'];

                $data = [
                    'po_id' => $po_id,
                    'tagihan_id' => $tagihan_id,
                    'valid' => 9,
                    'nominal_bayar' => $nominal_bayar,
                    'bukti_tf' => 'public/tagihan/'.$filename
                ];
               DB::commit();
                return response([
                    'message' => 'Record created',
                    'status' => 'success',
                    'data' => Payment::create($data),
                    'tagihan_awal' => Tagihan::where('id', $tagihan_id)->get()
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response($th, 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // dd($request->all());
      DB::beginTransaction();
      try {
        $orderIds=[];
        $orderPotongan=[];
        $qty_barang=[];
        $id_order_get = explode(",",$request->po_id);
        $id_order = array_filter($id_order_get);
        $gudang_masuk = !empty($request->gudang)?$request->gudang:[];
        if (count($id_order) > count($gudang_masuk)) {
          return response([
              'message' => 'Pilih Gudang',
              'status' => 'errors',
          ], 500);
        }
        $ref_gudang=[];
        $qty = explode(",",$request->qty);
        $order = DB::table('orders')->wherein('status', ['BELUM DISETUJUI'])->whereIn('id', $id_order)->get();
        foreach ($order as $key => $value) {
          $totalOrderApprove = DB::table('orders')
          ->where('po_id', $value->po_id)
          ->where('status', 'AWAL PESAN')->get();
        }
        $total_harga_potongan = $request->total_harga_potongan;
        $id_user = $request->id_user;
        foreach ($request->gudang as $key => $value) {
          foreach ($value as $key => $value_temp) {
            array_push($ref_gudang,explode(',',$value_temp));
            // dd($ref_gudang);
          }
        }
        $data = [
          'po_id' => $order[0]->po_id,
          'nominal_total' => $total_harga_potongan,
          'no_tagihan' => 'TAG-'.base64_encode(base64_encode(base64_encode(Auth::user()->id))).date('YmdHis'),
          'id_gudang' => json_encode($ref_gudang),
          'metode_bayar' => $request->jenis_pembayaran
        ];
        $gudang = $data['id_gudang'];
        $data_tagihan = Tagihan::create($data);
        // $profil_user = User::where('id', $id_user)->update([
        //     'id_profil' => 1
        // ]);
        $data_po = PurchaseOrder::where('no_nota', $request->nota)
        ->update(['potongan_po_rp' => $request->potongan_nota]);
        foreach ($id_order as $key => $value) {
          $id_orders[] = $value-1;
        }
        for ($i=0; $i < count($request->harga_awal); $i++) {
          $val_total_harga_qty[] = $request->harga_awal[$i] * $qty[$i];
          $val_harga_potongan[] = $request->harga_awal[$i] - $request->potongan_harga[$i];
        }
        for ($i=0; $i < count($order); $i++) {
          $id_barang_arr[] = $order[$i]->product_id;
        }
        for ($i=0; $i < count($request->potongan_harga); $i++) {
          $potongan_arr[] = empty($request->potongan_harga[$i])?0:intVal($request->potongan_harga[$i]);
        }
        $arr['tabel_jumlah'] = $qty;
        $arr['tabel_harga'] = $request->harga_awal;
        $arr['tabel_id'] = $id_order;
        $arr['tabel_total'] = $val_total_harga_qty;
        $arr['tabel_idbarang'] = $id_barang_arr;
        $arr['tabel_gudang'] = $ref_gudang;
        $arr['tabel_potongan'] = $potongan_arr;
        $arr['tabel_subtotal'] = $val_harga_potongan;
        $arr["td_subtotal"] = array_sum($val_harga_potongan);
        $arr["td_potongan"] = empty($request->potongan_nota)?0:$request->potongan_nota;
        $arr["td_total"] = $request->total_harga_potongan;
        // dd($arr);
        try {
            $api_insert_kasir = $this->api_tagihan(json_encode($ref_gudang),$order[0]->po_id,$arr,$id_user,$gudang);
        } catch (\Throwable $th) {
          // return Response::error('Gagal load database');
          dd('$th');
          // return Response($th);
          DB::rollback();
        }

        $coba = [];
        for($i=0;$i<count($id_order);$i++ ){
            array_push($coba, Order::wherein('status', ['BELUM DISETUJUI'])
                ->where('po_id', $order[$i]->po_id)
                ->where('id', $id_order[$i])
                ->update([
                    'status' => 'DISETUJUI SEMUA',
                    'tagihan_id' => $data_tagihan->id,
                    'potongan_order_rp' => $potongan_arr[$i]
                ]));
        }
        DB::commit();
      } catch (\Exception $e) {
        dd($e);
        // return Response::error('Gagal load database');
        DB::rollback();
      }


    }

    private function api_tagihan($id_gudang_bc,$id,$data_kasir,$id_user,$gudang){
        $purchaseOrder = PurchaseOrder::where('id',$id);
        $tabel_id=[];

        if ($purchaseOrder->count() > 0) {
            $data_api = [
                'nomor' => 'TAG-'.base64_encode(base64_encode(base64_encode(Auth::user()->id))).date('YmdHis'),
                'tanggal_tempo' => date('Y-m-d',strtotime($purchaseOrder->first()->jatuh_tempo)),
                'tanggal' => date('Y-m-d',strtotime($purchaseOrder->first()->created_at)),
                'tanggal_faktur' => date('Y-m-d',strtotime($purchaseOrder->first()->created_at)),
            ];

            $data_api = array_merge($data_api, $data_kasir);
            // $response = Http::post('http://localhost/sim_besi/api/simpan_kasir', $data_api)->json();
            // $response = Http::post('http://192.168.18.19/sim_besi/api/simpan_kasir', $data_api)->json();
            try {
                $id_kasir               = '';
                $data['tanggal']        = $data_api['tanggal'];
                $data['tanggal_tempo']  = $data_api['tanggal_tempo'];
                $data['tanggal_faktur'] = $data_api['tanggal_faktur'];
                $data['no_faktur']      = $data_api['nomor'];
                $data['id_pelanggan']   = $id_user;
                // $data['id_pelanggan'] = $pelanggan;
                $data['uang_muka']      = '0';
                $data['ongkos_kirim']   = '0';
                $data['carabayar']      = '3';
                $data['metodebayar']    = '7';
                $data['total_potongan'] = $data_api['td_potongan'];
                $data['total_subtotal']  = $data_api['td_subtotal'];
                $data['total_tagihan']  = $data_api['td_total'];
                $data['status']         = '1';
                $data['status_penjualan']   = '1';
                $data['jenis_transaksi']= '1';
                // dd(str_replace("\"","",explode(',', $gudang))[1]);
                $id_gudang = DB::table("ref_gudang")->where('nama', str_replace("\"","",explode(',', $gudang))[1])->first('id');
                $data['id_bc_gudang']      = $id_gudang_bc;
                // dd($id_gudang->id);
                // dd($data);
                // $tbl_kasir_insert = DB::table('tbl_kasir')->insert($data);
                // $data['metodebayar2']   = $data_api['viabayar2'];
                // $data['total_metodebayar'] = $data_api['total_viabayar'];
                // $data['total_metodebayar2']= $data_api['total_viabayar2'];
                $tabel_id       = ($data_api['tabel_id']!=null) ? $data_api['tabel_id']:[];
                // $tabel_produk   = $data_api['tabel_idproduk'];
                $tabel_barang   = $data_api['tabel_idbarang'];
                $tabel_jumlah   = $data_api['tabel_jumlah'];
                $tabel_harga    = $data_api['tabel_harga'];
                $tabel_subtotal    = $data_api['tabel_subtotal'];
                $tabel_satuan   = '9';
                $tabel_satuan2  = '9';
                $tabel_gudang   = $id_gudang_bc;
                $tabel_potongan = $data_api['tabel_potongan'];
                $tabel_total    = $data_api['tabel_total'];

                // $tabel_idlog    = $data_api['tabel_idlog'];
                // $tabel_status   = $data_api['tabel_status'];
                // $tabel_status_redeem = $data_api->get('tabel_statusredeem');
                // $tabel_poin     = $data_api->get('tabel_poin');
                DB::beginTransaction();
                try{
                    $id = DB::table('tbl_kasir')->insertGetId($data);
                    for($i=0;$i<count($tabel_id);$i++){
                        $input['id_barang']     = $tabel_barang[$i];
                        $input['unit_masuk']    = "0";
                        $input['unit_keluar']   = $tabel_jumlah[$i];
                        $input['id_ref_gudang'] = $tabel_gudang;
                        $input['id_satuan']     = DB::table('tbl_barang')->where('barang_id', $input['id_barang'])->first('satuan_id')->satuan_id;
                        $input['tanggal']       = $data_api['tanggal'];
                        $input['status']        = 'J1';
                        $id_log_stok = DB::table('tbl_log_stok')->insertGetId($input);

                        $barang['id_kasir']               = $id;
                        $barang['id_detail_kasir_produk'] = '0';
                        $barang['id_barang']              = $tabel_barang[$i];
                        $barang['jumlah']                 = $tabel_jumlah[$i];
                        $barang['id_satuan']              = DB::table('tbl_barang')->where('barang_id', $input['id_barang'])->first('satuan_id')->satuan_id;
                        $barang['harga']                  = $tabel_harga[$i];
                        $barang['subtotal']               = $tabel_subtotal[$i];
                        $barang['potongan']               = $tabel_potongan[$i];
                        $barang['total']                  = $tabel_total[$i];
                        $barang['id_log_stok']            = $id_log_stok;
                        DB::table('tbl_kasir_detail')->insert($barang);

                    }
                    DB::commit();
                    // $message = 'Berhasil Simpan Data';
                    // return Response($message);

                }catch(\Throwable $e){
                  dd($e);
                    DB::rollback();
                    // return Response::error('Gagal Simpan Data');
                    return response($e->getMessage());
                }

            } catch (Exception $e) {
              dd($e);
                // return Response::error('Gagal load database');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function show(Tagihan $tagihan)
    {
        return view('dashboard.lihat-tagihan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function edit(Tagihan $tagihan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tagihan $tagihan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tagihan  $tagihan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tagihan $tagihan)
    {
        //
    }

    public function cetak_tagihan(Request $request)
    {
        $profil = User::where('id', 1)->get('id_profil');
        $data['data']= Profil::where('id', $profil[0]->id_profil)->get();
        $no_nota = (isset($request->no_nota)) ? $request->no_nota : null;
        $id_tagihan = (isset($request->id_tagihan)) ? $request->id_tagihan : null;
        $send['PurchaseOrder'] = Tagihan::with(['po.user.profil', 'orders'])
            ->whereHas('po', function ($db) use ($no_nota) {
                return $db->where('no_nota', $no_nota);
            })
            ->where('id', $id_tagihan)
            ->get()->first();
        // return $send;
        // dd($send['PurchaseOrder']);
        $nama_tagihan = $send['PurchaseOrder']->po->user->name;
        $mpdf = new \Mpdf\Mpdf([
            //'tempDir'             =>  _DIR_ . '/tmp',
            'format'              => 'A4-P',
            'mode'                => 'utf-8',
            'setAutoTopMargin'    => 'stretch',
            'defaultheaderline'   => 0,
            'defaultfooterline'   => 0
        ]);
        $mpdf->SetMargins(0, 0, 12);

        $html = view('dashboard.pdf_tagihan', $send, $data);
        $header = '
        <table width="100%">
        <tr>
        <td width="60%" style="color:#000D00;font-size: 20pt;font-weight: bold; ">TAGIHAN</td>
        <td width="40%" style="text-align: right;">
            <table width="100%">
                <tr>
                    <td width="40%" style="color:rgba(58,54,68,1);font-size: 8pt; text-align: left;">
                    No. Nota
                    <td width="60%" style="text-align: right;">
                    <span style="font-size: 8pt;">' . $send['PurchaseOrder']->po->no_nota . '</span>
                    </td>
                </tr>
                <tr>
                    <td width="40%" style="color:rgba(58,54,68,1);font-size: 8pt; text-align: left;">
                    Tanggal
                    <td width="60%" style="text-align: right;">
                    <span style="font-size: 8pt;">' . Helper::tgl_full($send['PurchaseOrder']->po->created_at, 98) . '</span>
                    </td>
                </tr>
                <tr>
                    <td width="40%" style="color:rgba(58,54,68,1);font-size: 8pt; text-align: left;">
                    Jatuh Tempo
                    <td width="60%" style="text-align: right;">
                    <span style="font-size: 8pt;">' . Helper::tgl_full($send['PurchaseOrder']->po->jatuh_tempo, 98) . '</span>
                    </td>
                </tr>

            </table>
        </td>
        </tr>
        </table>
        ';


        $mpdf->SetHeader($header);
        $footer = '
        <div style="text-align: left;">' . Helper::tgl_full(now(), 78) . '</div>
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
        Page {PAGENO} of {nb}
        ';
        $mpdf->setFooter($footer);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Surat Tagihan" . $send['PurchaseOrder']->no_nota);
        $mpdf->SetAuthor("Acme Trading Co.");
        $mpdf->SetWatermarkText("Tagihan");
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHTML($html);

        $mpdf->Output("Tagihan Kepada ".$nama_tagihan.".pdf", 'I');
    }

    public function cetak_surat_jalan(Request $request)
    {
        $profil = User::where('id', 1)->get('id_profil');
        $data['data']= Profil::where('id', $profil[0]->id_profil)->get();
        $no_nota = (isset($request->no_nota)) ? $request->no_nota : null;
        $id_tagihan = (isset($request->id_tagihan)) ? $request->id_tagihan : null;
        $send['PurchaseOrder'] = Tagihan::with(['po.user.profil', 'orders', 'trackings', 'orders.tagihan.gudang'])
            ->whereHas('po', function ($db) use ($no_nota) {
                return $db->where('no_nota', $no_nota);
            })
            ->where('id', $id_tagihan)
            ->get()->first();
            // dd($send);
        $mpdf = new \Mpdf\Mpdf([

            'format'              => 'A4-P',
            'mode'                => 'utf-8',
            'setAutoTopMargin'    => 'stretch',
            'defaultheaderline'   => 0,
            'defaultfooterline'   => 0
        ]);
        $mpdf->SetMargins(0, 0, 12);

        $html = view('dashboard.pdf_surat_jalan', $send);
        // return $html;
        $header = '
            <table width="100%" style="font-family: sans;">
            <tr>
                <td width="40%" style="font-size: 12pt">
                    <span style="font-weight: bold; font-size: 14pt;">' . $data['data'][0]->nama . '</span>
                    <br />' . $data['data'][0]->alamat . '<br />
                    <span style="font-family:dejavusanscondensed;">&#9742;</span>' . $data['data'][0]->telp . '
                </td>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="10%">&nbsp;</td>
                <td width="30%" style="font-size: 12pt;">
                    <span style="font-weight: bold; font-size: 14pt;">Kepada Yth.</span>
                    <br />' . $send['PurchaseOrder']->po->user->name . '<br />' . $send['PurchaseOrder']->po->user->address . '<br />
                    <span style="font-family:dejavusanscondensed;"></span>' . $send['PurchaseOrder']->po->user->no_handphone . '
                </td>
            </tr>
            </table><br />
            <h2 style="font-size: 14pt;font-weight: bold;text-decoration: underline;font-style: normal;text-align: center">SURAT JALAN</h2>
        ';


        $mpdf->SetHeader($header);
        $footer = '
        <div style="text-align: left;">' . Helper::tgl_full(now(), 78) . '</div>
        <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
        Page {PAGENO} of {nb}
        ';
        $mpdf->setFooter($footer);
        $mpdf->SetProtection(array('print'));
        $mpdf->SetTitle("Surat Jalan" . $send['PurchaseOrder']->po->no_nota);
        $mpdf->SetAuthor("Acme Trading Co.");
        $mpdf->SetWatermarkText("Surat Jalan");
        $mpdf->showWatermarkText = true;
        $mpdf->watermark_font = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.1;
        $mpdf->SetDisplayMode('fullpage');

        $mpdf->WriteHTML($html);
        // dd($mpdf);
        $mpdf->Output("Surat Jalan.pdf", "I");
    }

    function cetak_pesanan(Request $request) {

        $nama_dokumen = 'QrCode Pesanan';
        $no_nota  = (isset($request->no_nota)) ? $request->no_nota : null;
        $send['Data'] = PurchaseOrder::with(['user', 'orders.product'])
            ->where('no_nota', $no_nota)->get();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4-P',
            'model' => 'utf-8',
            'setAutoTopMargin' => 'stretch',
            'defaultheaderline'   => 0,
            'defaultfooterline'   => 0
        ]);

        $mpdf->SetMargins(0, 0, 12);

        $code = '<barcode code="Your message here" type="QR" class="barcode" size="0.8"/>';

        $html = view('dashboard.pdf_pesanan', $send);
        $header = '
        <table width="100%">
            <tr>
            <td width="60%" style="color:#000D00;font-size: 20pt;font-weight: bold; ">PESANAN</td>
            <td width="40%" style="text-align: right;">
                <table width="100%">
                    <tr>
                        <td width="40%" style="color:rgba(58,54,68,1);font-size: 8pt; text-align: left;">
                        No. Nota
                        <td width="60%" style="text-align: right;">
                        <span style="font-size: 8pt;">' . $send['Data'][0]->no_nota . '</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="40%" style="color:rgba(58,54,68,1);font-size: 8pt; text-align: left;">
                        Tanggal
                        <td width="60%" style="text-align: right;">
                        <span style="font-size: 8pt;">' . Helper::tgl_full($send['Data'][0]->created_at, 98) . '</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>';

    $mpdf->SetHeader($header);
    $footer = '
    <div style="text-align: left;">' . Helper::tgl_full(now(), 78) . '</div>
    <div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
        Page {PAGENO} of {nb}
    </div>';
    $mpdf->setFooter($footer);

    $mpdf->SetProtection(array('print'));
    $mpdf->SetTitle("Surat Pesanan" . $send['Data'][0]->no_nota);
    $mpdf->SetAuthor("Acme Trading Co.");
    $mpdf->SetWatermarkText("Pesanan");
    $mpdf->showWatermarkText= true;
    $mpdf->watermark_font= 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.1;
    // $mpdf->writeBarcode($nama_dokumen);
    $mpdf->WriteHTML($html);
    $mpdf->Output("".$nama_dokumen.".pdf", 'I');
    }
    public function approval() {
        return view('dashboard.approval-bayar');
    }

}
