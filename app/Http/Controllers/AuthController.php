<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
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


    // public function insertRegister(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'username' => 'required|string|max:255|unique:users,username',
    //         'email' => 'required|email|max:255|unique:users,email',
    //         'phone' => 'required|string|max:20',
    //         'password' => 'required|string|min:8|confirmed',
    //         'user_type_id' => 'required|integer|exists:user_types,id'
    //     ]);

    //     // Hash password sebelum dikirim ke Stored Procedure
    //     $hashedPassword = Hash::make($request->password);

    //     // Buat JSON data
    //     $dataUser = json_encode([
    //         "name" => $request->name,
    //         "username" => $request->username,
    //         "email" => $request->email,
    //         "phone" => $request->phone,
    //         "password" => $hashedPassword,
    //         "user_type_id" => $request->user_type_id
    //     ]);

    //     // Panggil Stored Procedure
    //     DB::statement("CALL store_registerUser(?)", [$dataUser]);

    //     return response()->json(['message' => 'User registered successfully'], 201);
    // }

    // public function insertRegister(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'username' => 'required|string|unique:users,username',
    //         'email' => 'required|email|unique:users,email',
    //         'phone' => 'nullable|string',
    //         'password' => 'required|string|min:6',
    //         'userType_id' => 'required|exists:user_types,userType_id',
    //         'role_id' => 'required|exists:user_roles,role_id', // Sesuai tabel user_roles
    //     ]);

    //     // Hash password
    //     $hashedPassword = Hash::make($request->password);

    //     // Buat JSON untuk dikirim ke SP
    //     $userData = json_encode([
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => $hashedPassword,
    //         'userType_id' => $request->userType_id,
    //         'role_id' => $request->role_id,
    //     ]);

    //     // Panggil Stored Procedure untuk menyimpan user
    //     $userId = DB::select("CALL sp_register_user(?)", [$userData]);

    //     // **Pastikan user sudah dibuat sebelum lanjut ke Spatie**
    //     $user = User::find($userId[0]->user_id);
    //     if (!$user) {
    //         return response()->json(['message' => 'Gagal membuat user'], 500);
    //     }

    //     // **Gunakan Spatie untuk assign role agar sesuai dengan user_roles**
    //     $role = Role::where('id', $request->role_id)->first();
    //     if ($role) {
    //         $user->assignRole($role->name);
    //     }

    //     return response()->json([
    //         'message' => 'User registered successfully',
    //         'user_id' => $userId[0]->user_id
    //     ], 201);
    // }

    // public function insertRegister(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'name' => 'required|string',
    //         'username' => 'required|string|unique:users,username',
    //         'email' => 'required|email|unique:users,email',
    //         'phone' => 'required|string',
    //         'password' => 'required|string|min:6|confirmed',
    //         'user_type_id' => 'required|exists:user_types,id',
    //         'user_role_id' => 'required|exists:user_roles,id',
    //     ]);

    //     // Hash password sebelum dikirim ke SP
    //     $hashedPassword = Hash::make($request->password);

    //     // Format data dalam JSON untuk dikirim ke Stored Procedure
    //     $dataUser = json_encode([
    //         'name' => $request->name,
    //         'username' => $request->username,
    //         'email' => $request->email,
    //         'phone' => $request->phone,
    //         'password' => $hashedPassword,
    //         'user_type_id' => $request->user_type_id,
    //         'user_role_id' => $request->user_role_id
    //     ]);

    //     // Panggil Stored Procedure
    //     $result = DB::select("CALL store_registerUser(?)", [$dataUser]);

    //     // Pastikan hasil SP tidak kosong
    //     if (empty($result) || !isset($result[0]->user_id)) {
    //         return response()->json([
    //             'message' => 'Gagal mendaftarkan user'
    //         ], 500);
    //     }

    //     return response()->json([
    //         'message' => 'User registered successfully',
    //         'user_id' => $result[0]->user_id
    //     ], 201);
    // }

    private function sendVerificationEmail($email, $userId, $token)
    {
        $verificationUrl = route('email.verify', ['id' => $userId, 'token' => $token]);

        Mail::raw("Klik link berikut untuk verifikasi email Anda: $verificationUrl", function ($message) use ($email) {
            $message->to($email)
                ->subject('Verifikasi Email');
        });
    }


    public function insertRegister(Request $request)
{
    // Validasi input
    $request->validate([
        'name' => 'required|string',
        'username' => 'required|string|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string',
        'password' => 'required|string|min:6|confirmed',
        'user_type_id' => 'required|exists:user_types,id',
        'user_role_id' => 'required|exists:user_roles,id',
    ]);

    // Hash password sebelum dikirim ke SP
    $hashedPassword = Hash::make($request->password);

    // Buat token verifikasi (hash dari email)
    $verificationToken = sha1($request->email);

    // Format data dalam JSON untuk dikirim ke Stored Procedure
    $dataUser = json_encode([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => $hashedPassword,
        'user_type_id' => $request->user_type_id,
        'user_role_id' => $request->user_role_id,
        'email_verified_at' => null, // Belum diverifikasi
        'verification_token' => $verificationToken // Simpan token ke database
    ]);

    // Panggil Stored Procedure
    $result = DB::select("CALL store_registerUser(?)", [$dataUser]);

    // Pastikan hasil SP tidak kosong
    if (empty($result) || !isset($result[0]->user_id)) {
        return response()->json([
            'message' => 'Gagal mendaftarkan user'
        ], 500);
    }

    // Kirim email verifikasi
    $this->sendVerificationEmail($request->email, $result[0]->user_id, $verificationToken);

    return response()->json([
        'message' => 'User registered successfully. Please check your email for verification.',
        'user_id' => $result[0]->user_id
    ], 201);
}

    public function login(Request $request)
    {
        // **1. Validasi Input**
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // **2. Panggil Stored Procedure untuk mencari user berdasarkan email**
            $userData = DB::select("CALL login_user(?)", [$request->email]);

            // **3. Periksa apakah user ditemukan**
            if (empty($userData)) {
                return redirect()->route('Login')->with('error', 'Email tidak ditemukan.');
            }

            // **4. Ambil data user dari hasil SP**
            $user = (object) $userData[0];

            // **5. Verifikasi Password (Laravel)**
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->route('login')->with('error', 'Password salah.');
            }

            // **6. Login User ke Laravel**
            $loggedInUser = User::find($user->id);
            Auth::login($loggedInUser);

            // **7. Cek Role User dan Redirect Sesuai Role**
            if ($user->user_role_id == 3) {
                return redirect()->route('landingpage')->with('message', 'Login berhasil!');
            } elseif ($user->user_role_id == 2) {
                return redirect()->route('owner.dashboard')->with('message', 'Login sebagai Owner!');
            }

            // Jika role tidak dikenali, arahkan ke halaman default
            return redirect()->route('dashboard')->with('message', 'Login berhasil!');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('landingpage')->with('message', 'Anda telah logout.');
    }

    public function insertUserType(Request $request)
    {
        $dataUserType = json_encode([
            'userType_name' => $request->userType_name,
            'description'   => $request->description
        ]);

        $response = DB::statement('CALL insert_user_type(?)', [$dataUserType]);

        if ($response) {
            return response()->json(['message' => 'User Type berhasil ditambahkan'], 201);
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
            return response()->json(['message' => 'User Role berhasil ditambahkan'], 201);
        } else {
            return response()->json(['error' => 'Gagal menambahkan User Role'], 500);
        }
    }
    
}
