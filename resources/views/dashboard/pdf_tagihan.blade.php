<html>
	<head>
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
		<table width="100%" style="font-family: sans;" cellpadding="10">
			<tr>
				<td width="45%" style="border: 0.1mm solid #888888; ">
					<span style="font-size: 7pt; color: #555555; font-family: sans;">
						DIKIRIM DARI:<span><br /><br />
					<span style="font-weight: bold; font-size: 14pt;">{{$data[0]->nama}}</span>
					<br />{{$data[0]->alamat}}<br />
					<span style="font-family:dejavusanscondensed;">&#9742;</span>{{$data[0]->telp}}
				</td>

				<td width="10%">&nbsp;</td>
				{{-- {{dd($PurchaseOrder->po->user->profil)}}; --}}
				<td width="45%" style="border: 0.1mm solid #888888; ">
					<span style="font-size: 7pt; color: #555555; font-family: sans;">
						DITERIMA OLEH:<span><br /><br />
					<span style="font-weight: bold; font-size: 14pt;">{{$PurchaseOrder->po->user->name}}</span>
					{{-- <br />{{ App\GroupUser::where('id', $PurchaseOrder->po->user->id_group)->get('group_name')[0]->group_name }}<br /> --}}
					<br />
					<span>{{$PurchaseOrder->po->user->address}}</span>
					<br/><span style="font-family:dejavusanscondensed;">&#9742;</span> {{$PurchaseOrder->po->user->no_handphone}}
				</td>
			</tr>
		</table>

		<br />

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; " cellpadding="8">
			<thead>
				<tr>
				<td width="5%">No</td>
				<td width="10%">Kode Barang</td>
				<td width="30%">Deskripsi Barang</td>
				<td width="10%">Qty</td>
				<td width="20%">Harga Barang</td>
				<td width="15%">Potongan (Rp)</td>
				<td width="20%">Subtotal</td>
				</tr>
			</thead>
			<tbody>
		<!-- ITEMS HERE -->
				@php
					$total = 0;
					$jmlqty = 0;
				@endphp
		@forelse ($PurchaseOrder->orders as $key => $item)

				@php
					// dd($item);

					$qty = $item->qty;
					$pot = App\PurchaseOrder::where('id', $item->po_id)->get('potongan_po_rp')[0]->potongan_po_rp;
					$status = $item->status;
					$subtotal = ($item->harga_order * $qty) - $item->potongan_order_rp;
					$sub = $item->harga_order * $qty;
					$item->tagihan_id = 'null';
					$total += $subtotal;
					$jmlqty += $qty;
					$tot = $total - $pot;
				@endphp
				<tr>
					<td align="center">{{++$key}}</td>
					<td>{{ App\barang::where('barang_id', $item->product_id)->get('barang_kode')[0]->barang_kode }}</td>
					<td>{{$item->nama_barang}}</td>
					<td align="center">{{$item->qty}}</td>
					<td align="right" class="cost">Rp {{number_format($item->harga_order,2,',','.')}}</td>
					<td align="right" class="pot">Rp {{number_format($item->potongan_order_rp,2,',','.')}}</td>
					<td align="right" class="cost">Rp {{number_format($subtotal,2,',','.')}}</td>
				</tr>
		@empty
		@endforelse
				<tr>
					<td class="totals" colspan="5"></td>
					<td align= "center" class="totals"><b>TOTAL:</b></td>
					<td class="totals cost"><b>Rp {{number_format($total,2,',','.')}}</b></td>
				</tr>
			</tbody>
		</table>
		<br>
		<label class="title">Keterangan &nbsp;&nbsp;:</label>
		<div class="detail-tot" style="margin-left: 200pt;">
			<table>
				<tbody>
				<tr><td>Jml Item</td><td>:</td><td><b>{{$jmlqty}} bj</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Sub Total</td><td>:</td><td><b>Rp {{number_format($total,2,',','.')}}</b></td>
				</tr>
				<tr><td>Potongan</td><td>:</td><td><b>Rp {{number_format(App\PurchaseOrder::where('id', $item->po_id)->get('potongan_po_rp')[0]->potongan_po_rp,2,',','.')}}</b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Total Akhir</td><td>:</td><td><b>Rp {{number_format($tot,2,',','.')}}</b></td>
				</tr>
				<tr><td>Pajak</td><td>:</td><td><b>0%</b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>DP PO</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>
				</tr>
				<tr><td>Biaya Lain</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Tunai</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>
				</tr>
				<tr><td></td><td></td><td><b></b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Kredit</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>
				</tr>
				<tr><td></td><td></td><td><b></b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>K. Debit</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>
				</tr>
				<tr><td></td><td></td><td><b></b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>K. Kredit</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>
				</tr>
				<tr><td></td><td></td><td><b></b></td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td>Kembali</td><td>:</td><td><b>Rp {{number_format(0,2,',','.')}}</b></td>
				</tr>
				</tbody>
			</table>
		</div>
		<?php
			function penyebut($nilai) {
				$nilai = abs($nilai);
				$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
				$temp = "";
				if ($nilai < 12) {
					$temp = " ". $huruf[$nilai];
				} else if ($nilai <20) {
					$temp = penyebut($nilai - 10). " Belas";
				} else if ($nilai < 100) {
					$temp = penyebut($nilai/10)." Puluh". penyebut($nilai % 10);
				} else if ($nilai < 200) {
					$temp = " seratus" . penyebut($nilai - 100);
				} else if ($nilai < 1000) {
					$temp = penyebut($nilai/100) . " Ratus" . penyebut($nilai % 100);
				} else if ($nilai < 2000) {
					$temp = " seribu" . penyebut($nilai - 1000);
				} else if ($nilai < 1000000) {
					$temp = penyebut($nilai/1000) . " Ribu" . penyebut($nilai % 1000);
				} else if ($nilai < 1000000000) {
					$temp = penyebut($nilai/1000000) . " Juta" . penyebut($nilai % 1000000);
				} else if ($nilai < 1000000000000) {
					$temp = penyebut($nilai/1000000000) . " Milyar" . penyebut(fmod($nilai,1000000000));
				} else if ($nilai < 1000000000000000) {
					$temp = penyebut($nilai/1000000000000) . " Triliun" . penyebut(fmod($nilai,1000000000000));
				}
				return $temp;
			}

			function terbilang($nilai) {
				if($nilai<0) {
					$hasil = "Minus ". trim(penyebut($nilai));
				} else {
					$hasil = trim(penyebut($nilai));
				}
				return $hasil;
			}
		?>
		<label class="title">Terbilang &nbsp;&nbsp;: <b>{{terbilang($tot)}} Rupiah</b></label>
		<br>
		
	</body>
</html>
