<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Display a listing of all guru (READ)
     */
    public function index()
    {
        try {
            $guru = Guru::all();
            return response()->json([
                'success' => true,
                'message' => 'Data guru berhasil diambil',
                'data' => $guru
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created guru in database (CREATE)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'nip' => 'required|string|unique:guru,nip',
                'mata_pelajaran' => 'required|string|max:255',
                'no_telepon' => 'nullable|string',
            ]);

            $guru = Guru::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan',
                'data' => $guru
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
     * Display the specified guru (READ)
     */
    public function show($id)
    {
        try {
            $guru = Guru::find($id);

            if (!$guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data guru berhasil diambil',
                'data' => $guru
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified guru in database (UPDATE)
     */
    public function update(Request $request, $id)
    {
        try {
            $guru = Guru::find($id);

            if (!$guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'nama' => 'sometimes|string|max:255',
                'nip' => 'sometimes|string|unique:guru,nip,' . $id,
                'mata_pelajaran' => 'sometimes|string|max:255',
                'no_telepon' => 'nullable|string',
            ]);

            $guru->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diperbarui',
                'data' => $guru
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
     * Remove the specified guru from database (DELETE)
     */
    public function destroy($id)
    {
        try {
            $guru = Guru::find($id);

            if (!$guru) {
                return response()->json([
                    'success' => false,
                    'message' => 'Guru tidak ditemukan'
                ], 404);
            }

            $guru->delete();

            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dihapus',
                'data' => $guru
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
