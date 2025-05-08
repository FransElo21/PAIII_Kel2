<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function landingpage() {
        // Ambil semua properti tanpa harga kamar
        $properties = DB::select("CALL viewAll_Properties()");
        
        // Loop untuk menambahkan harga kamar termurah dan rating ke setiap properti
        foreach ($properties as $property) {
            // Harga kamar termurah
            $priceResult = DB::select("CALL get_MinRoomPriceByProperty(?)", [$property->property_id]);
            $property->min_price = $priceResult[0]->min_price ?? 0;
    
            // Rating rata-rata
            $ratingResult = DB::select("CALL get_AverageRating(?)", [$property->property_id]);
            $property->avg_rating = $ratingResult[0]->avg_rating ?? 0;
            $property->total_reviews = $ratingResult[0]->total_reviews ?? 0;
        }
        
        return view('customers/welcome', compact('properties'));
    }

    public function test() {
        return view('customers.test');
    }
    

    public function showLoginForm() {
        return view('login'); 
    }

    public function showRegisterForm()
    {
        $userRoles = DB::select("CALL get_user_roles()");
        $userTypes = DB::select('CALL getAllUserTypes()');

        return view('register', compact('userTypes', 'userRoles'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('landingpage')->with('success', 'Anda telah logout.');
    }

    public function insertUserType(Request $request)
    {
        $dataUserType = json_encode([
            'userType_name' => $request->userType_name,
            'description'   => $request->description
        ]);

        $response = DB::statement('CALL insert_user_type(?)', [$dataUserType]);

        if ($response) {
            return response()->json(['success' => 'User Type berhasil ditambahkan'], 201);
        } else {
            return response()->json(['error' => 'Gagal menambahkan User Type'], 500);
        }
    }

    // Insert User Role
    public function insertUserRole(Request $request)
    {
        $dataUserRole = json_encode([
            'role_name'   => $request->role_name,
            'description' => $request->description
        ]);

        $response = DB::statement('CALL insert_user_role(?)', [$dataUserRole]);

        if ($response) {
            return response()->json(['success' => 'User Role berhasil ditambahkan'], 201);
        } else {
            return response()->json(['error' => 'Gagal menambahkan User Role'], 500);
        }
    }
    
    // 1. Registrasi Pengguna
    public function insertRegister(Request $request)
    {
        // Validasi input dasar
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Validasi tipe user
        $allowedUserTypes = ['Customer', 'Pengusaha'];
        $selectedUserType = DB::table('user_types')
            ->where('id', $request->user_type_id)
            ->first();

        if (!$selectedUserType || !in_array($selectedUserType->userType_name, $allowedUserTypes)) {
            return back()->withErrors(['user_type_id' => 'Tipe user tidak valid'])->withInput();
        }

        // Hash password
        $hashedPassword = Hash::make($request->password);

        // Tetapkan role berdasarkan user_type
        $defaultUserRole = ($selectedUserType->userType_name === 'Customer') ? 3 : 2;

        // Format data JSON untuk Stored Procedure
        $dataUser = json_encode([
            'name' => $request->username,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $hashedPassword,
            'user_type_id' => $request->user_type_id,
            'user_role_id' => $defaultUserRole,
            'verification_token' => sha1($request->email), // Token dari email
        ]);

        try {
            // Panggil Stored Procedure
            $result = DB::select("CALL store_registerUser(?)", [$dataUser]);

            if (empty($result) || !isset($result[0]->user_id)) {
                return back()->withErrors(['registration' => 'Gagal mendaftarkan akun'])->withInput();
            }

            // Kirim email verifikasi
            $this->sendVerificationEmail($request->email, $result[0]->user_id, sha1($request->email));

            return redirect('/login')->with('success', 'Registrasi berhasil! Silakan periksa email Anda.');
        } catch (\Exception $e) {
            return back()->withErrors(['registration' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    // 2. Verifikasi Email
    public function verifyEmail($userId, $token)
    {
        $user = DB::table('users')->where('id', $userId)->first();

        // Validasi token dan email
        if (!$user || $user->verification_token !== $token) {
            return redirect('/login')->withErrors(['email' => 'Token tidak valid atau sudah digunakan.']);
        }

        // Update status verifikasi
        DB::table('users')
            ->where('id', $userId)
            ->update([
                'email_verified_at' => Carbon::now(),
                'verification_token' => null // Hapus token setelah diverifikasi
            ]);

        return redirect('/login')->with('success', 'Email berhasil diverifikasi!');
    }

    // 3. Kirim Ulang Email Verifikasi
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan.');
        }

        if ($user->email_verified_at) {
            return back()->with('error', 'Email sudah diverifikasi.');
        }

        // Buat token baru dan kirim ulang
        $newToken = sha1($user->email);
        DB::table('users')->where('id', $user->id)->update(['verification_token' => $newToken]);

        $this->sendVerificationEmail($user->email, $user->id, $newToken);

        return back()->with('success', 'Email verifikasi telah dikirim ulang.');
    }

    // 4. Fungsi Kirim Email
    private function sendVerificationEmail($email, $userId, $token)
    {
        $verificationUrl = route('verify.email', ['userId' => $userId, 'token' => $token]);

        Mail::send('emails.verify-email', [
            'username' => $userId,
            'verificationUrl' => $verificationUrl,
        ], function ($message) use ($email) {
            $message->to($email)->subject('Verifikasi Email - HomMie');
        });
    }

    // 5. Login Pengguna
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Panggil SP untuk mencari pengguna
            $userData = DB::select("CALL login_user(?)", [$request->email]);

            if (empty($userData)) {
                return redirect()->route('login')->with('error', 'Email tidak ditemukan.');
            }

            $user = (object) $userData[0];

            // Validasi password
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->route('login')->with('error', 'Password salah.');
            }

            // Cek status verifikasi email
            if (!$user->email_verified_at) {
                return redirect()->route('login')->with('error', 'Email belum diverifikasi.');
            }

            // Login pengguna
            $loggedInUser = User::find($user->id);
            Auth::login($loggedInUser);

            // Redirect berdasarkan role
            if ($user->user_role_id == 3) {
                return redirect()->route('landingpage')->with('success', 'Login berhasil!');
            } elseif ($user->user_role_id == 2) {
                return redirect()->route('owner.dashboard')->with('success', 'Login sebagai Owner!');
            }

            return redirect()->route('dashboard')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
    