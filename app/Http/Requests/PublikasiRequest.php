<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublikasiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tahun_akademik_id' => 'nullable|exists:tahun_akademiks,id',
            'pengajuan_id' => 'nullable|exists:pengajuans,id',
            'upload_laporan' => 'nullable|string',
            'link_dokumentasi' => 'nullable|url|max:2048',
            'link_desain' => 'nullable|url|max:2048',
            'keterangan_desain' => 'nullable|string|max:2000',
            'link_publikasi' => 'nullable',
            'link_publikasi.*' => 'nullable|url|max:2048',
            'dokumentasi_files' => 'nullable|array',
            'dokumentasi_files.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip|max:10240',
            'delete_dokumentasi_files' => 'nullable|array',
            'delete_dokumentasi_files.*' => 'nullable|string|max:500',
            'status_validasi' => 'nullable|in:disetujui,ditolak,perlu_revisi,menunggu_validasi',
            'catatan_validasi' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Custom validation messages in Indonesian.
     */
    public function messages(): array
    {
        return [
            'link_dokumentasi.url' => 'Format Link Dokumentasi harus berupa URL yang valid (diawali https:// atau http://).',
            'link_desain.url' => 'Format Link Desain harus berupa URL yang valid (diawali https:// atau http://).',
            'link_publikasi.*.url' => 'Format Link Publikasi harus berupa URL yang valid (diawali https:// atau http://).',
            'dokumentasi_files.*.mimes' => 'File dokumentasi harus berformat JPG, JPEG, PNG, GIF, WEBP, PDF, DOC, DOCX, atau ZIP.',
            'dokumentasi_files.*.max' => 'Ukuran setiap file dokumentasi maksimal 10 MB.',
        ];
    }
}
