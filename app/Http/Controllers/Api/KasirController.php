<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use File;

class KasirController extends Controller
{

    public function index()
    {
        try {
            $message = 'Berhasil Load Data';
            $data = DB::SELECT(DB::Raw("SELECT tb.barang_id as id_barang, tb.barang_kode AS kode_barang, 
                tb.barang_nama AS nama_barang, tb.satuan_id as id_satuan, ts.satuan_satuan AS satuan_barang 
                FROM tbl_barang AS tb LEFT JOIN tbl_satuan AS ts ON tb.satuan_id = ts.satuan_id"));
            return Response::success($message, $data);
        } catch (Exception $e) {
            return Response::error('Gagal load database');
        }
    }

    public function get_stok(Request $request)
    {
        $term = isset($request->q) ? trim($request->q):'';
        $search = strtolower($term);
        try {
            $message = 'Berhasil Load Data';
            $where[] = 'tb.barang_id_parent != 0';
            if($search != ''){
                $where[] = "(tb.barang_nama LIKE '%$search%' OR tb.barang_kode LIKE '%$search%')";
            }
            $add_where = "WHERE ".implode(' AND ', $where); 
            $data = DB::SELECT(DB::Raw("SELECT * FROM
                        (
                        SELECT
                            tls.*,
                            tb.barang_kode AS kode_barang,
                            tb.barang_nama AS nama_barang,
                            rg.nama AS nama_gudang,
                            SUM( tls.unit_masuk - tls.unit_keluar ) AS stok,
                            ts.satuan_nama AS nama_satuan  ,
                            SUM( (tls.unit_masuk * ts.konversi) - (tls.unit_keluar * ts.konversi) ) as konversi
                        FROM
                            tbl_log_stok AS tls
                            LEFT JOIN tbl_barang AS tb ON tls.id_barang = barang_id
                            LEFT JOIN ref_gudang AS rg ON tls.id_ref_gudang = rg.id
                            LEFT JOIN tbl_satuan AS ts ON tls.id_satuan = ts.satuan_id
                        GROUP BY
                            id_barang,
                            id_ref_gudang, id_satuan
                        ORDER BY
                            tb.barang_nama
                        ) b
                    WHERE
                        b.stok NOT LIKE '%-%' $add_where"));
            return Response::success($message, $data);
        } catch (Exception $e) {
            return Response::error('Gagal load database');
        }
    }

    public function simpan_kasir(Request $request){
        try {
            $id_kasir = $request->get('id_kasir');
            $data['tanggal'] = tgl_full($request->get('tanggal'),'99');
            $data['tanggal_tempo'] = tgl_full($request->get('tanggal_tempo'),'99');
            $data['tanggal_faktur'] = tgl_full($request->get('tanggal_faktur'),'99');
            $data['no_faktur'] = $request->get('nomor');
            /*$data['id_pelanggan'] = $request->get('id_pelanggan');*/
            $data['id_pelanggan'] = $pelanggan;
            $data['uang_muka']  = $request->get('td_uangmuka');
            $data['ongkos_kirim'] = $request->get('td_ongkir');
            $data['carabayar'] = $request->get('carabayar');
            $data['metodebayar'] = $request->get('viabayar');
            $data['total_potongan'] = $request->get('td_diskon');
            $data['total_tagihan']  = $request->get('td_total');
            $data['keterangan'] = $request->get('keterangan');
            $data['status'] = '1';
            $data['jenis_transaksi'] = '1';
            //$data['paper']  = $request->get('paper');
            $data['id_gudang'] = $request->get('gudang');
            $data['metodebayar2'] = $request->get('viabayar2');
            $data['total_metodebayar'] = $request->get('total_viabayar');
            $data['total_metodebayar2']= $request->get('total_viabayar2');

            $tabel_id = ($request->get('tabel_id')) ? $request->get('tabel_id'):[];
            $tabel_produk   = $request->get('tabel_idproduk');
            $tabel_barang   = $request->get('tabel_idbarang');
            $tabel_jumlah   = $request->get('tabel_jumlah');
            $tabel_harga    = $request->get('tabel_harga');
            $tabel_satuan   = '9';
            $tabel_satuan2  = $request->get('tabel_idsatuan');
            $tabel_total    = $request->get('tabel_total');
            $tabel_idlog    = $request->get('tabel_idlog');
            $tabel_status   = $request->get('tabel_status');
            $tabel_status_redeem = $request->get('tabel_statusredeem');
            $tabel_poin     = $request->get('tabel_poin');
           
            DB::beginTransaction();
            try{
                if($id_kasir == ''){
        $id = DB::table('tbl_kasir')->insertGetId($data);
        $produk_cetak = $id;
        $total_pembayaran = 0;
        $total_poin = 0;
        for($i=0;$i<count($tabel_id);$i++){
          if($tabel_status[$i] == '1'){
          $produk['id_kasir'] = $id;
          $produk['id_produk']    = $tabel_produk[$i];
          $produk['jumlah']       = $tabel_jumlah[$i];
          $produk['id_satuan']    = $tabel_satuan;
          $produk['harga']        = $tabel_harga[$i];
          $produk['total']        = $tabel_total[$i];
          $porduk['poin']         = $tabel_poin[$i];
          $produk['total_poin']   = $tabel_poin[$i]*$tabel_jumlah[$i];
          // $produk['status_redeem']= $tabel_status_redeem[$i];
          $id_kasir_detail_produk = DB::table('tbl_kasir_detail_produk')->insertGetId($produk);
          //$produk_cetak[] = $produk;

          $d_barang = DB::table('m_detail_produk as mdp')->join('tbl_barang as tb','mdp.id_barang','tb.barang_id')->where('mdp.id_produk',$tabel_produk[$i])->select(DB::raw('mdp.*,tb.satuan_id as id_satuan'));

          foreach($d_barang->get() as $d){
            $input['id_barang']     = $d->id_barang;
            $input['unit_masuk']    = "0";
            $input['unit_keluar']   = $d->jumlah*$tabel_jumlah[$i];
            $input['id_ref_gudang'] = $request->get('gudang');
            $input['id_satuan']     = $d->id_satuan;
            $input['tanggal']       = tgl_full($request->get('tanggal'),'99');
            $input['status']        = 'J1';
            $id_log_stok = DB::table('tbl_log_stok')->insertGetId($input);

            $barang['id_kasir']               = $id;
            $barang['id_detail_kasir_produk'] = $id_kasir_detail_produk;
            $barang['id_barang']              = $d->id_barang;
            $barang['jumlah']                 = $d->jumlah*$tabel_jumlah[$i];
            $barang['id_satuan']              = $d->id_satuan;
            $barang['harga']                  = '0';
            $barang['total']                  = '0';
            $barang['id_log_stok']            = $id_log_stok;
            // $barang['status_redeem']          = $tabel_status_redeem[$i];
            DB::table('tbl_kasir_detail')->insert($barang);
          }

          }else if($tabel_status[$i] == '2'){
            $input['id_barang']     = $tabel_barang[$i];
            $input['unit_masuk']    = "0";
            $input['unit_keluar']   = $tabel_jumlah[$i];
            $input['id_ref_gudang'] = $request->get('gudang');
            $input['id_satuan']     = $tabel_satuan2[$i];
            $input['tanggal']       = tgl_full($request->get('tanggal'),'99');
            $input['status']        = 'J1';
            $id_log_stok = DB::table('tbl_log_stok')->insertGetId($input);

            $barang['id_kasir']               = $id;
            $barang['id_detail_kasir_produk'] = '0';
            $barang['id_barang']              = $tabel_barang[$i];
            $barang['jumlah']                 = $tabel_jumlah[$i];
            $barang['id_satuan']              = $tabel_satuan2[$i];
            $barang['harga']                  = $tabel_harga[$i];
            $barang['total']                  = $tabel_total[$i];
            $barang['id_log_stok']            = $id_log_stok;
            // $barang['status_redeem']          = $tabel_status_redeem[$i];
            DB::table('tbl_kasir_detail')->insert($barang);
          }
          
          $total_pembayaran += $tabel_total[$i];
          $total_poin += $tabel_poin[$i]; 

        }

        $total_pembayaran = $total_pembayaran;
        $total_poin = $total_poin;
        $nominal = DB::table('m_nominal_poin')->limit(1)->first()->nominal;
        $cek_poin = floor($total_pembayaran / $nominal);
        // dd($total_poin);
        if($cek_poin > 0){
          $redeem['id_pelanggan'] = $pelanggan;
          $redeem['unit_masuk']   = $total_poin;
          $redeem['unit_keluar']  = 0;
          $redeem['tanggal']      = tgl_full($request->get('tanggal'),'99');
          $redeem['status']       = 1;
          $redeem['id_kasir']     = $id;
          DB::table('tbl_transaksi_poin')->insert($redeem);
        }

      }else{
        DB::table('tbl_kasir')->where('id_kasir',$id_kasir)->update($data);

        $id_del = array();
        $id_store = array();
        $id_del2 = array();
        $id_store2 = array();
        $id_del_barang = array();
        $id_store_barang = array();
        $id_del_log = array();
        $id_store_log = array();
        $d_produk = DB::table('tbl_kasir_detail_produk')->where('id_kasir',$id_kasir);
        $d_barang = DB::table('tbl_kasir_detail')->where('id_kasir',$id_kasir)->where('id_detail_kasir_produk','0');
        foreach($d_produk->get() as $key => $d){
          $id_store[] = $d->id_kasir_detail_produk;
          $id_store_produk[] = $d->id_produk;
          $id_storeproduk[$key]['id_kasir_detail_produk'] = $d->id_kasir_detail_produk;
          $id_storeproduk[$key]['id_produk'] = $d->id_produk;
        }

        foreach($d_barang->get() as $d){
          $id_store2[] = $d->id_detail_kasir;
        }        

        foreach($id_store as $d){
                if(count($tabel_id) > 0){
                    if(!in_array($d, $tabel_id)){
                        $id_del[] = $d;
                    }
                }else{
                    $id_del[] = $d;
                }
            }
        foreach($id_store2 as $d){
                if(count($tabel_id) > 0){
                    if(!in_array($d, $tabel_id)){
                        $id_del2[] = $d;
                    }
                }else{
                    $id_del2[] = $d;
                }
        }
        $produk_cetak = $id_kasir;
        $total_pembayaran = 0;
        $total_poin = 0;
        for($i=0;$i<count($tabel_id);$i++){
          $produk['id_kasir'] = $id_kasir;
          $produk['id_produk']    = $tabel_produk[$i];
          $produk['jumlah']       = $tabel_jumlah[$i];
          $produk['id_satuan']    = $tabel_satuan;
          $produk['harga']        = $tabel_harga[$i];
          $produk['total']        = $tabel_total[$i];
          $produk['poin']         = $tabel_poin[$i];
          $produk['total_poin']   = $tabel_poin[$i]*$tabel_jumlah[$i];
          // $produk['status_redeem']= $tabel_status_redeem[$i];
          //$produk_cetak[] = $produk;

          if($tabel_id[$i] == ''){
            if($tabel_status[$i] == '1'){

              $id_kasir_detail_produk = DB::table('tbl_kasir_detail_produk')->insertGetId($produk);
              $d_barang = DB::table('m_detail_produk as mdp')->join('tbl_barang as tb','mdp.id_barang','tb.barang_id')->where('id_produk',$tabel_produk[$i])->select(DB::raw('mdp.*,tb.satuan_id as id_satuan'));

              foreach($d_barang->get() as $d){
                $input['id_barang']     = $d->id_barang;
                $input['unit_masuk']    = "0";
                $input['unit_keluar']   = $d->jumlah*$tabel_jumlah[$i];
                $input['id_ref_gudang'] = $request->get('gudang');
                $input['id_satuan']     = $d->id_satuan;
                $input['tanggal']       = tgl_full($request->get('tanggal'),'99');
                $input['status']        = 'J1';
                $id_log_stok = DB::table('tbl_log_stok')->insertGetId($input);

                $barang['id_kasir']               = $id_kasir;
                $barang['id_detail_kasir_produk'] = $id_kasir_detail_produk;
                $barang['id_barang']              = $d->id_barang;
                $barang['jumlah']                 = $d->jumlah*$tabel_jumlah[$i];
                $barang['id_satuan']              = $d->id_satuan;
                $barang['harga']                  = '0';
                $barang['total']                  = '0';
                $barang['id_log_stok']            = $id_log_stok;
                // $barang['status_redeem']          = $tabel_status_redeem[$i];
                DB::table('tbl_kasir_detail')->insert($barang);
              }

            }else if($tabel_status[$i] == '2'){
              $input['id_barang']     = $tabel_barang[$i];
              $input['unit_masuk']    = "0";
              $input['unit_keluar']   = $tabel_jumlah[$i];
              $input['id_ref_gudang'] = $request->get('gudang');
              $input['id_satuan']     = $tabel_satuan2[$i];
              $input['tanggal']       = tgl_full($request->get('tanggal'),'99');
              $input['status']        = 'J1';
              $id_log_stok = DB::table('tbl_log_stok')->insertGetId($input);

              $barang['id_kasir']               = $id_kasir;
              $barang['id_detail_kasir_produk'] = '0';
              $barang['id_barang']              = $tabel_barang[$i];
              $barang['jumlah']                 = $tabel_jumlah[$i];
              $barang['id_satuan']              = $tabel_satuan2[$i];
              $barang['harga']                  = $tabel_harga[$i];
              $barang['total']                  = $tabel_total[$i];
              $barang['id_log_stok']            = $id_log_stok;
              // $barang['status_redeem']          = $tabel_status_redeem[$i];
              DB::table('tbl_kasir_detail')->insert($barang);
            }


          }else if($tabel_id[$i] != ''){
            if($tabel_status[$i] == '1'){
              DB::table('tbl_kasir_detail_produk')->where(array('id_kasir_detail_produk' => $tabel_id[$i]))->update($produk);
              $d_barang = DB::table('tbl_kasir_detail')->where('id_detail_kasir_produk',$tabel_id[$i]);
              
              foreach($d_barang->get() as $d){              
                $jumlah = DB::table('m_detail_produk')->where('id_produk',$tabel_produk[$i])
                        ->where('id_barang',$d->id_barang)->first()->jumlah;
                
                $barang['id_kasir']               = $id_kasir;
                $barang['id_detail_kasir_produk'] = $tabel_id[$i];
                $barang['id_barang']              = $d->id_barang;
                $barang['jumlah']                 = $jumlah*$tabel_jumlah[$i];
                $barang['id_satuan']              = $d->id_satuan;
                $barang['harga']                  = $d->harga;
                $barang['total']                  = $d->jumlah*$d->harga;
                $barang['id_log_stok']            = $d->id_log_stok;
                // $barang['status_redeem']          = $tabel_status_redeem[$i];
              DB::table('tbl_kasir_detail')->where(array('id_detail_kasir'=> $d->id_detail_kasir))->update($barang);

                $input['id_barang']     = $d->id_barang;
                $input['unit_masuk']    = "0";
                $input['unit_keluar']   = $jumlah*$tabel_jumlah[$i];
                $input['id_ref_gudang'] = $request->get('gudang');
                $input['id_satuan']     = $d->id_satuan;
                $input['tanggal']       = tgl_full($request->get('tanggal'),'99');
                $input['status']        = 'J1';
              DB::table('tbl_log_stok')->where('log_stok_id',$d->id_log_stok)->update($input);

              }
            }else if($tabel_status[$i] == '2'){
                $input['id_barang']     = $tabel_barang[$i];
                $input['unit_masuk']    = "0";
                $input['unit_keluar']   = $tabel_jumlah[$i];
                $input['id_ref_gudang'] = $request->get('gudang');
                $input['id_satuan']     = $tabel_satuan2[$i];
                $input['tanggal']       = tgl_full($request->get('tanggal'),'99');
                $input['status']        = 'J1';
                DB::table('tbl_log_stok')->where(array('log_stok_id' => $tabel_idlog[$i]))->update($input);

                $barang['id_kasir']               = $id_kasir;
                $barang['id_detail_kasir_produk'] = '0';
                $barang['id_barang']              = $tabel_barang[$i];
                $barang['jumlah']                 = $tabel_jumlah[$i];
                $barang['id_satuan']              = $tabel_satuan2[$i];
                $barang['harga']                  = $tabel_harga[$i];
                $barang['total']                  = $tabel_total[$i];
                $barang['id_log_stok']            = $tabel_idlog[$i];
                // $barang['status_redeem']          = $tabel_status_redeem[$i];
                DB::table('tbl_kasir_detail')->where(array('id_detail_kasir' => $tabel_id[$i]))->update($barang);
            }

          }

          $total_pembayaran += $tabel_total[$i];
          $total_poin += $tabel_poin[$i];
        }

        $total_pembayaran = $total_pembayaran;
        $total_poin = $total_poin;
        $nominal = DB::table('m_nominal_poin')->limit(1)->first()->nominal;
        $cek_poin = floor($total_pembayaran / $nominal);
        if($cek_poin > 0){
          $redeem['id_pelanggan'] = $pelanggan;
          $redeem['unit_masuk']   = $total_poin;
          $redeem['unit_keluar']  = 0;
          $redeem['tanggal']      = tgl_full($request->get('tanggal'),'99');
          $redeem['status']       = 1;
          $redeem['id_kasir']     = $id;
          DB::table('tbl_transaksi_poin')->where('id_kasir',$id_kasir)->update($redeem);
        }

        if(count($id_del2) > 0){
          $d_barang_stok = DB::table('tbl_kasir_detail')->where('id_detail_kasir_produk','0')->whereIn('id_detail_kasir', $id_del2)->get();
          foreach($d_barang_stok as $d){
             DB::table('tbl_log_stok')->where('log_stok_id', $d->id_log_stok)->delete();
          }
          DB::table('tbl_kasir_detail')->where('id_detail_kasir_produk','0')->whereIn('id_detail_kasir', $id_del2)->delete();
        }

        if(count($id_del) > 0){
          $d_produk_stok = DB::table('tbl_kasir_detail')->whereIn('id_detail_kasir_produk', $id_del)->get();
          foreach($d_produk_stok as $d){
             DB::table('tbl_log_stok')->where('log_stok_id', $d->id_log_stok)->delete();
          }
          DB::table('tbl_kasir_detail_produk')->whereIn('id_kasir_detail_produk', $id_del)->delete();
          DB::table('tbl_kasir_detail')->whereIn('id_detail_kasir_produk', $id_del)->delete();

        }


      }
                DB::commit();
                $message = 'Berhasil Simpan Data';
                return Response::success($message, $data);

            }catch(\Throwable $e){
                DB::rollback();
                return Response::error('Gagal Simpan Data');
            }
            
        } catch (Exception $e) {
            return Response::error('Gagal load database');
        }
    }

    
}
