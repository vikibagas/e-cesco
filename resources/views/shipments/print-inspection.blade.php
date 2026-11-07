<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form pemeriksaan {{ $shipment->airwaybill_kode }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;padding:24px;background:#f2f5f3;color:#1f2d23;font:14px Arial,sans-serif}.sheet{max-width:820px;margin:auto;padding:28px;background:#fff;border:1px solid #dce5de}.actions{max-width:820px;margin:0 auto 12px;text-align:right}.actions button{border:0;border-radius:6px;background:#16763d;color:#fff;padding:10px 16px;font-weight:700;cursor:pointer}.top{display:flex;justify-content:space-between;border-bottom:2px solid #16763d;padding-bottom:14px}.brand{font-size:22px;font-weight:800}.brand span{color:#16763d}.awb{text-align:right}.awb strong{display:block;margin-top:6px;font-size:22px}.details{width:100%;margin-top:18px;border-collapse:collapse}.details td,.details th{border:1px solid #aeb9b0;padding:9px;text-align:left}.details th{width:24%;background:#f4f8f5}.checklist{margin-top:22px}.check{display:inline-block;width:15px;height:15px;border:1px solid #333;vertical-align:middle;margin-right:6px}.signature{display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:38px;text-align:center}.signature>div{min-height:100px;padding-top:65px;border-top:1px solid #777}@media print{body{padding:0;background:#fff}.sheet{max-width:none;border:0;padding:0}.actions{display:none}}
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Cetak form pemeriksaan</button></div>
    <main class="sheet">
        <header class="top"><div class="brand">CE<span>S</span>CO LOGISTICS<br><small>FORM PEMERIKSAAN PENGIRIMAN</small></div><div class="awb">NO. AWB<strong>{{ $shipment->airwaybill_kode }}</strong></div></header>
        <table class="details"><tbody>
            <tr><th>Pelanggan</th><td>{{ $shipment->customer_name }}</td></tr>
            <tr><th>Pengirim</th><td>{{ $shipment->nama_pengirim }} — {{ $shipment->hp_pengirim }}</td></tr>
            <tr><th>Penerima</th><td>{{ $shipment->nama_penerima }} — {{ $shipment->hp_penerima }}</td></tr>
            <tr><th>Rute</th><td>{{ $shipment->kota_asal ?: $shipment->provinsi_asal }} → {{ $shipment->kota_tujuan ?: $shipment->provinsi_tujuan }}</td></tr>
            <tr><th>Layanan / Barang</th><td>{{ $shipment->service_name }} / {{ $shipment->jenis_barang }}</td></tr>
            <tr><th>Berat / Koli</th><td>{{ $shipment->berat_aktual ?: $shipment->berat_barang }} kg / {{ $shipment->collie }} koli</td></tr>
        </tbody></table>
        <section class="checklist"><strong>Kondisi barang saat pemeriksaan</strong><p><span class="check"></span> Kemasan baik &nbsp;&nbsp; <span class="check"></span> Kemasan rusak &nbsp;&nbsp; <span class="check"></span> Isi sesuai</p><p>Catatan: __________________________________________________________________________</p><p>____________________________________________________________________________________</p></section>
        <footer class="signature"><div>Petugas pemeriksa</div><div>Pelanggan / pengirim</div></footer>
    </main>
</body>
</html>
