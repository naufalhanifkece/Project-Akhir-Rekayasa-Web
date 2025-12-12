<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of all siswa (READ)
     */
    public function index()
    {
        try {
            $siswa = Siswa::with('kelas')->get();
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diambil',
                'data' => $siswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created siswa in database (CREATE)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nisn' => 'required|string|unique:siswa,nisn',
                'kelas_id' => 'required|exists:kelas,id',
                'no_telepon' => 'nullable|string',
                'alamat' => 'nullable|string',
            ]);

            $siswa = Siswa::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil ditambahkan',
                'data' => $siswa->load('kelas')
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified siswa (READ)
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::with('kelas')->find($id);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diambil',
                'data' => $siswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified siswa in database (UPDATE)
     */
    public function update(Request $request, $id)
    {
        try {
            $siswa = Siswa::find($id);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'nama' => 'sometimes|string|max:255',
                'nisn' => 'sometimes|string|unique:siswa,nisn,' . $id,
                'kelas_id' => 'sometimes|exists:kelas,id',
                'no_telepon' => 'nullable|string',
                'alamat' => 'nullable|string',
            ]);

            $siswa->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil diperbarui',
                'data' => $siswa->load('kelas')
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified siswa from database (DELETE)
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::find($id);

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siswa tidak ditemukan'
                ], 404);
            }

            $siswa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Siswa berhasil dihapus',
                'data' => $siswa
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
