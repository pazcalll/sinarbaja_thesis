@php
	use App\Http\Controllers\Helper;
	use App\Gudang;
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="utf-8">
		<title>Surat Jalan</title>
		<style>
			body {font-family: sans-serif;
				font-size: 10pt;
			}
			p {	margin: 0pt; }
			table.items {
				border: 0.1mm solid #000000;
			}
			td { vertical-align: top; }
			.items td {
				border-left: 0.1mm solid #000000;
				border-right: 0.1mm solid #000000;
			}
			table thead td { background-color: #EEEEEE;
				text-align: center;
				border: 0.1mm solid #000000;
				font-variant: small-caps;
			}
			.items td.blanktotal {
				background-color: #EEEEEE;
				border: 0.1mm solid #000000;
				background-color: #FFFFFF;
				border: 0mm none #000000;
				border-top: 0.1mm solid #000000;
				border-right: 0.1mm solid #000000;
			}
			.items td.totals {
				text-align: right;
				border: 0.1mm solid #000000;
			}
			.items td.cost {
				text-align: "." center;
			}
		</style>
		</head>
		<body>


		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
		<thead>
		<tr>
			<td width="5%">No</td>
			<td width="35%">Deskripsi Barang</td>
			<td width="10%">Qty</td>
			<td width="20%">Gudang</td>
			<td width="20%">Paraf</td>
			<td width="20%">Keterangan</td>
		</tr>
		</thead>
		<tbody>
			{{-- {{dd($PurchaseOrder)}} --}}
		@forelse ($PurchaseOrder->orders as $key => $item)
			@php
				$data = $item->tagihan->id_gudang;
				$nilai = json_decode($data, true);
			@endphp
			@foreach ($nilai as $i => $k)
				@php
					$PurchaseOrder->orders[$i]->gudang = $k[1];
				@endphp
			@endforeach
			
			<tr>
				<td align="center">{{++$key}}</td>
				<td>{{$item->nama_barang}}</td>
				<td align="center">{{$item->qty}}</td>
				<td>{{$item['gudang']}}</td>
				<td width="20%" align="center">(...................................................)</td>
				<td align="left" class="cost">-</td>
			</tr>
		@empty
		@endforelse
		</tbody>
		</table>
		<br />
		<div style="margin-top:5px;font-size: 10pt;text-align: right">Kediri, {{Helper::tgl_full(date('Y-m-d'),1)}}</div>

		<table style="font-size: 8pt; margin-top:15px;">
			<tr>
				<td width="20%" align="center">Penerima :</td>
				<td width="20%"></td>
				<td width="20%" align="center">Pengirim :</td>
				<td width="20%"></td>
				<td width="20%" align="center">Mengetahui :</td>
			</tr>
			<tr>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
			</tr>
			<tr>
				<td width="20%" align="center">(...................................................)</td>
				<td width="20%"></td>
				<td width="20%" align="center">(...................................................)</td>
				<td width="20%"></td>
				<td width="20%" align="center">(...................................................)</td>
			</tr>
		</table>
		<br/><br/><br/>
		<br/><br/><br/>
		
		<table width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
			<thead>
				<tr>
					@foreach ($PurchaseOrder->orders as $k => $v)
						@php
							$data = $v->tagihan->memo;
						@endphp
					@endforeach
					<td width="20%">Note : </td>
					<td width="80%" style="text-align: left;">
						@if ($data == null)
							{{'-'}}
						@else
							{{$data}}
						@endif

					</td>
				</tr>
			</thead>
		</table>
	</body>
</html>
