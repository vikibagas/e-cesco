<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Label {{ $shipment->airwaybill_kode }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;padding:20px;background:#f3f5f3;color:#15251b;font:14px Arial,sans-serif}.label{width:100mm;min-height:145mm;margin:auto;padding:8mm;border:1px solid #111;background:#fff}.brand{font-size:18px;font-weight:800}.brand span{color:#16763d}.awb{margin:10px 0;padding:10px 0;border-top:2px solid #111;border-bottom:2px solid #111;text-align:center;font-size:23px;font-weight:800;letter-spacing:1px}.row{margin:9px 0}.caption{display:block;margin-bottom:3px;color:#526057;font-size:9px;font-weight:700;text-transform:uppercase}.recipient{font-size:18px;font-weight:800}.destination{margin-top:12px;padding:9px;background:#f0f5f1;font-size:18px;font-weight:800}.bottom{display:flex;justify-content:space-between;margin-top:16px;padding-top:8px;border-top:1px solid #aab5ad;font-size:11px}.actions{margin:0 auto 12px;text-align:center}.actions button{border:0;border-radius:6px;background:#16763d;color:#fff;padding:9px 16px;font-weight:700}@media print{@page{size:100mm 150mm;margin:0}body{padding:0;background:#fff}.label{width:100mm;min-height:150mm;border:0;margin:0}.actions{display:none}}
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Cetak label</button></div>
    <main class="label">
        <div class="brand">CE<span>S</span>CO LOGISTICS</div>
        <div class="awb">{{ $shipment->airwaybill_kode }}</div>
        <div class="row"><span class="caption">Penerima</span><div class="recipient">{{ $shipment->nama_penerima }}</div><div>{{ $shipment->perusahaan_penerima }}</div><div>{{ $shipment->alamat_penerima }} {{ $shipment->alamat_penerima2 }}</div><div>{{ $shipment->hp_penerima }}</div></div>
        <div class="destination">{{ $shipment->kota_tujuan ?: $shipment->provinsi_tujuan }}</div>
        <div class="row"><span class="caption">Pengirim</span>{{ $shipment->perusahaan_pengirim ?: $shipment->nama_pengirim }}</div>
        <div class="bottom"><span>{{ $shipment->service_name }}</span><span>{{ $shipment->berat_aktual ?: $shipment->berat_barang }} kg · {{ $shipment->collie }} koli</span></div>
    </main>
</body>
</html>
