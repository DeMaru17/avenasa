@php
    $isEn = $quotation->locale === 'en';
@endphp
<!DOCTYPE html>
<html lang="{{ $isEn ? 'en' : 'id' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isEn ? 'Quotation Request Confirmation' : 'Konfirmasi Permintaan Penawaran' }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #1e293b; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 24px auto; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .header { background-color: #0f766e; color: #ffffff; padding: 28px 32px; }
        .header h1 { margin: 0 0 4px; font-size: 20px; font-weight: 700; }
        .header p { margin: 0; font-size: 13px; color: #ccfbf1; }
        .content { padding: 32px; }
        .salutation { font-size: 16px; font-weight: 600; color: #0f172a; margin-top: 0; margin-bottom: 12px; }
        .summary-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
        .summary-title { font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
        .summary-item { font-size: 14px; margin-bottom: 6px; color: #334155; }
        .contact-info { background-color: #f0fdfa; border: 1px solid #ccfbf1; border-radius: 8px; padding: 16px 20px; margin-top: 24px; font-size: 13px; color: #115e59; }
        .contact-info h4 { margin: 0 0 8px; font-size: 14px; font-weight: 700; color: #0f766e; }
        .footer { padding: 20px 32px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>PT Abhipraya Nawasena Sejahtera</h1>
            <p>{{ $isEn ? 'Official Company Website Confirmation' : 'Konfirmasi Resmi Website Perusahaan' }}</p>
        </div>
        <div class="content">
            <h2 class="salutation">
                {{ $isEn ? "Dear {$quotation->name}," : "Yth. {$quotation->name}," }}
            </h2>

            <p style="font-size: 14px; color: #334155; margin-bottom: 16px;">
                @if ($isEn)
                    Thank you for contacting <strong>PT Abhipraya Nawasena Sejahtera</strong>. We have successfully received your quotation request / inquiry.
                @else
                    Terima kasih telah menghubungi <strong>PT Abhipraya Nawasena Sejahtera</strong>. Permintaan penawaran harga / pertanyaan Anda telah berhasil kami terima.
                @endif
            </p>

            <div class="summary-box">
                <div class="summary-title">{{ $isEn ? 'Request Summary' : 'Ringkasan Permintaan' }}</div>
                <div class="summary-item">
                    <strong>{{ $isEn ? 'Subject:' : 'Subjek:' }}</strong> {{ $quotation->subject }}
                </div>
                @if ($quotation->product)
                    <div class="summary-item">
                        <strong>{{ $isEn ? 'Requested Product:' : 'Produk yang Diminta:' }}</strong>
                        {{ $isEn && !empty($quotation->product->name_en) ? $quotation->product->name_en : $quotation->product->name_id }}
                    </div>
                @endif
                @if ($quotation->company)
                    <div class="summary-item">
                        <strong>{{ $isEn ? 'Company / Institution:' : 'Instansi / Perusahaan:' }}</strong> {{ $quotation->company }}
                    </div>
                @endif
            </div>

            <p style="font-size: 14px; color: #334155; margin-bottom: 16px;">
                @if ($isEn)
                    Our technical and sales support team will review your inquiry and contact you regarding your requirements.
                @else
                    Tim penjualan dan dukungan teknis kami akan meninjau rincian kebutuhan Anda dan menghubungi Anda sesuai informasi yang disampaikan.
                @endif
            </p>

            <div class="contact-info">
                <h4>{{ $isEn ? 'Official Contact Information' : 'Informasi Kontak Resmi ANS' }}</h4>
                <div><strong>{{ $isEn ? 'Office:' : 'Kantor:' }}</strong> Mensana Tower Lt. 15, Jl. Raya Kranggan RT.002/RW.016, Cibubur, Bekasi 17433</div>
                <div><strong>{{ $isEn ? 'Phone:' : 'Telepon:' }}</strong> (021) 39722772</div>
                <div><strong>WhatsApp:</strong> 0822-614-614-00</div>
                <div><strong>Email:</strong> admin@avenasa.co.id</div>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} PT Abhipraya Nawasena Sejahtera. {{ $isEn ? 'All rights reserved.' : 'Hak cipta dilindungi undang-undang.' }}
        </div>
    </div>
</body>
</html>
