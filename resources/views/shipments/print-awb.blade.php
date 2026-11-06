<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AWB {{ $shipment->airwaybill_kode }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;padding:28px;background:#f2f5f3;color:#1f2d23;font:14px Arial,sans-serif}.sheet{max-width:850px;margin:auto;padding:30px;background:#fff;border:1px solid #dce5de;border-radius:10px}.top{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:2px solid #16763d;padding-bottom:18px}.brand{font-size:24px;font-weight:800}.brand span{color:#16763d}.awb{text-align:right;font-size:13px}.awb strong{display:block;margin-top:7px;font-size:25px;letter-spacing:1px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:20px}.card{border:1px solid #dce5de;border-radius:8px;padding:16px;min-height:140px}.card h2{margin:0 0 12px;color:#16763d;font-size:12px;text-transform:uppercase;letter-spacing:.07em}.card p{margin:5px 0;line-height:1.45}.details{margin-top:16px;border-collapse:collapse;width:100%}.details th,.details td{border:1px solid #dce5de;padding:10px;text-align:left}.details th{background:#f4f8f5;width:25%;font-size:12px}.actions{max-width:850px;margin:0 auto 12px;text-align:right}.actions button{border:0;border-radius:6px;background:#16763d;color:white;padding:10px 16px;font-weight:700;cursor:pointer}.muted{color:#718078}@media print{body{padding:0;background:#fff}.sheet{max-width:none;border:0;border-radius:0;padding:0}.actions{display:none}}
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Cetak AWB</button></div>
    <main class="sheet">
        <header class="top"><div class="brand">CE<span>S</span>CO LOGISTICS</div><div class="awb">AIR WAYBILL<strong>{{ $shipment->airwaybill_kode }}</strong><span class="muted">{{ $shipment->created_date }}</span></div></header>
        <section class="grid">
            <div class="card"><h2>Pengirim</h2><p><strong>{{ $shipment->nama_pengirim }}</strong></p><p>{{ $shipment->perusahaan_pengirim }}</p><p>{{ $shipment->alamat_pengirim }}</p><p>{{ $shipment->hp_pengirim }}</p></div>
            <div class="card"><h2>Penerima</h2><p><strong>{{ $shipment->nama_penerima }}</strong></p><p>{{ $shipment->perusahaan_penerima }}</p><p>{{ $shipment->alamat_penerima }} {{ $shipment->alamat_penerima2 }}</p><p>{{ $shipment->hp_penerima }}</p></div>
        </section>
        <table class="details"><tbody>
            <tr><th>Pelanggan</th><td>{{ $shipment->customer_name }}</td><th>Layanan</th><td>{{ $shipment->service_name }}</td></tr>
            <tr><th>Asal</th><td>{{ $shipment->kota_asal ?: $shipment->provinsi_asal }}</td><th>Tujuan</th><td>{{ $shipment->kota_tujuan ?: $shipment->provinsi_tujuan }}</td></tr>
            <tr><th>Jenis barang</th><td>{{ $shipment->jenis_barang }}</td><th>Berat</th><td>{{ $shipment->berat_aktual ?: $shipment->berat_barang }} kg</td></tr>
            <tr><th>Jumlah koli</th><td>{{ $shipment->collie }}</td><th>Catatan</th><td>{{ $shipment->catatan }}</td></tr>
        </tbody></table>
        <p class="muted">Dokumen pengiriman Cesco Logistics.</p>
    </main>
</body>
</html>
