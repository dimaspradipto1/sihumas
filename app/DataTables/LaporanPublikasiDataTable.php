<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\LaporanPublikasi;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class LaporanPublikasiDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query))
        ->addIndexColumn()
        ->addColumn('tahun_akademik', fn ($row) =>
            $row->publikasi?->tahunAkademik?->tahun_akademik ?? '-'
        )
        ->addColumn('submit_pengguna', fn ($row) =>
            $row->user?->name ?? '-'
        )
        ->addColumn('nama_kegiatan', fn ($row) =>
            $row->publikasi?->pengajuan?->nama_kegiatan ?? '-'
        )
        ->addColumn('tgl_awal', function ($publikasi) {
            Carbon::setlocale('id');
            return Carbon::parse($publikasi->pengajuan->tgl_awal)->translatedFormat('l, d F Y');
        })
        ->addColumn('tgl_selesai', function ($publikasi) {
            Carbon::setLocale('id');
            return Carbon::parse($publikasi->pengajuan->tgl_selesai)->translatedFormat('l, d F Y');
        })
        ->addColumn('deskripsi_kegiatan', fn ($row) =>
            trim(strip_tags($row->publikasi?->pengajuan?->deskripsi_kegiatan ?? '')) ?: '-'
        )
        ->addColumn('link_zoom', fn ($row) =>
            $row->publikasi?->pengajuan?->link_zoom ?? '-'
        )
        ->addColumn('link_dokumen', fn ($row) =>
            $row->publikasi?->pengajuan?->link_dokumen ?? '-'
        )
        ->rawColumns(['nama_kegiatan', 'tgl_awal', 'tgl_selesai', 'deskripsi_kegiatan', 'link_zoom', 'link_dokumen'])
        ->setRowId('id')
        ->filter(function ($query) {
            $search = request()->get('search')['value'];
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('user.name', 'like', "%$search%")
                      ->orWhere('publikasi.pengajuan.nama_kegiatan', 'like', "%$search%")
                      ->orWhere('publikasi.pengajuan.deskripsi_kegiatan', 'like', "%$search%");
                });
            }
        });
}


   public function query(LaporanPublikasi $model): QueryBuilder
{
    return $model->newQuery()
        ->with([ // Pastikan relasi yang diperlukan dimuat
            'user', // Relasi dengan model User
            'publikasi.tahunAkademik',
            'publikasi.pengajuan',
        ]);
}


    public function html(): HtmlBuilder
{
    return $this->builder()
        ->setTableId('laporanpublikasi-table')
        ->columns($this->getColumns())
        ->minifiedAjax(route('laporan-publikasi.index'))
        ->parameters([
            'scrollX' => true,
            'searching' => true, // Aktifkan pencarian di DataTables
            'searchDelay' => 500, // Tambahkan delay pada pencarian untuk menghindari permintaan berulang
            'ordering' => true, // Aktifkan pengurutan di tabel
            'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']], // Pilihan panjang halaman
            'order' => [[1, 'asc']], // Pengurutan default pada kolom tertentu
            'columnDefs' => [
                [
                    'targets' => 3,
                    'width' => '300px',
                    'render' => 'function(data, type, row, meta) { return "<div style=\'word-wrap: break-word; word-break: break-word; white-space: normal; overflow-wrap: break-word;\' >" + data + "</div>"; }'
                ],
                [
                    'targets' => 0,
                    'width' => '5px',
                    'className' => 'text-start',
                    'render' => null
                ],
                [
                    'targets' => 4,
                    'width' => '10px',
                    'className' => 'text-left',
                    'style' => 'text-align: text-left;',
                    'render' => null
                ]
            ],
        ])
        ->buttons([
            Button::make('excel'),
            Button::make('csv'),
            Button::make('pdf'),
            Button::make('print'),
            Button::make('reset'),
            Button::make('reload'),
        ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')->title('No'),
            // Column::make('tahun_akademik')->title('Tahun Akademik'),
            Column::make('submit_pengguna')->title('Pengguna'),
            Column::make('nama_kegiatan')->title('Nama Kegiatan'),
            Column::make('tgl_awal')->title('Tanggal Awal'),
            Column::make('tgl_selesai')->title('Tanggal Selesai'),
            Column::make('deskripsi_kegiatan')->title('Deskripsi Kegiatan'),
        ];
    }

    protected function filename(): string
    {
        return 'LaporanPublikasi_' . date('YmdHis');
    }
}
