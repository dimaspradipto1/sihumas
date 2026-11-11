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
        $query = $model->newQuery()
            ->with([
                'user',
                'publikasi.tahunAkademik',
                'publikasi.pengajuan',
            ]);

        // Filter Tahun Akademik
        if (request()->filled('tahun_akademik')) {
            $ta = request('tahun_akademik');
            $query->whereHas('publikasi', function ($q) use ($ta) {
                $q->where('tahun_akademik_id', $ta)
                  ->orWhereHas('tahunAkademik', fn($tq) => $tq->where('tahun_akademik', $ta));
            });
        }

        // Filter Bulan
        if (request()->filled('bulan')) {
            $b = request('bulan');
            $query->where(function ($q) use ($b) {
                $q->whereMonth('tgl_awal', $b)
                  ->orWhereHas('pengajuan', fn($pq) => $pq->whereMonth('tgl_awal', $b));
            });
        }

        // Filter Tahun
        if (request()->filled('tahun') && !request()->filled('tahun_akademik')) {
            $y = request('tahun');
            $query->where(function ($q) use ($y) {
                $q->whereYear('tgl_awal', $y)
                  ->orWhereHas('pengajuan', fn($pq) => $pq->whereYear('tgl_awal', $y));
            });
        }

        // Filter Rentang Tanggal
        if (request()->filled('tgl_awal') && request()->filled('tgl_selesai')) {
            $query->whereDate('tgl_awal', '>=', request('tgl_awal'))
                  ->whereDate('tgl_selesai', '<=', request('tgl_selesai'));
        } elseif (request()->filled('tgl_awal')) {
            $query->whereDate('tgl_awal', '>=', request('tgl_awal'));
        } elseif (request()->filled('tgl_selesai')) {
            $query->whereDate('tgl_selesai', '<=', request('tgl_selesai'));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('laporanpublikasi-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('laporan-publikasi.index'))
            ->parameters([
                'scrollX' => true,
                'autoWidth' => false,
                'searching' => true,
                'searchDelay' => 500,
                'ordering' => true,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']],
                'order' => [[1, 'asc']],
                'columnDefs' => [
                    [
                        'targets' => 3,
                        'render' => 'function(data, type, row, meta) { return "<div style=\'word-wrap: break-word; word-break: break-word; white-space: normal; overflow-wrap: break-word; min-width: 220px;\' >" + data + "</div>"; }'
                    ],
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
            Column::computed('DT_RowIndex')->title('No')->addClass('text-center')->width(50),
            Column::make('tahun_akademik')->title('Tahun Akademik')->width(160),
            Column::make('submit_pengguna')->title('Pengguna')->width(150),
            Column::make('nama_kegiatan')->title('Nama Kegiatan')->width(280),
            Column::make('tgl_awal')->title('Tanggal Awal')->width(170),
            Column::make('tgl_selesai')->title('Tanggal Selesai')->width(170),
            Column::make('deskripsi_kegiatan')->title('Deskripsi Kegiatan')->width(300),
        ];
    }

    protected function filename(): string
    {
        return 'LaporanPublikasi_' . date('YmdHis');
    }
}
