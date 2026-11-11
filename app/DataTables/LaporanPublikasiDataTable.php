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
            ->addColumn('nama_kegiatan', function ($row) {
                $pengajuan = $row->publikasi?->pengajuan ?? $row->pengajuan;
                if (!$pengajuan) {
                    return '-';
                }
                return '<a href="' . route('pengajuan.edit', $pengajuan->id) . '" class="text-decoration-none text-success fw-bold">' . e($pengajuan->nama_kegiatan) . '</a>';
            })
            ->addColumn('status_validasi', function ($row) {
                $status = $row->publikasi?->status_validasi ?? 'belum_dilaporkan';
                if ($status === 'disetujui') {
                    return '<span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Disetujui</span>';
                } elseif ($status === 'menunggu_validasi') {
                    return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi</span>';
                } elseif ($status === 'perlu_revisi') {
                    $catatan = !empty($row->publikasi?->catatan_validasi) ? ' title="Catatan: ' . e($row->publikasi->catatan_validasi) . '"' : '';
                    return '<span class="badge bg-danger text-white px-2 py-1"' . $catatan . '><i class="fa-solid fa-triangle-exclamation me-1"></i> Perlu Revisi</span>';
                } elseif ($status === 'ditolak') {
                    $catatan = !empty($row->publikasi?->catatan_validasi) ? ' title="Catatan: ' . e($row->publikasi->catatan_validasi) . '"' : '';
                    return '<span class="badge bg-danger text-white px-2 py-1"' . $catatan . '><i class="fa-solid fa-circle-xmark me-1"></i> Ditolak</span>';
                }
                return '<span class="badge bg-secondary text-white px-2 py-1"><i class="fa-solid fa-hourglass-start me-1"></i> Belum Dilaporkan</span>';
            })
            ->addColumn('tgl_pelaksanaan', function ($row) {
                Carbon::setLocale('id');
                $tglAwal = $row->tgl_awal ?? ($row->publikasi?->tgl_awal ?? $row->publikasi?->pengajuan?->tgl_awal ?? $row->pengajuan?->tgl_awal);
                $tglAkhir = $row->tgl_selesai ?? ($row->publikasi?->tgl_akhir ?? $row->publikasi?->pengajuan?->tgl_selesai ?? $row->pengajuan?->tgl_selesai);

                if (!$tglAwal && !$tglAkhir) {
                    return '<span class="text-muted">-</span>';
                }

                $start = $tglAwal ? Carbon::parse($tglAwal) : null;
                $end = $tglAkhir ? Carbon::parse($tglAkhir) : null;

                if ($start && $end) {
                    if ($start->isSameDay($end)) {
                        return '<span class="text-nowrap fw-semibold">' . $start->translatedFormat('d M Y') . '</span><br><small class="text-muted">' . $start->translatedFormat('l') . '</small>';
                    }
                    return '<span class="text-nowrap fw-semibold">' . $start->translatedFormat('d M Y') . '</span><br><small class="text-muted">s/d ' . $end->translatedFormat('d M Y') . '</small>';
                }

                return '<span class="text-nowrap fw-semibold">' . ($start ? $start->translatedFormat('d M Y') : $end->translatedFormat('d M Y')) . '</span>';
            })
            ->addColumn('tgl_awal', function ($row) {
                Carbon::setlocale('id');
                $tgl = $row->tgl_awal ?? ($row->publikasi?->tgl_awal ?? $row->publikasi?->pengajuan?->tgl_awal ?? $row->pengajuan?->tgl_awal);
                return $tgl ? Carbon::parse($tgl)->translatedFormat('l, d F Y') : '-';
            })
            ->addColumn('tgl_akhir', function ($row) {
                Carbon::setLocale('id');
                $tgl = $row->tgl_selesai ?? ($row->publikasi?->tgl_akhir ?? $row->publikasi?->pengajuan?->tgl_selesai ?? $row->pengajuan?->tgl_selesai);
                return $tgl ? Carbon::parse($tgl)->translatedFormat('l, d F Y') : '-';
            })
            ->addColumn('link_dokumentasi', function ($row) {
                $doc = $row->publikasi?->link_dokumentasi;
                if (empty($doc)) {
                    return '<span class="text-muted small">-</span>';
                }
                return '<a href="' . e($doc) . '" target="_blank" class="btn btn-xs btn-outline-primary d-inline-flex align-items-center py-1 px-2" style="font-size: 11px;"><i class="fa-solid fa-folder-open me-1"></i> Buka Drive</a>';
            })
            ->addColumn('link_publikasi', function ($row) {
                $links = $row->publikasi?->link_publikasi_array ?? [];
                if (empty($links)) {
                    return '<span class="text-muted small">-</span>';
                }
                $html = '<div class="d-flex flex-wrap gap-1">';
                foreach ($links as $idx => $link) {
                    $num = count($links) > 1 ? ' #' . ($idx + 1) : '';
                    $html .= '<a href="' . e($link) . '" target="_blank" class="btn btn-xs btn-outline-success d-inline-flex align-items-center py-1 px-2" style="font-size: 11px;" title="' . e($link) . '">'
                          . '<i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Link' . $num . '</a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->addColumn('user_id', function ($row) {
                if (auth()->check() && auth()->user()->is_admin) {
                    return $row->publikasi?->user?->name ?? ($row->user?->name ?? '-');
                }
                return '';
            })
            ->addColumn('action', function ($row) {
                $pub = $row->publikasi;
                if (!$pub) {
                    return '<span class="text-muted small">-</span>';
                }
                $target = !empty($pub->slug) ? $pub->slug : $pub->id;
                $html = '<div class="d-flex justify-content-center gap-1">';
                $html .= '<a href="' . route('publikasi.show', $target) . '" class="btn btn-sm btn-primary text-white px-2 py-1" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>';

                if (auth()->check() && auth()->user()->is_admin) {
                    $actionUrl = route('publikasi.validasi', $target);

                    // 1. Tombol Validasi / Setujui Langsung (SweetAlert)
                    $html .= '<form action="' . $actionUrl . '" method="POST" style="display: inline" class="form-validasi-setujui">'
                           . csrf_field()
                           . '<input type="hidden" name="status_validasi" value="disetujui">'
                           . '<button type="submit" class="btn btn-sm btn-success text-white px-2 py-1" title="Validasi / Setujui Laporan">'
                           . '<i class="fa-solid fa-clipboard-check"></i></button></form>';

                    // 2. Tombol Menolak Validasi Langsung (SweetAlert)
                    $html .= '<form action="' . $actionUrl . '" method="POST" style="display: inline" class="form-validasi-tolak">'
                           . csrf_field()
                           . '<input type="hidden" name="status_validasi" value="ditolak">'
                           . '<input type="hidden" name="catatan_validasi" value="">'
                           . '<button type="submit" class="btn btn-sm btn-danger text-white px-2 py-1" title="Tolak Validasi">'
                           . '<i class="fa-solid fa-clipboard-xmark"></i></button></form>';
                }

                $html .= '<a href="' . route('publikasi.edit', $target) . '" class="btn btn-sm btn-warning text-white px-2 py-1" title="Edit / Laporkan"><i class="fa-solid fa-pen-to-square"></i></a>';

                if (auth()->check() && auth()->user()->is_admin) {
                    $html .= '<form action="' . route('publikasi.destroy', $target) . '" method="POST" style="display: inline" class="form-action-delete">'
                           . csrf_field() . method_field('DELETE')
                           . '<button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Hapus"><i class="fa-solid fa-trash-can"></i></button></form>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action', 'nama_kegiatan', 'status_validasi', 'tgl_pelaksanaan', 'tgl_awal', 'tgl_akhir', 'link_dokumentasi', 'link_publikasi', 'user_id'])
            ->setRowId('id')
            ->filter(function ($query) {
                $search = request()->get('search')['value'] ?? null;
                if ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%$search%"))
                          ->orWhereHas('publikasi.user', fn($uq) => $uq->where('name', 'like', "%$search%"))
                          ->orWhereHas('publikasi.pengajuan', fn($pq) => $pq->where('nama_kegiatan', 'like', "%$search%"))
                          ->orWhereHas('pengajuan', fn($pq) => $pq->where('nama_kegiatan', 'like', "%$search%"));
                    });
                }
            });
    }

    public function query(LaporanPublikasi $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->select('laporan_publikasis.*')
            ->with([
                'user',
                'publikasi.tahunAkademik',
                'publikasi.pengajuan',
                'publikasi.user',
                'pengajuan',
            ])
            ->orderBy('laporan_publikasis.id', 'desc');

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
                $q->whereMonth('laporan_publikasis.tgl_awal', $b)
                  ->orWhereHas('pengajuan', fn($pq) => $pq->whereMonth('tgl_awal', $b));
            });
        }

        // Filter Tahun
        if (request()->filled('tahun') && !request()->filled('tahun_akademik')) {
            $y = request('tahun');
            $query->where(function ($q) use ($y) {
                $q->whereYear('laporan_publikasis.tgl_awal', $y)
                  ->orWhereHas('pengajuan', fn($pq) => $pq->whereYear('tgl_awal', $y));
            });
        }

        // Filter Rentang Tanggal
        if (request()->filled('tgl_awal') && request()->filled('tgl_selesai')) {
            $query->whereDate('laporan_publikasis.tgl_awal', '>=', request('tgl_awal'))
                  ->whereDate('laporan_publikasis.tgl_selesai', '<=', request('tgl_selesai'));
        } elseif (request()->filled('tgl_awal')) {
            $query->whereDate('laporan_publikasis.tgl_awal', '>=', request('tgl_awal'));
        } elseif (request()->filled('tgl_selesai')) {
            $query->whereDate('laporan_publikasis.tgl_selesai', '<=', request('tgl_selesai'));
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
                'scrollX' => false,
                'autoWidth' => false,
                'responsive' => false,
                'searching' => true,
                'searchDelay' => 500,
                'ordering' => true,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'All']],
                'order' => [[1, 'asc']],
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
        $isAdmin = auth()->check() && auth()->user()->is_admin;

        return [
            Column::make('DT_RowIndex')->title('NO')->addClass('text-center')->orderable(false)->searchable(false)->width('4%'),
            Column::make('nama_kegiatan')->data('nama_kegiatan')->name('pengajuan.nama_kegiatan')->title('NAMA KEGIATAN'),
            Column::make('status_validasi')->name('publikasi.status_validasi')->title('STATUS VALIDASI')->width('12%')->addClass('text-center'),
            Column::make('tgl_pelaksanaan')->title('TANGGAL PELAKSANAAN')->orderable(false)->searchable(false)->width('14%'),
            Column::make('link_dokumentasi')->title('DOKUMENTASI')->orderable(false)->searchable(false)->width('9%')->addClass('text-center'),
            Column::make('link_publikasi')->title('LINK PUBLIKASI')->orderable(false)->searchable(false)->width('11%'),
            Column::make('user_id')->name('user.name')->title('SUBMIT PENGGUNA')->visible($isAdmin)->width('12%'),
            Column::computed('action')->title('AKSI')->exportable(false)->printable(false)->width($isAdmin ? '12%' : '8%')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'LaporanPublikasi_' . date('YmdHis');
    }
}
