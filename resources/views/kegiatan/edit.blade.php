<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kegiatan</title>
</head>
<body>
    <h1>Edit Kegiatan</h1>

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('kegiatan.update', $item['id']) }}"
        method="POST">
        @csrf
        @method('PUT')

        <p>
            <label>Kode Kegiatan</label><br>
            <input type="text" name="kode_kegiatan" maxlength="10"
                required
                value="{{ old('kode_kegiatan', $item['kode_kegiatan']) }}">
        </p>

        <p>
            <label>Nama Kegiatan</label><br>
            <input type="text" name="nama_kegiatan" required
                value="{{ old('nama_kegiatan', $item['nama_kegiatan']) }}">
        </p>

        <p>
            <label>Email PIC</label><br>
            <input type="email" name="email_pic" required
                value="{{ old('email_pic', $item['email_pic']) }}">
        </p>

        <p>
            <label>Kuota</label><br>
            <input type="number" name="kuota" min="10" max="200"
                required
                value="{{ old('kuota', $item['kuota']) }}">
        </p>

        <p>
            <label>Jenis Kegiatan</label><br>
            <select name="jenis_kegiatan_id" required>
                @foreach ($jenis as $j)
                    <option value="{{ $j['id'] }}"
                        @selected(old('jenis_kegiatan_id',
                            $item['jenis_kegiatan_id']) == $j['id'])>
                        {{ $j['nama_jenis'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('kegiatan.index') }}">Kembali</a>
    </form>
</body>
</html>