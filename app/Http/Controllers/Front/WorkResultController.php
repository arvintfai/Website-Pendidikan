<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\work_results;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Mews\Purifier\Facades\Purifier;

class WorkResultController extends Controller
{
    public function index()
    {
        // $works = work_results::with('User')->get();
        return view('front.work_results');
    }

    /**
     * @param Request $request
     *
     * @return [type]
     */
    public function store(Request $request)
    {
        // --- Bagian Validasi Form ---
        $request->validate(
            [
                'title' => 'required|string|min:3|max:255|unique:work_results,title,id', // Judul harus diisi, string, min 3 karakter, maks 255 karakter
                'paragraph' => 'required|string|min:10', // Deskripsi harus diisi, string, min 10 karakter
                'image' => 'required|image|mimes:jpeg,png,gif,webp|max:5120', // Opsional, harus gambar, format tertentu, maks 5MB
                'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:20480', // Opsional, harus file, format tertentu, maks 20MB
            ],
            // --- Pesan Error Kustom (Opsional) ---
            [
                'title.required' => 'Judul karya wajib diisi.',
                'title.string' => 'Judul harus berupa teks.',
                'title.min' => 'Judul minimal 3 karakter.',
                'title.max' => 'Judul maksimal 255 karakter.',
                'title.unique' => 'Judul sudah digunakan.',

                'paragraph.required' => 'Deskripsi karya wajib diisi.',
                'paragraph.string' => 'Deskripsi harus berupa teks.',
                'paragraph.min' => 'Deskripsi minimal 10 karakter.',

                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar yang diizinkan adalah JPEG, PNG, GIF, atau WEBP.',
                'image.max' => 'Ukuran gambar maksimal 5MB.',

                'video.file' => 'File harus berupa video.',
                'video.mimes' => 'Format video yang diizinkan adalah MP4, MOV, AVI, atau WMV.',
                'video.max' => 'Ukuran video maksimal 20MB.',
            ]
        );
        // --- Akhir Bagian Validasi ---

        // Jika validasi gagal, Laravel akan secara otomatis mengembalikan pengguna
        // ke halaman sebelumnya dengan error dan input yang dipertahankan (old input).
        // Jika validasi berhasil, kode di bawah ini akan dieksekusi.

        $photoPath = null;
        if ($request->hasFile('image')) {
            $photoPath = $request->file('image')->store('works/images', 'public');
            $photoPath = Storage::url($photoPath);
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('works/videos', 'public');
            $videoPath = Storage::url($videoPath);
        }

        // Sanitasi HTML dari description sebelum disimpan
        // Ini sangat penting jika Anda menggunakan rich text editor
        $cleanedDescription = Purifier::clean($request->paragraph);

        work_results::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'slug' => strtolower(str_replace(' ', '-', $request->title)),
            'paragraph' => $cleanedDescription,
            'photo_path' => $photoPath,
            'video_path' => $videoPath,
        ]);

        return redirect()->back()->with('success', 'Karya berhasil diupload! Silahkan tunggu karya disetujui oleh Guru');
    }

    /**
     * @param mixed $slug
     *
     * @return [type]
     */
    public function show($slug)
    {
        $data = work_results::where('slug', $slug)->where('showAtFront', true)
            ->firstOrFail();
        return view('front.work_result', compact('data'));
    }

    public function destroy($slug)
    {
        $work = work_results::where('user_id', Auth::id())
            ->where('slug', $slug)
            ->firstOrFail();

        // dd($work->photo_path);

        if (Storage::disk('public')->exists(str_replace('/storage', '', $work->photo_path)))
            Storage::disk('public')->delete(str_replace('/storage', '', $work->photo_path));


        try {
            $work->delete();
            return redirect()->route('index')->with('success', 'Karya berhasil dihapus.');
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode === 19)
                return redirect()->route('index')->with('danger', 'Karya gagal dihapus.');
        }
    }
}
