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
    public function landingPage()
    {
        // Ambil semua properti
        $properties = DB::select("CALL viewAll_Properties()");

        foreach ($properties as $property) {
            // Harga kamar termurah
            $priceResult = DB::select("CALL get_MinRoomPriceByProperty(?)", [
                $property->property_id
            ]);
            $property->min_price = $priceResult[0]->min_price ?? 0;

            // Rating rata-rata dan total review
            $ratingResult = DB::select("CALL get_AverageRating(?)", [
                $property->property_id
            ]);
            $property->avg_rating    = $ratingResult[0]->avg_rating    ?? 0;
            $property->total_reviews = $ratingResult[0]->total_reviews ?? 0;

            // Ambil city & district saja
            $loc = DB::select("CALL get_fullLocation1(?)", [
                $property->subdis_id
            ])[0] ?? null;

            $property->city     = $loc->city     ?? '';
            $property->district = $loc->district ?? '';
        }

        // Kirim ke view
        return view('customers.welcome', compact('properties'));
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
    // Aturan validasi
    $rules = [
        'username' => 'required|string|max:255|unique:users',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'user_type_id' => 'required|exists:user_types,id',
    ];

    $messages = [
        // Username
        'username.required' => 'Username wajib diisi.',
        'username.string' => 'Username harus berupa teks.',
        'username.max' => 'Username maksimal 255 karakter.',
        'username.unique' => 'Username sudah terpakai.',

        // Email
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.max' => 'Email maksimal 255 karakter.',
        'email.unique' => 'Email sudah terdaftar.',

        // Password
        'password.required' => 'Password wajib diisi.',
        'password.string' => 'Password harus berupa teks.',
        'password.min' => 'Password harus minimal 6 karakter.',
        'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',

        // User Type
        'user_type_id.required' => 'Tipe user wajib dipilih.',
        'user_type_id.exists' => 'Tipe user tidak valid.',
    ];

    // Jalankan validasi
    $validator = Validator::make($request->all(), $rules, $messages);

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

    // Tentukan is_confirmed sesuai tipe user
    // Penyewa (Customer) langsung confirmed, Pengusaha belum confirmed
    $isConfirmed = ($request->user_type_id == 3) ? true : false;

    // Sesuaikan role user berdasarkan user_type_id
    $defaultUserRole = $request->user_type_id;

    // Format data JSON untuk Stored Procedure, tambah is_confirmed
    $dataUser = json_encode([
        'name' => $request->username,
        'username' => $request->username,
        'email' => $request->email,
        'password' => $hashedPassword,
        'user_type_id' => $request->user_type_id,
        'user_role_id' => $defaultUserRole,
        'verification_token' => sha1($request->email),
        'is_confirmed' => $isConfirmed,
    ]);

    try {
        // Panggil Stored Procedure
        $result = DB::select("CALL store_registerUser(?)", [$dataUser]);

        if (empty($result) || !isset($result[0]->user_id)) {
            return back()->withErrors(['registration' => 'Gagal mendaftarkan akun'])->withInput();
        }

        // Kirim email verifikasi
        $this->sendVerificationEmail($request->email, $result[0]->user_id, sha1($request->email));

        $message = 'Registrasi berhasil! Silakan periksa email Anda untuk verifikasi.';
        if (!$isConfirmed) {
            $message .= ' Akun pengusaha Anda akan aktif setelah dikonfirmasi admin.';
        }

        return redirect('/login')->with('success', $message);
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
    // Aturan validasi untuk login
    $rules = [
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ];

    // Pesan error dalam Bahasa Indonesia
    $messages = [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.min' => 'Password harus minimal 6 karakter.',
    ];

    // Jalankan validasi
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput();
    }

    try {
        // Panggil SP untuk mencari pengguna
        $userData = DB::select("CALL login_user(?)", [$request->email]);

        if (empty($userData)) {
            return back()
                ->withErrors(['email' => 'Email tidak ditemukan.'])
                ->withInput();
        }

        $user = (object) $userData[0];

        // Validasi password
        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Password salah.'])
                ->withInput();
        }

        // Cek status verifikasi email
        if (!$user->email_verified_at) {
            return back()
                ->withErrors(['email' => 'Email belum diverifikasi.'])
                ->withInput();
        }

        // Cek is_confirmed khusus untuk pengusaha (user_role_id = 2)
        if ($user->user_role_id == 2 && !$user->is_confirmed) {
            return back()
                ->withErrors(['email' => 'Akun pengusaha Anda belum dikonfirmasi oleh admin.'])
                ->withInput();
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

        return redirect()->route('showAdminpage')->with('success', 'Login Sebagai Super Admin!');
    } catch (\Exception $e) {
        return back()
            ->withErrors(['login' => 'Terjadi kesalahan: ' . $e->getMessage()])
            ->withInput();
    }
}


}
    