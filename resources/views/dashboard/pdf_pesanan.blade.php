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
						DARI:<span><br /><br />
					<span style="font-weight: bold; font-size: 14pt;">Toko Sinar Baja</span>
					<br />Jl. Mawar Melati Indah No. 234<br />Kota Kediri<br />
					<span style="font-family:dejavusanscondensed;">&#9742;</span> +6289X-XXXX-XXXX
				</td>

				<td width="10%">&nbsp;</td>

				<td width="45%" style="border: 0.1mm solid #888888; ">
					<span style="font-size: 7pt; color: #555555; font-family: sans;">
						DITERIMA OLEH:<span><br /><br />
					<span style="font-weight: bold; font-size: 14pt;">{{ $Data[0]->user->name }}</span>
                    <br />{{ App\GroupUser::where('id', $Data[0]->user->id_group)->get('group_name')[0]->group_name }}<br />
					<span>{{ $Data[0]->user->address }}</span>
					<br /><span style="font-family:dejavusanscondensed;">&#9742;</span> {{ $Data[0]->user->no_handphone }}
				</td>
			</tr>
		</table>

		<br />

		<div class="visible-print">
			<div style="text-align: center;font-size: 20pt; text-weight: bold;">QrCode Pesanan Anda</div>
				<div class="form-code" style="text-align: center">
					
				</div>
			<br />
			<div style="text-align: center; font-style: italic;">Scan Me !</div>
		</div>
	</body>
</html>
