<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PDO;
use Throwable;

class KegiatanController extends Controller
{
    public function index(Request $request)
{
    try {
        $pdo = DB::connection()->getPdo();

        $keyword = trim($request->query('q', ''));
        $jenisId = $request->query('jenis_kegiatan_id');

        $sql = 'SELECT k.*, j.nama_jenis
                FROM kegiatan k
                JOIN jenis_kegiatan j
                ON k.jenis_kegiatan_id = j.id';

        $conditions = [];
        $params = [];

        if ($keyword !== '') {
            $conditions[] = '(
                k.kode_kegiatan LIKE :kode
                OR k.nama_kegiatan LIKE :nama
                OR k.email_pic LIKE :email
            )';

            $search = '%' . $keyword . '%';

            $params[':kode'] = $search;
            $params[':nama'] = $search;
            $params[':email'] = $search;
        }

        if ($jenisId !== null && $jenisId !== '') {
            $conditions[] = 'k.jenis_kegiatan_id = :jenis';
            $params[':jenis'] = $jenisId;
        }

        if ($conditions) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY k.id ASC';

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $kegiatan = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $jenis = $pdo->query(
            'SELECT id, nama_jenis
             FROM jenis_kegiatan
             ORDER BY nama_jenis'
        )->fetchAll(PDO::FETCH_ASSOC);

        return view('kegiatan.index', compact(
            'kegiatan',
            'jenis',
            'keyword',
            'jenisId'
        ));
    } catch (Throwable $e) {
        report($e);

        return response(
            'Data tidak dapat ditampilkan. Silakan coba kembali.',
            500
        );
    }
}

private function validasi(Request $request, $id = null)
{
    $data = $request->all();

    foreach ([
        'kode_kegiatan',
        'nama_kegiatan',
        'email_pic',
    ] as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = trim(strip_tags($data[$field]));
        }
    }

    $request->merge($data);

    $validator = Validator::make($data, [
        'kode_kegiatan' => [
            'required',
            'alpha_num',
            'size:10',
            Rule::unique('kegiatan', 'kode_kegiatan')->ignore($id),
        ],
        'nama_kegiatan' => [
            'required',
            'string',
            'min:3',
            'max:100',
        ],
        'email_pic' => [
            'required',
            'email',
            'max:100',
            Rule::unique('kegiatan', 'email_pic')->ignore($id),
        ],
        'kuota' => [
            'required',
            'integer',
            'min:10',
            'max:200',
        ],
        'jenis_kegiatan_id' => [
            'required',
            'integer',
            'exists:jenis_kegiatan,id',
        ],
    ], [
        'kode_kegiatan.required' => 'Kode kegiatan wajib diisi.',
        'kode_kegiatan.alpha_num' => 'Kode hanya boleh berisi huruf dan angka.',
        'kode_kegiatan.size' => 'Kode harus tepat 10 karakter.',
        'kode_kegiatan.unique' => 'Kode kegiatan sudah digunakan.',
        'nama_kegiatan.min' => 'Nama kegiatan minimal 3 karakter.',
        'email_pic.email' => 'Format email tidak valid.',
        'email_pic.unique' => 'Email PIC sudah digunakan.',
        'kuota.min' => 'Kuota minimal 10.',
        'kuota.max' => 'Kuota maksimal 200.',
        'jenis_kegiatan_id.exists' => 'Jenis kegiatan tidak tersedia.',
    ]);

    return $validator;
}

public function store(Request $request)
{
    $validator = $this->validasi($request);

if ($validator->fails()) {
    return redirect('/kegiatan')
        ->withErrors($validator)
        ->withInput();
}

$data = $validator->validated();

    try {
        $pdo = DB::connection()->getPdo();

        $stmt = $pdo->prepare(
            'INSERT INTO kegiatan
            (kode_kegiatan, nama_kegiatan, email_pic,
             kuota, jenis_kegiatan_id, created_at, updated_at)
            VALUES
            (:kode, :nama, :email, :kuota, :jenis,
             NOW(), NOW())'
        );

        $stmt->execute([
            ':kode' => $data['kode_kegiatan'],
            ':nama' => $data['nama_kegiatan'],
            ':email' => $data['email_pic'],
            ':kuota' => $data['kuota'],
            ':jenis' => $data['jenis_kegiatan_id'],
        ]);

        return redirect('/kegiatan')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    } catch (Throwable $e) {
        report($e);

        return redirect('/kegiatan')
            ->withInput()
            ->with('error', 'Data gagal disimpan. Periksa kembali input.');
    }
}

public function edit($id)
{
    try {
        $pdo = DB::connection()->getPdo();

        $stmt = $pdo->prepare(
            'SELECT * FROM kegiatan WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            abort(404);
        }

        $jenis = $pdo->query(
            'SELECT id, nama_jenis
             FROM jenis_kegiatan ORDER BY nama_jenis'
        )->fetchAll(PDO::FETCH_ASSOC);

        return view('kegiatan.edit', compact('item', 'jenis'));
    } catch (Throwable $e) {
        report($e);

        return response('Data gagal dimuat.', 500);
    }
}

public function update(Request $request, $id)
{
    $validator = $this->validasi($request, $id);

if ($validator->fails()) {
    return redirect("/kegiatan/{$id}/edit")
        ->withErrors($validator)
        ->withInput();
}

$data = $validator->validated();

    try {
        $pdo = DB::connection()->getPdo();

        $stmt = $pdo->prepare(
            'UPDATE kegiatan SET
             kode_kegiatan = :kode,
             nama_kegiatan = :nama,
             email_pic = :email,
             kuota = :kuota,
             jenis_kegiatan_id = :jenis,
             updated_at = NOW()
             WHERE id = :id'
        );

        $stmt->execute([
            ':kode' => $data['kode_kegiatan'],
            ':nama' => $data['nama_kegiatan'],
            ':email' => $data['email_pic'],
            ':kuota' => $data['kuota'],
            ':jenis' => $data['jenis_kegiatan_id'],
            ':id' => $id,
        ]);

        if ($stmt->rowCount() === 0) {
            $check = $pdo->prepare(
                'SELECT id FROM kegiatan WHERE id = :id'
            );
            $check->execute([':id' => $id]);

            if (!$check->fetch()) {
                abort(404);
            }
        }

        return redirect('/kegiatan')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    } catch (Throwable $e) {
        report($e);

        return redirect("/kegiatan/{$id}/edit")
            ->withInput()
            ->with('error', 'Perubahan gagal disimpan.');
    }
}

public function destroy($id)
{
    try {
        $pdo = DB::connection()->getPdo();

        $stmt = $pdo->prepare(
            'DELETE FROM kegiatan WHERE id = :id'
        );

        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            return redirect('/kegiatan')
                ->with('error', 'Data tidak ditemukan.');
        }

        return redirect('/kegiatan')
            ->with('success', 'Kegiatan berhasil dihapus.');
    } catch (Throwable $e) {
        report($e);

        return redirect('/kegiatan')
            ->with('error', 'Data gagal dihapus.');
    }
}


}
