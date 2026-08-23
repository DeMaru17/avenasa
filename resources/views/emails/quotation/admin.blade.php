<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Permintaan Penawaran Baru</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 24px auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .header { background-color: #0f766e; color: #ffffff; padding: 24px 32px; }
        .header h1 { margin: 0 0 4px; font-size: 20px; font-weight: 700; }
        .header p { margin: 0; font-size: 13px; color: #ccfbf1; }
        .content { padding: 32px; }
        .badge { display: inline-block; padding: 4px 10px; font-size: 12px; font-weight: 600; border-radius: 6px; background-color: #f1f5f9; color: #475569; }
        .badge-product { background-color: #ccfbf1; color: #0f766e; }
        .table { width: 100%; border-collapse: collapse; margin-top: 16px; margin-bottom: 24px; }
        .table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; vertical-align: top; }
        .table td.label { font-weight: 600; color: #64748b; width: 140px; background-color: #f8fafc; }
        .message-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; font-size: 14px; color: #334155; white-space: pre-wrap; word-break: break-word; }
        .cta-btn { display: inline-block; background-color: #0f766e; color: #ffffff; text-decoration: none; padding: 12px 24px; font-size: 14px; font-weight: 600; border-radius: 8px; margin-top: 20px; }
        .footer { padding: 20px 32px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>PT Abhipraya Nawasena Sejahtera</h1>
            <p>Notifikasi Sistem Website — Permintaan Penawaran Baru</p>
        </div>
        <div class="content">
            <h2 style="font-size: 16px; margin-top: 0; color: #0f172a;">Rincian Data Calon Klien</h2>
            <table class="table">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td><strong>{{ $quotation->name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Alamat Email</td>
                    <td><a href="mailto:{{ $quotation->email }}" style="color: #0f766e;">{{ $quotation->email }}</a></td>
                </tr>
                <tr>
                    <td class="label">Telepon / WhatsApp</td>
                    <td>{{ $quotation->phone ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Perusahaan / Instansi</td>
                    <td>{{ $quotation->company ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Konteks Produk</td>
                    <td>
                        @if ($quotation->product)
                            <span class="badge badge-product">{{ $quotation->product->name_id }}</span>
                        @else
                            <span class="badge">Inquiry Umum</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Subjek</td>
                    <td>{{ $quotation->subject }}</td>
                </tr>
                <tr>
                    <td class="label">Bahasa (Locale)</td>
                    <td>{{ strtoupper($quotation->locale) }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu Pengiriman</td>
                    <td>{{ $quotation->created_at?->format('d M Y H:i') ?? '-' }} WIB</td>
                </tr>
            </table>

            <h3 style="font-size: 14px; margin-bottom: 8px; color: #0f172a;">Pesan / Rincian Kebutuhan:</h3>
            <div class="message-box">{{ $quotation->message }}</div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/quotations') }}" class="cta-btn">Buka di Filament CMS Admin &rarr;</a>
            </div>
        </div>
        <div class="footer">
            Email ini dibuat secara otomatis oleh sistem website resmi PT Abhipraya Nawasena Sejahtera.
        </div>
    </div>
</body>
</html>
