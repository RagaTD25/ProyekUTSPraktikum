<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Kegiatan Mahasiswa</title>
</head>
<body>
    <h1>Data Kegiatan Mahasiswa</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

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

    <h2>Tambah Kegiatan</h2>

    <form action="{{ route('kegiatan.store') }}" method="POST">
        @csrf

        <p>
            <label>Kode Kegiatan</label><br>
            <input type="text" name="kode_kegiatan"
                maxlength="10" required
                value="{{ old('kode_kegiatan') }}">
        </p>

        <p>
            <label>Nama Kegiatan</label><br>
            <input type="text" name="nama_kegiatan"
                required value="{{ old('nama_kegiatan') }}">
        </p>

        <p>
            <label>Email PIC</label><br>
            <input type="email" name="email_pic"
                required value="{{ old('email_pic') }}">
        </p>

        <p>
            <label>Kuota</label><br>
            <input type="number" name="kuota"
                min="10" max="200" required
                value="{{ old('kuota') }}">
        </p>

        <p>
            <label>Jenis Kegiatan</label><br>
            <select name="jenis_kegiatan_id" required>
                <option value="">Pilih jenis</option>
                @foreach ($jenis as $j)
                    <option value="{{ $j['id'] }}"
                        @selected(old('jenis_kegiatan_id') == $j['id'])>
                        {{ $j['nama_jenis'] }}
                    </option>
                @endforeach
            </select>
        </p>

        <button type="submit">Simpan</button>
    </form>

    <hr>

    <h2>Pencarian dan Filter</h2>

    <form action="{{ route('kegiatan.index') }}" method="GET">
        <label>Kata kunci</label>
        <input type="text" name="q"
            value="{{ $keyword }}"
            placeholder="Kode, nama, atau email">

        <label>Jenis</label>
        <select name="jenis_kegiatan_id">
            <option value="">Semua jenis</option>
            @foreach ($jenis as $j)
                <option value="{{ $j['id'] }}"
                    @selected($jenisId == $j['id'])>
                    {{ $j['nama_jenis'] }}
                </option>
            @endforeach
        </select>

        <button type="submit">Cari</button>
        <a href="{{ route('kegiatan.index') }}">Reset</a>
    </form>

    <h2>Daftar Kegiatan</h2>

    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Email PIC</th>
                <th>Kuota</th>
                <th>Status Kuota</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($kegiatan as $item)
                <tr>
                    <td>{{ $item['kode_kegiatan'] }}</td>
                    <td>{{ $item['nama_kegiatan'] }}</td>
                    <td>{{ $item['email_pic'] }}</td>
                    <td>{{ $item['kuota'] }}</td>
                    <td>{{ statusKuota($item['kuota']) }}</td>
                    <td>{{ $item['nama_jenis'] }}</td>
                    <td>
                        <a href="{{ route('kegiatan.edit', $item['id']) }}">
                            Edit
                        </a>

                        <form action="{{ route('kegiatan.destroy', $item['id']) }}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Data tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>