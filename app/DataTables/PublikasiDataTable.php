<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Publikasi;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PublikasiDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('nama_kegiatan', function ($publikasi) {
                if (!$publikasi->pengajuan) {
                    return '-';
                }
                if (auth()->check() && (auth()->user()->is_admin || auth()->user()->is_humas)) {
                    return '<a href="' . route('pengajuan.edit', $publikasi->pengajuan->id) . '" class="text-decoration-none text-success fw-bold">' . e($publikasi->pengajuan->nama_kegiatan) . '</a>';
                }
                return '<span class="fw-semibold text-gray-800">' . e($publikasi->pengajuan->nama_kegiatan) . '</span>';
            })
            ->addColumn('pengajuan.nama_kegiatan', function ($publikasi) {
                if (!$publikasi->pengajuan) {
                    return '-';
                }
                if (auth()->check() && (auth()->user()->is_admin || auth()->user()->is_humas)) {
                    return '<a href="' . route('pengajuan.edit', $publikasi->pengajuan->id) . '" class="text-decoration-none text-success fw-bold">' . e($publikasi->pengajuan->nama_kegiatan) . '</a>';
                }
                return '<span class="fw-semibold text-gray-800">' . e($publikasi->pengajuan->nama_kegiatan) . '</span>';
            })
            ->addColumn('tgl_pelaksanaan', function ($publikasi) {
                Carbon::setLocale('id');
                $p = $publikasi->pengajuan;
                $tglAwal = $publikasi->tgl_awal ?? $p?->tgl_awal;
                $tglAkhir = $publikasi->tgl_akhir ?? $p?->tgl_selesai;

                $start = $tglAwal ? Carbon::parse($tglAwal) : null;
                $end = $tglAkhir ? Carbon::parse($tglAkhir) : null;

                $startStr = $start ? $start->translatedFormat('d M Y') : '-';
                $endStr = $end ? $end->translatedFormat('d M Y') : '-';

                $jamMulai = !empty($p?->jam_kegiatan) ? substr($p->jam_kegiatan, 0, 5) . ' WIB' : '';
                $jamSelesai = !empty($p?->waktu_selesai) ? substr($p->waktu_selesai, 0, 5) . ' WIB' : '';

                return '<div class="d-flex flex-column gap-1">'
                     . '  <div class="d-inline-flex align-items-center">'
                     . '    <span class="text-nowrap"><span class="text-muted small">Pelaksanaan:</span> <span class="fw-semibold">' . $startStr . '</span></span>'
                     . ($jamMulai ? '    <span class="badge bg-light text-primary border small ms-2"><i class="fa-regular fa-clock me-1"></i>' . $jamMulai . '</span>' : '')
                     . '  </div>'
                     . '  <div class="d-inline-flex align-items-center">'
                     . '    <span class="text-nowrap"><span class="text-muted small">Selesai:</span> <span class="fw-semibold">' . $endStr . '</span></span>'
                     . ($jamSelesai ? '    <span class="badge bg-light text-success border small ms-2"><i class="fa-regular fa-clock me-1"></i>' . $jamSelesai . '</span>' : '')
                     . '  </div>'
                     . '</div>';
            })
            ->addColumn('tgl_awal', function ($publikasi) {
                Carbon::setlocale('id');
                $tgl = $publikasi->tgl_awal ?? $publikasi->pengajuan?->tgl_awal;
                return $tgl ? Carbon::parse($tgl)->translatedFormat('l, d F Y') : '-';
            })
            ->addColumn('tgl_akhir', function ($publikasi) {
                Carbon::setLocale('id');
                $tgl = $publikasi->tgl_akhir ?? $publikasi->pengajuan?->tgl_selesai;
                return $tgl ? Carbon::parse($tgl)->translatedFormat('l, d F Y') : '-';
            })
            ->editColumn('pengajuan_id', function ($publikasi) {
                // Use the 'pengajuan' relation to fetch 'nama_kegiatan'
                return $publikasi->pengajuan ? $publikasi->pengajuan->nama_kegiatan : '-';
            })
            ->editColumn('upload_laporan', function ($publikasi) {
                if (empty($publikasi->upload_laporan)) {
                    return '<span class="text-muted small">-</span>';
                }
                $clean = strip_tags($publikasi->upload_laporan);
                if (filter_var($clean, FILTER_VALIDATE_URL)) {
                    return '<a href="' . e($clean) . '" target="_blank" class="text-success"><i class="fa-solid fa-eye me-1"></i> Buka Laporan</a>';
                }
                return '<span title="' . e($clean) . '">' . e(\Illuminate\Support\Str::limit($clean, 35)) . '</span>';
            })
            ->editColumn('tahun_akademik_id', function ($item) {
                return $item->tahunAkademik ? $item->tahunAkademik->tahun_akademik : '-';
            })
            ->addColumn('user_id', function ($publikasi) {
                if (auth()->user()->is_admin) {
                    return $publikasi->user?->name ?? '-';
                }
                return '';
            })
            ->editColumn('link_dokumentasi', function ($publikasi) {
                if (empty($publikasi->link_dokumentasi)) {
                    return '<span class="text-muted small">-</span>';
                }
                return '<a href="' . e($publikasi->link_dokumentasi) . '" target="_blank" class="btn btn-xs btn-outline-primary d-inline-flex align-items-center py-1 px-2" style="font-size: 11px;"><i class="fa-solid fa-folder-open me-1"></i> Buka Drive</a>';
            })
            ->editColumn('link_publikasi', function ($publikasi) {
                $links = $publikasi->link_publikasi_array;
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

            ->addColumn('status_validasi', function ($publikasi) {
                $status = $publikasi->status_validasi ?? 'belum_dilaporkan';
                if ($status === 'disetujui') {
                    return '<span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Disetujui</span>';
                } elseif ($status === 'menunggu_validasi') {
                    return '<span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi</span>';
                } elseif ($status === 'perlu_revisi') {
                    $catatan = !empty($publikasi->catatan_validasi) ? ' title="Catatan: ' . e($publikasi->catatan_validasi) . '"' : '';
                    return '<span class="badge bg-danger text-white px-2 py-1"' . $catatan . '><i class="fa-solid fa-triangle-exclamation me-1"></i> Perlu Revisi</span>';
                } elseif ($status === 'ditolak') {
                    $catatan = !empty($publikasi->catatan_validasi) ? ' title="Catatan: ' . e($publikasi->catatan_validasi) . '"' : '';
                    return '<span class="badge bg-danger text-white px-2 py-1"' . $catatan . '><i class="fa-solid fa-circle-xmark me-1"></i> Ditolak</span>';
                }
                return '<span class="badge bg-secondary text-white px-2 py-1"><i class="fa-solid fa-hourglass-start me-1"></i> Belum Dilaporkan</span>';
            })

            ->addColumn('action', function ($publikasi) {
                $target = !empty($publikasi->slug) ? $publikasi->slug : $publikasi->id;
                $html = '<div class="d-flex justify-content-center gap-1">';
                $html .= '<a href="' . route('publikasi.show', $target) . '" class="btn btn-sm btn-primary text-white px-2 py-1" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>';
                
                if (auth()->user()->is_admin) {
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

                // Hanya Admin & Humas yang bisa mengedit / mengisi laporan kegiatan
                if (auth()->user()->is_admin || auth()->user()->is_humas) {
                    $html .= '<a href="' . route('publikasi.edit', $target) . '" class="btn btn-sm btn-warning text-white px-2 py-1" title="Edit / Laporkan"><i class="fa-solid fa-pen-to-square"></i></a>';
                }

                if (auth()->user()->is_admin) {
                    $html .= '<form action="' . route('publikasi.destroy', $target) . '" method="POST" style="display: inline" class="form-action-delete">'
                           . csrf_field() . method_field('DELETE')
                           . '<button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Hapus"><i class="fa-solid fa-trash-can"></i></button></form>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['action', 'nama_kegiatan', 'pengajuan.nama_kegiatan', 'status_validasi', 'tgl_pelaksanaan', 'tgl_awal', 'tgl_akhir', 'upload_laporan', 'link_dokumentasi', 'link_publikasi', 'user_id', 'tahun_akademik_id', 'pengajuan_id'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Publikasi $model): QueryBuilder
    {
        $user = auth()->user();

        // 1. Super Admin melihat seluruh pelaporan
        if ($user && $user->is_admin) {
            return $model->newQuery()
                ->select('publikasis.*')
                ->with(['pengajuan.user', 'tahunAkademik', 'user'])
                ->orderBy('publikasis.created_at', 'desc');
        }

        // 2. Petugas Humas melihat pelaporan kegiatan yang ditugaskan kepadanya sebagai PIC
        if ($user && $user->is_humas) {
            return $model->newQuery()
                ->select('publikasis.*')
                ->with(['pengajuan.user', 'tahunAkademik', 'user'])
                ->where(function ($q) use ($user) {
                    $q->whereHas('pengajuan', function ($pq) use ($user) {
                        $pq->assignedToUser($user);
                    })->orWhere('publikasis.user_id', $user->id);
                })
                ->orderBy('publikasis.created_at', 'desc');
        }

        // 3. User Unit/Fakultas melihat pelaporan dari kegiatan yang diajukannya
        return $model->newQuery()
            ->select('publikasis.*')
            ->with(['pengajuan.user', 'tahunAkademik', 'user'])
            ->where(function ($q) use ($user) {
                $q->where('publikasis.user_id', $user->id)
                  ->orWhereHas('pengajuan', function ($pq) use ($user) {
                      $pq->where('user_id', $user->id);
                  });
            })
            ->orderBy('publikasis.created_at', 'desc');
    }

    /**
     * Optional method if you want to use the html builder.
     */

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('publikasi-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => false,
                'autoWidth' => false,
                'responsive' => false,
            ])
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $isAdmin = auth()->check() && auth()->user()->is_admin;

        return [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false)
                ->width('4%'),
            Column::make('nama_kegiatan')
                ->data('nama_kegiatan')
                ->name('pengajuan.nama_kegiatan')
                ->title('NAMA KEGIATAN'),
            Column::make('status_validasi')
                ->name('publikasis.status_validasi')
                ->title('STATUS VALIDASI')
                ->width('12%')
                ->addClass('text-center'),
            Column::make('tgl_pelaksanaan')
                ->title('TANGGAL')
                ->orderable(false)
                ->searchable(false)
                ->width('16%'),
            Column::make('link_dokumentasi')
                ->title('DOKUMENTASI')
                ->orderable(false)
                ->searchable(false)
                ->width('9%')
                ->addClass('text-center'),
            Column::make('link_publikasi')
                ->title('LINK PUBLIKASI')
                ->orderable(false)
                ->searchable(false)
                ->width('11%'),
            Column::make('user_id')
                ->name('publikasis.user_id')
                ->title('SUBMIT PENGGUNA')
                ->visible($isAdmin)
                ->width('12%'),
            Column::computed('action')
                ->title('AKSI')
                ->exportable(false)
                ->printable(false)
                ->width($isAdmin ? '12%' : '8%')
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Publikasi_' . date('YmdHis');
    }
}
