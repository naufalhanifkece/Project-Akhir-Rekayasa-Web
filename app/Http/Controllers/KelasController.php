<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of all kelas (READ)
     */
    public function index()
    {
        try {
            $kelas = Kelas::with('siswa')->get();
            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diambil',
                'data' => $kelas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created kelas in database (CREATE)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama_kelas' => 'required|string|max:255',
                'tingkat' => 'nullable|string|max:255',
            ]);

            $kelas = Kelas::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil ditambahkan',
                'data' => $kelas
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
     * Display the specified kelas (READ)
     */
    public function show($id)
    {
        try {
            $kelas = Kelas::with('siswa')->find($id);

            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diambil',
                'data' => $kelas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified kelas in database (UPDATE)
     */
    public function update(Request $request, $id)
    {
        try {
            $kelas = Kelas::find($id);

            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'nama_kelas' => 'sometimes|string|max:255',
                'tingkat' => 'nullable|string|max:255',
            ]);

            $kelas->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil diperbarui',
                'data' => $kelas
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
     * Remove the specified kelas from database (DELETE)
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::find($id);

            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas tidak ditemukan'
                ], 404);
            }

            $kelas->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus',
                'data' => $kelas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
