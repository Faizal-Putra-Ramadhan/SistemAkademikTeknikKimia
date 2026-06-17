<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TemplateController extends Controller
{
    /**
     * Tampilkan halaman manajemen template
     */
    public function index(): View
    {
        $templates = [
            [
                'name' => 'Template Risk Assessment',
                'filename' => 'template1.docx',
                'path' => 'templates/template1.docx',
                'exists' => file_exists(storage_path('app/templates/template1.docx')),
                'route' => 'admin.templates.upload-ra',
            ],
            [
                'name' => 'Template Bebas Lab',
                'filename' => 'bebas_lab.docx',
                'path' => 'templates/bebas_lab.docx',
                'exists' => file_exists(storage_path('app/templates/bebas_lab.docx')),
                'route' => 'admin.templates.upload-bebas-lab',
            ],
        ];

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Upload template Risk Assessment
     */
    public function uploadRATemplate(Request $request): RedirectResponse
    {
        $request->validate([
            'template' => 'required|file|mimes:docx|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('template')) {
            try {
                $file = $request->file('template');

                // Simpan file ke storage/app/templates/template1.docx menggunakan Storage facade
                Storage::disk('templates')->putFileAs('', $file, 'template1.docx');

                ActivityLog::create([
                    'user_name' => Auth::user()->Nama,
                    'action' => 'Update Template RA',
                    'description' => 'Memperbarui template Risk Assessment (template1.docx)',
                    'ip_address' => $request->ip(),
                ]);

                return back()->with('success', 'Template Risk Assessment berhasil diperbarui!');
            }
            catch (\Exception $e) {
                return back()->with('error', 'Gagal menyimpan file. Pastikan file "template1.docx" di server tidak sedang dibuka oleh aplikasi lain (seperti MS Word).');
            }
        }

        return back()->with('error', 'Gagal mengupload template.');
    }

    /**
     * Upload template Bebas Lab
     */
    public function uploadBebasLabTemplate(Request $request): RedirectResponse
    {
        $request->validate([
            'template' => 'required|file|mimes:docx|max:5120', // Max 5MB
        ]);

        if ($request->hasFile('template')) {
            try {
                $file = $request->file('template');

                // Simpan file ke storage/app/templates/bebas_lab.docx menggunakan Storage facade
                Storage::disk('templates')->putFileAs('', $file, 'bebas_lab.docx');

                ActivityLog::create([
                    'user_name' => Auth::user()->Nama,
                    'action' => 'Update Template Bebas Lab',
                    'description' => 'Memperbarui template Bebas Lab (bebas_lab.docx)',
                    'ip_address' => $request->ip(),
                ]);

                return back()->with('success', 'Template Bebas Lab berhasil diperbarui!');
            }
            catch (\Exception $e) {
                return back()->with('error', 'Gagal menyimpan file. Pastikan file "bebas_lab.docx" di server tidak sedang dibuka oleh aplikasi lain (seperti MS Word).');
            }
        }

        return back()->with('error', 'Gagal mengupload template.');
    }
}
