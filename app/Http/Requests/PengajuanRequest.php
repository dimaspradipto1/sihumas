<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class PengajuanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'tahun_akademik_id' => 'required|exists:tahun_akademiks,id',
            'nama_kegiatan' => 'required|string|max:255',
            'tgl_awal' => 'required|date',
            'tgl_selesai' => 'required|date',
            'jam_kegiatan' => 'required|string|max:255',
            'waktu_selesai' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'nullable|string',
            'perlengkapan' => 'nullable|string',
            'link_zoom' => 'nullable|string',
            'unit_kegiatan' => 'nullable|string|max:255',
            'tempat_kegiatan' => 'required|string|max:255',
            'alasan_ditolak' => 'nullable|string',
            'petugas_ditugaskan' => 'nullable|string|max:255',
            'no_wa_tujuan' => 'nullable|string|max:255',
            'pic_petugas' => 'nullable|array',
            'pic_petugas.*.nama' => 'nullable|string|max:255',
            'pic_petugas.*.no_wa' => 'nullable|string|max:30',
            'pic_nama' => 'nullable|array',
            'pic_nama.*' => 'nullable|string|max:255',
            'pic_no_wa' => 'nullable|array',
            'pic_no_wa.*' => 'nullable|string|max:30',
            'email_tujuan' => 'nullable|email',
            'upload_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:10240',
            'upload_png' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:5120',
        ];
    }
}
