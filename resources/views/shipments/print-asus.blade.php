<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ASUS AWB {{ $shipment->airwaybill_kode }}</title>
    <style>
        *{box-sizing:border-box}body{margin:0;padding:24px;background:#f2f5f3;color:#1f2d23;font:13px Arial,sans-serif}.sheet{max-width:1000px;margin:auto;padding:26px;background:#fff;border:1px solid #dce5de}.actions{max-width:1000px;margin:0 auto 12px;text-align:right}.actions button{border:0;border-radius:6px;background:#16763d;color:#fff;padding:10px 16px;font-weight:700;cursor:pointer}.top{display:flex;justify-content:space-between;align-items:start;border-bottom:2px solid #16763d;padding-bottom:16px}.brand{font-size:22px;font-weight:800}.brand span{color:#16763d}.awb{text-align:right}.awb strong{display:block;margin-top:5px;font-size:22px;letter-spacing:1px}.meta{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;padding:18px 0}.meta div{padding:10px;border:1px solid #dce5de}.meta small{display:block;margin-bottom:5px;color:#718078;font-size:10px;text-transform:uppercase}.items{width:100%;border-collapse:collapse}.items td,.items th{border:1px solid #aeb9b0;padding:8px;text-align:left}.items th{background:#f0f6f2;font-size:10px;text-transform:uppercase}.empty{text-align:center;color:#718078;padding:22px!important}@media print{body{padding:0;background:#fff}.sheet{max-width:none;border:0;padding:0}.actions{display:none}}
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Cetak AWB ASUS</button></div>
    <main class="sheet">
        <header class="top"><div class="brand">CE<span>S</span>CO LOGISTICS<br><small>ASUS SHIPMENT DETAIL</small></div><div class="awb">AIR WAYBILL<strong>{{ $shipment->airwaybill_kode }}</strong><span>{{ $shipment->created_date }}</span></div></header>
        <section class="meta"><div><small>Pengirim</small><strong>{{ $shipment->nama_pengirim }}</strong><br>{{ $shipment->perusahaan_pengirim }}</div><div><small>Penerima</small><strong>{{ $shipment->nama_penerima }}</strong><br>{{ $shipment->alamat_penerima }}</div><div><small>Tujuan</small><strong>{{ $shipment->kota_tujuan ?: $shipment->provinsi_tujuan }}</strong><br>{{ $shipment->service_name }}</div></section>
        <table class="items"><thead><tr><th>Product</th><th>APRC</th><th>Part No.</th><th>Description</th><th>RMA</th><th>Qty</th><th>Remark</th><th>W</th><th>H</th><th>L</th><th>Weight</th></tr></thead><tbody>
            @forelse ($items as $item)
                <tr><td>{{ $item->product }}</td><td>{{ $item->aprc }}</td><td>{{ $item->part_n }}</td><td>{{ $item->part_n_desc }}</td><td>{{ $item->rma_no }}</td><td>{{ $item->qty }}</td><td>{{ $item->remark }}</td><td>{{ $item->width }}</td><td>{{ $item->height }}</td><td>{{ $item->length }}</td><td>{{ $item->weight }}</td></tr>
            @empty
                <tr><td colspan="11" class="empty">Belum ada detail barang ASUS untuk AWB ini.</td></tr>
            @endforelse
        </tbody></table>
    </main>
</body>
</html>
