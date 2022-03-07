<?php

namespace App\Http\Controllers;

class Helper extends Controller
{
    public static function storeImage($request, $field, $callback)
    {
        $allowedfileExtension = ['jpg', 'png', 'jpeg', 'pdf', 'svg'];
        Helper::storeFile($request, $field, $allowedfileExtension, $callback);
    }

    public static function storeDoc($request, $field, $callback)
    {
        $allowedfileExtension = ['doc', 'pdf', 'docx', 'odt'];
        Helper::storeFile($request, $field, $allowedfileExtension, $callback);
    }

    public static function storeFile($request, $field, $allowedfileExtension, $callback)
    {
        $store = function ($file, $loc) use ($callback, $allowedfileExtension) {
            $extension = $file->getClientOriginalExtension();
            if (in_array($extension, $allowedfileExtension)) {
                $file->store($loc);
                $callback($file->hashName());
            }
        };

        if ($request->has($field)) {
            // if (is_array($request[$field])) {
            //     // array selalu photo
            foreach ($request[$field] as $i => $file) {
                $store($file, 'public/' . $field);
            }
            // } else {
            //     // selalu dokumen
            //     $store($request->file($field), 'public/');
            // }
        }
    }

    public static function tgl_full($tgl, $jenis){
		$hari_h = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
		$tg = date("d", strtotime($tgl));
		$bln = date("m", strtotime($tgl));
		$bln2 = date("m", strtotime($tgl));
		$thn = date("Y", strtotime($tgl));
		$bln_h = array('01' => "Januari", "02" => "Februari", "03" => "Maret", "04" => "April", "05" => "Mei", "06" => "Juni", "07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember");
		$bln = $bln_h[$bln];
		$hari = $hari_h[date("w", strtotime($tgl))];

		$jam = date('H');
		$menit = date('i');
		$detik = date('s');

		$get_jam = date("H", strtotime($tgl));	
		$get_menit = date("i", strtotime($tgl));
		$get_detik = date("s", strtotime($tgl));

		if($jenis == '0'){
			$print = $tg.' '.$bln.' '.$thn;
		}else if($jenis == '1'){
			$print = $hari.', '.$tg.' '.$bln.' '.$thn;
		}else if($jenis == '2'){
			$print = $thn.'-'.$bln2.'-'.$tg;
		}else if($jenis == '3'){
			$print = $tg."/".$bln2;
		}else if($jenis == '4'){
			$print = strtotime($tgl);
		}else if($jenis == '5'){
			$print = $thn."-".$bln2."-".$tg." ".$jam.":".$menit.":".$detik;
		}else if($jenis == '6'){
			$print = $hari;
		}else if($jenis == '7'){
			$print = $tg."-".$bln2."-".$thn." ".$jam.":".$menit.":".$detik;
		}else if($jenis == '8'){
			$print = $hari." ".$jam.":".$menit.":".$detik;
		}else if($jenis == '9'){
			$print = $tg." ".$bln." ".$thn." ".$get_jam.":".$get_menit;
		}else if($jenis == '10'){
			$print = $hari_h[$tgl];
		}else if($jenis == '77'){
			$print = $hari.", ".$tg."-".$bln2."-".$thn." ".$get_jam.":".$get_menit;
		}else if($jenis == '78'){
			$print = $hari.", ".$tg." ".$bln." ".$thn." ".$jam.":".$menit;
		}else if($jenis == '79'){
			$print = $thn."-".$bln2."-".$tg." ".$get_jam.":".$get_menit;
		}else if($jenis == '98'){
			$print = $tg."-".$bln2."-".$thn;
		}else if($jenis == '99'){
			$print = $thn."-".$bln2."-".$tg;
		}else if($jenis == '100'){
			$print = $get_jam.":".$get_menit;
		}else if($jenis == '101'){
			$print = $bln;
		}else if($jenis == '102'){
			$print = $tg."-".$bln2."-".$thn." ".$get_jam.":".$get_menit;
		}else if($jenis == '103'){
			$print = $tg."-".$bln_h."-".$thn;
		}else if($jenis == '104'){
			$print = $thn;
		}else{
			$print = $tg.'-'.$bln2.'-'.$thn;
		}
		return $print;
	}

    public static function format_angka($var){
		if(is_numeric($var)){
			$var = number_format($var, 0, '.', '.');
		}else{
			$var = 0;
		}

		return $var;
	}
}
