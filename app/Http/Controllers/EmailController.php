<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function send($id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);

        // Periksa apakah user sudah memverifikasi email
        if ($user->hasVerifiedEmail()) {
            return redirect()->back()->with('info', 'Email user sudah diverifikasi.');
        }

        // Kirim email verifikasi ke user
        $user->sendEmailVerificationNotification();

        // Tampilkan pesan sukses
        return redirect()->back()->with('success', 'Email verifikasi telah dikirim ke user.');
    }
}
