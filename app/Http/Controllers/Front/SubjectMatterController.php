<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Assigment;
use App\Models\SubjectMatter;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubjectMatterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $name = Str::headline(Str::replace('-', ' ', $slug));
        $data = SubjectMatter::whereLike('name', $name)->first();
        $assigment = Assigment::where('subject_matter_id', $data->id)->where('user_id', auth()->user()->id)->first(['file_name', 'id']);
        $user = auth()->user();
        if (!$user->wasOpened->contains($data->id))
            $user->wasOpened()->attach($data->id, ['opened_at' => now()]);
        if ($data->is_has_assigment)
            return view('front.subject-matter', ['data' => $data, 'assigment' => $assigment]);
        return view('front.subject-matter', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subjectMatter = SubjectMatter::where('id', $request->assigment)->first('due_to');
        if (now() >= $subjectMatter->due_to)
            return redirect()->back()->with('error', 'Batas waktu pengumpulan sudah lewat!');

        $validated = $request->validate([
            'pdfFile' => 'required|file|mimes:pdf',
        ]);

        // 💾 Simpan file ke storage/app/public/assigment
        $file = $request->file('pdfFile');
        $file_name = $file->getClientOriginalName();
        $filePath = $file->storeAs('assigment', $file_name, 'public');

        // 🧾 Simpan data ke database
        $document = Assigment::create([
            'user_id' => auth()->user()->id,
            'subject_matter_id' => $request->assigment,
            'file_name' => $filePath,
            'scores' => 0,
        ]);

        try {
            $document;
            return redirect()->back()->with('success', 'Pengumpulan tugas berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Terjadi error:' . $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SubjectMatter $subjectMatter)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubjectMatter $subjectMatter)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'pdfFile' => 'required|file|mimes:pdf',
        ]);

        $assigment = Assigment::findOrFail($id);

        try {
            if ($assigment->file_name)
                Storage::disk('public')->delete($assigment->file_name);


            $file = $request->file('pdfFile');
            $file_name = $file->getClientOriginalName();
            $filePath = $file->storeAs('assigment', $file_name, 'public');
            $assigment->file_name = $filePath;
            $assigment->save();

            return redirect()->back()->with('success', 'Tugas berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Terjadi error:' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubjectMatter $subjectMatter)
    {
        //
    }
}
