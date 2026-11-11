<?php

namespace App\DataTables;

use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use App\Models\KotakMasukPengajuan;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class KotakMasukPengajuanDataTable extends DataTable
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
            ->addColumn('DT_RowIndex', '')
            ->addColumn('pengajuan.nama_kegiatan', function ($kotakMasukPengajuan) {
                $id = $kotakMasukPengajuan->pengajuan->id;
                return '<a href="' . route('pengajuan.edit', $id) . '" class="text-decoration-none text-success">'
                    . $kotakMasukPengajuan->pengajuan->nama_kegiatan . '</a>';
            })
            ->addColumn('pengajuan.status', function ($kotakMasukPengajuan) {
                $status = $kotakMasukPengajuan->pengajuan->status;

                if ($status === 'pending') {
                    return '<span class="badge bg-warning px-2 rounded-pill px-3 py-2">Pending <i class="fa-solid fa-spinner"></i></span>';
                } elseif ($status === 'diterima') {
                    $badge = '<span class="badge bg-success text-white px-2 rounded-pill px-3 py-2">Diterima <i class="fa-solid fa-check"></i></span>';
                    $pub = $kotakMasukPengajuan->pengajuan?->publikasi;
                    if ($pub) {
                        $target = !empty($pub->slug) ? $pub->slug : $pub->id;
                        $hasReport = !empty($pub->link_dokumentasi) || !empty($pub->link_publikasi) || !empty($pub->upload_laporan);
                        if ($pub->status_validasi === 'disetujui') {
                            $badge .= '<br><a href="' . route('publikasi.show', $target) . '" class="badge bg-success text-white mt-1 text-decoration-none" title="Laporan Telah Divalidasi Admin"><i class="fa-solid fa-circle-check me-1"></i> Laporan Valid</a>';
                        } elseif ($pub->status_validasi === 'perlu_revisi') {
                            $badge .= '<br><a href="' . route('publikasi.edit', $target) . '" class="badge bg-danger text-white mt-1 text-decoration-none" title="Laporan Perlu Revisi: ' . e($pub->catatan_validasi ?? '') . '"><i class="fa-solid fa-triangle-exclamation me-1"></i> Perlu Revisi</a>';
                        } elseif ($pub->status_validasi === 'menunggu_validasi') {
                            $badge .= '<br><a href="' . route('publikasi.show', $target) . '" class="badge bg-warning text-dark mt-1 text-decoration-none" title="Menunggu Validasi Admin"><i class="fa-solid fa-clock me-1"></i> Menunggu Validasi</a>';
                        } elseif ($hasReport) {
                            $badge .= '<br><a href="' . route('publikasi.show', $target) . '" class="badge bg-info text-white mt-1 text-decoration-none" title="Lihat Laporan"><i class="fa-solid fa-check-double me-1"></i> Dilaporkan</a>';
                        } else {
                            $badge .= '<br><a href="' . route('publikasi.edit', $target) . '" class="badge bg-primary text-white mt-1 text-decoration-none" title="Laporkan Kegiatan"><i class="fa-solid fa-file-pen me-1"></i> Laporkan</a>';
                        }
                    }
                    return $badge;
                } elseif ($status === 'ditolak') {
                    return '<span class="badge bg-danger text-white px-2 rounded-pill px-3 py-2">Ditolak <i class="fa-solid fa-xmark"></i></span>';
                }

                return '<span class="badge bg-secondary text-white px-2 rounded-pill px-3 py-2">-</span>';
            })
            ->addColumn('pengajuan.petugas_ditugaskan', function ($kotakMasukPengajuan) {
                $pic = $kotakMasukPengajuan->pengajuan?->petugas_ditugaskan;
                if (!empty($pic)) {
                    return '<span class="badge bg-primary text-white px-2 py-1"><i class="fa-solid fa-user-check me-1"></i> ' . e($pic) . '</span>';
                }
                return '<span class="text-muted fst-italic">Belum ditugaskan</span>';
            })
            ->addColumn('pengajuan.tgl_pelaksanaan', function ($kotakMasukPengajuan) {
                Carbon::setLocale('id');
                $p = $kotakMasukPengajuan->pengajuan;
                if (!$p) {
                    return '<span class="text-muted">-</span>';
                }
                $start = $p->tgl_awal ? Carbon::parse($p->tgl_awal) : null;
                $end = $p->tgl_selesai ? Carbon::parse($p->tgl_selesai) : null;

                $startStr = $start ? $start->translatedFormat('d M Y') : '-';
                $endStr = $end ? $end->translatedFormat('d M Y') : '-';

                return '<div class="text-nowrap"><span class="text-muted small">Pelaksanaan:</span> <span class="fw-semibold">' . $startStr . '</span></div>'
                     . '<div class="text-nowrap"><span class="text-muted small">Selesai:</span> <span class="fw-semibold">' . $endStr . '</span></div>';
            })
            ->addColumn('pengajuan.tgl_awal', function ($kotakMasukPengajuan) {
                Carbon::setLocale('id');
                return Carbon::parse($kotakMasukPengajuan->pengajuan->tgl_awal)->translatedFormat('l, d F Y');
            })
            ->addColumn('pengajuan.tgl_selesai', function ($kotakMasukPengajuan) {
                Carbon::setLocale('id');
                return Carbon::parse($kotakMasukPengajuan->pengajuan->tgl_selesai)->translatedFormat('l, d F Y');
            })
            ->addColumn('pengajuan.user_id', function ($kotakMasukPengajuan) {
                return $kotakMasukPengajuan->pengajuan->user->name;
            })
            ->filter(function ($query) {
                if (request()->has('search') && request()->input('search')['value']) {
                    $search = request()->input('search')['value'];
                    $query->whereHas('pengajuan', function ($q) use ($search) {
                        $q->where(function ($sub) use ($search) {
                            $sub->where('nama_kegiatan', 'like', "%$search%")
                                ->orWhere('tgl_awal', 'like', "%$search%")
                                ->orWhere('tgl_selesai', 'like', "%$search%")
                                ->orWhere('status', 'like', "%$search%")
                                ->orWhere('petugas_ditugaskan', 'like', "%$search%")
                                ->orWhereHas('user', function ($uq) use ($search) {
                                    $uq->where('name', 'like', "%$search%");
                                });
                        });
                    });
                }
            })
            ->rawColumns(['pengajuan.nama_kegiatan', 'pengajuan.tgl_pelaksanaan', 'pengajuan.tgl_awal', 'pengajuan.tgl_selesai', 'pengajuan.status', 'pengajuan.petugas_ditugaskan', 'pengajuan.user_id'])
            ->setRowId('DT_RowIndex');
    }



    /**
     * Get the query source of dataTable.
     */
    public function query(KotakMasukPengajuan $model): QueryBuilder
    {
        $status = request('status');
        $user = auth()->user();

        // Start a query to get the KotakMasukPengajuan data
        $query = $model->newQuery()
            ->select('kotak_masuk_pengajuans.*')
            ->with(['pengajuan.user', 'pengajuan.publikasi'])
            ->orderBy('kotak_masuk_pengajuans.created_at', 'desc');

        // Jika Petugas Humas (bukan Super Admin), HANYA tampilkan data pengajuan yang ditugaskan kepada dirinya sebagai PIC
        if (!empty($user->is_humas) && empty($user->is_admin)) {
            $query->whereHas('pengajuan', function ($q) use ($user) {
                $q->assignedToUser($user);
            });
        }

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            $query->whereHas('pengajuan', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pengajuan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(2, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => false,
                'autoWidth' => false,
                'responsive' => false,
                'searching' => true,
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
        $columns = [
            Column::make('DT_RowIndex')
                ->title('NO')
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false)
                ->width('4%'),
            Column::make('pengajuan.nama_kegiatan')
                ->title('NAMA KEGIATAN')
                ->addClass('text-start'),
            Column::make('pengajuan.tgl_pelaksanaan')
                ->title('TANGGAL')
                ->addClass('text-start')
                ->orderable(false)
                ->searchable(false)
                ->width('16%'),
            Column::make('pengajuan.status')
                ->title('STATUS PENGAJUAN')
                ->addClass('text-center')
                ->width('14%'),
        ];

        // Hanya Super Admin yang melihat kolom Petugas / PIC (disembunyikan untuk Humas)
        if (auth()->check() && auth()->user()->is_admin) {
            $columns[] = Column::make('pengajuan.petugas_ditugaskan')
                ->title('Petugas / PIC')
                ->addClass('text-center')
                ->width('14%');
        }

        $columns[] = Column::make('pengajuan.user_id')
            ->title('SUBMIT PENGGUNA')
            ->addClass('text-start')
            ->width('13%');

        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'KotakMasukPengajuan_' . date('YmdHis');
    }
}
