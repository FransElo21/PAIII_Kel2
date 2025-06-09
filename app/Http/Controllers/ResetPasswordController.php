<?php

// app/Http/Controllers/ManualResetController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordController extends Controller
{
    // 1. Form untuk memasukkan email
    public function showForgotForm()
    {
        return view('LupaPassword');
    }

    // 2. Proses buat token & kirim email
    public function sendToken(Request $request)
    {
        $request->validate(['email'=>'required|email']);
        $user = User::where('email',$request->email)->first();
        if (!$user) {
            return back()->withErrors(['email'=>'Email tidak terdaftar']);
        }

        // generate token
        $token = Str::random(64);
        DB::table('password_resets_manual')->insert([
            'user_id'    => $user->id,
            'token'      => hash('sha256',$token),
            'expires_at' => Carbon::now()->addHour(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // kirim email
        $link = route('password.reset', $token);
        Mail::send('emails.manual-reset', compact('link','user'), function($m) use($user){
            $m->to($user->email)->subject('Reset Password Anda');
        });

        return back()->with('status','Link reset telah dikirim ke email Anda.');
    }

    // 3. Form reset (token ada di URL)
    public function showResetForm($token)
    {
        return view('manual-reset', compact('token'));
    }

    // 4. Proses ganti password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $hashed = hash('sha256',$request->token);
        $record = DB::table('password_resets_manual')
                    ->where('token',$hashed)
                    ->where('expires_at','>', now())
                    ->first();

        if (!$record) {
            return back()->withErrors(['token'=>'Token tidak valid atau sudah kadaluarsa.']);
        }

        // update password
        User::find($record->user_id)
            ->update(['password'=>Hash::make($request->password)]);

        // hapus token
        DB::table('password_resets_manual')
          ->where('id',$record->id)
          ->delete();

        return redirect()->route('login')
                         ->with('success','Password berhasil diubah.');
    }
}
