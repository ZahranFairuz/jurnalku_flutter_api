<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'nis' => 'required|string', // Hanya NIS
                'password' => 'required|string',
            ]);

            // Cari user berdasarkan NIS
            $user = User::where('nis', $request->nis)->first();

            // Check jika user tidak ditemukan atau password salah
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIS atau password salah',
                    'errors' => ['credentials' => ['NIS atau password tidak valid']]
                ], 401);
            }

            // Buat token Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            // Response sukses
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'nis' => $user->nis,
                        'rombel' => $user->rombel,
                        'rayon' => $user->rayon,
                        'grade' => $user->grade,
                        'photo_profile' => $user->photo_profile,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Hapus token saat ini
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAllUsers(Request $request)
{
    try {
        $query = User::query();

        // Filter berdasarkan parameter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('rombel', 'like', "%{$search}%");
            });
        }

        if ($request->has('rombel') && $request->rombel) {
            $query->where('rombel', $request->rombel);
        }

        if ($request->has('rayon') && $request->rayon) {
            $query->where('rayon', $request->rayon);
        }

        if ($request->has('grade') && $request->grade) {
            $query->where('grade', $request->grade);
        }

        // Pagination
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 5);
        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'message' => 'Data siswa berhasil diambil',
            'data' => [
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function getProfile(Request $request)
{
    try {
        $user = $request->user();
        
        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diambil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'nis' => $user->nis,
                    'rombel' => $user->rombel,
                    'rayon' => $user->rayon,
                    'grade' => $user->grade,
                    'photo_profile' => $user->photo_profile,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]
            ]
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function uploadPhoto(Request $request)
{
    try {
        // Validasi file
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = $request->user();
        
        // Hapus foto lama jika ada dan file exists
        if ($user->photo_profile && Storage::disk('public')->exists($user->photo_profile)) {
            Storage::disk('public')->delete($user->photo_profile);
        }

        // Upload foto baru
        $file = $request->file('photo');
        $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_photos', $filename, 'public');

        // Update database
        $user->update([
            'photo_profile' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diupload',
            'data' => [
                'photo_url' => asset('storage/' . $path),
                'photo_path' => $path
            ]
        ], 200);

    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}




}