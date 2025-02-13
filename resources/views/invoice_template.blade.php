<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; background: #f5f5f5; }
        .invoice-box { width: 100%; max-width: 800px; margin: auto; padding: 20px; background: #dde6e6; border-radius: 10px; }
        .header {
            display: flex;
            border-bottom: 2px solid #334d34;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #334d34;
        }

        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background: #1f3d2b; color: #fff; }
        .total { font-weight: bold; }
    </style>
</head>
<body>

    @php
        function terbilang($angka) {
            $angka = abs($angka);
            $satuan = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

            if ($angka < 12) {
                return " " . $satuan[$angka];
            } elseif ($angka < 20) {
                return terbilang($angka - 10) . " Belas";
            } elseif ($angka < 100) {
                return terbilang(floor($angka / 10)) . " Puluh" . terbilang($angka % 10);
            } elseif ($angka < 200) {
                return " Seratus" . terbilang($angka - 100);
            } elseif ($angka < 1000) {
                return terbilang(floor($angka / 100)) . " Ratus" . terbilang($angka % 100);
            } elseif ($angka < 2000) {
                return " Seribu" . terbilang($angka - 1000);
            } elseif ($angka < 1000000) {
                return terbilang(floor($angka / 1000)) . " Ribu" . terbilang($angka % 1000);
            } elseif ($angka < 1000000000) {
                return terbilang(floor($angka / 1000000)) . " Juta" . terbilang($angka % 1000000);
            } elseif ($angka < 1000000000000) {
                return terbilang(floor($angka / 1000000000)) . " Miliar" . terbilang($angka % 1000000000);
            }

            return "Angka terlalu besar";
        }
    @endphp

    <div class="invoice-box">
        <table width="100%">
            <tr>
                <td width="10%"><img src="{{ $imageSrc }}" alt="Logo Sekolah" width="80"></td>
                <td width="80%" align="center" style="text-align: left;">
                    <p><strong>CHIPTA DHARMA</strong><br>Jln. Jalan ke pasar minggu</p>
                </td>
                <td width="10%" align="right" style="font-size: 25px; font-weight: bold;"><strong>INVOICE</strong></td>
            </tr>
        </table>

        <p><strong>Kepada</strong></p>
        <table style="width: 100%">
            <tr>
                <td><strong>Nama Wali:</strong> {{ $user->nama_wali }}</td>
                <td><strong>Tanggal:</strong> {{ $data_tagihan['tanggal_terbit'] }}</td>
            </tr>
            <tr>
                <td><strong>Nama Murid:</strong> {{ $user->nama }}</td>
                <td><strong>No. Invoice:</strong> {{ $data_tagihan['no_invoice'] }}</td>
            </tr>
            <tr>
                <td><strong>Kelas:</strong> {{ $user->kelas }}</td>
                <td><strong>Bulan:</strong> {{ $data_tagihan['bulan'] }}</td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th>No.</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
            <tr>
                <td>1</td>
                <td>{{ $biaya->nama_biaya }}</td>
                <td>Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2" class="total">Total</td>
                <td class="total">Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
            </tr>
        </table>

        <p><strong>Terbilang:</strong> {{ terbilang($biaya->nominal) }}</p>
        <p style="text-align: center; margin-top: 20px;"><strong>Terima Kasih</strong><br>_________X_________</p>
    </div>
</body>
</html>
