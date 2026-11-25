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
                $pengajuan = $kotakMasukPengajuan->pengajuan;
                $picList = is_array($pengajuan?->pic_petugas) ? $pengajuan->pic_petugas : [];

                if (!empty($picList)) {
                    $namesByRole = [
                        'dokumentasi' => [],
                        'desain' => [],
                        'publikasi' => [],
                        'general' => [],
                    ];
                    foreach ($picList as $item) {
                        $nama = trim((string) ($item['nama'] ?? $item['name'] ?? ''));
                        if ($nama === '') {
                            continue;
                        }
                        $peran = strtolower(trim((string) ($item['peran'] ?? 'desain')));
                        if (!isset($namesByRole[$peran])) {
                            $peran = 'general';
                        }
                        $namesByRole[$peran][] = $nama;
                    }

                    $groups = [
                        ['label' => 'PIC DOKUMENTASI:', 'names' => $namesByRole['dokumentasi']],
                        ['label' => 'PIC DESIGN:', 'names' => $namesByRole['desain']],
                        ['label' => 'PIC PUBLIKASI:', 'names' => $namesByRole['publikasi']],
                        ['label' => 'PIC GENERAL:', 'names' => $namesByRole['general']],
                    ];

                    $hasAnyPic = !empty(array_filter($groups, fn($g) => !empty($g['names'])));
                    if ($hasAnyPic) {
                        $html = '<div class="d-flex flex-column align-items-start gap-1 text-start text-gray-800" style="font-size: 11px; line-height: 1.4;">';
                        foreach ($groups as $group) {
                            if (empty($group['names'])) {
                                continue;
                            }
                            $html .= '<div>'
                                   . '<div class="fw-bold text-gray-900" style="font-size: 11px; font-weight: 700; line-height: 1.4;"><strong>' . $group['label'] . '</strong></div>';
                            if (count($group['names']) > 1) {
                                $html .= '<ol class="mb-0 ps-3" style="font-size: 11px; line-height: 1.4;">';
                                foreach ($group['names'] as $name) {
                                    $html .= '<li style="font-size: 11px; line-height: 1.4;">' . e($name) . '</li>';
                                }
                                $html .= '</ol>';
                            } else {
                                $html .= '<div style="font-size: 11px; line-height: 1.4;">' . e($group['names'][0]) . '</div>';
                            }
                            $html .= '</div>';
                        }
                        $html .= '</div>';
                        return $html;
                    }
                }

                $pic = $pengajuan?->petugas_ditugaskan;
                if (!empty($pic)) {
                    return '<span class="text-gray-800" style="font-size: 11px; line-height: 1.4; white-space: normal;">' . e($pic) . '</span>';
                }
                return '<span class="text-muted fst-italic" style="font-size: 11px; line-height: 1.4;">Belum ditugaskan</span>';
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

                $jamMulai = !empty($p->jam_kegiatan) ? substr($p->jam_kegiatan, 0, 5) . ' WIB' : '';
                $jamSelesai = !empty($p->waktu_selesai) ? substr($p->waktu_selesai, 0, 5) . ' WIB' : '';

                if (in_array($p->nama_kegiatan, ['Desain', 'Publikasi', 'Desain dan Publikasi'], true) && empty($jamMulai)) {
                    if ($p->nama_kegiatan === 'Desain dan Publikasi') {
                        return '<div class="d-flex flex-column gap-1">'
                             . '  <div class="d-inline-flex align-items-center">'
                             . '    <span class="text-nowrap"><span class="text-muted small">Deadline Desain:</span> <span class="fw-semibold">' . $startStr . '</span></span>'
                             . '    <span class="badge bg-light text-primary border small ms-2"><i class="fa-solid fa-layer-group me-1"></i>Desain &amp; Publikasi</span>'
                             . '  </div>'
                             . '  <div class="d-inline-flex align-items-center">'
                             . '    <span class="text-nowrap"><span class="text-muted small">Target Publikasi:</span> <span class="fw-semibold">' . $endStr . '</span></span>'
                             . '  </div>'
                             . '</div>';
                    }
                    $isDesain = $p->nama_kegiatan === 'Desain';
                    $label = $isDesain ? 'Deadline:' : 'Target:';
                    $badge = $isDesain
                        ? '<span class="badge bg-light text-info border small ms-2"><i class="fa-solid fa-palette me-1"></i>Desain</span>'
                        : '<span class="badge bg-light text-success border small ms-2"><i class="fa-solid fa-bullhorn me-1"></i>Publikasi</span>';
                    return '<div class="d-inline-flex align-items-center">'
                         . '  <span class="text-nowrap"><span class="text-muted small">' . $label . '</span> <span class="fw-semibold">' . $startStr . '</span></span>'
                         . '  ' . $badge
                         . '</div>';
                }

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
            ->addColumn('pengajuan.judul_kegiatan', function ($kotakMasukPengajuan) {
                $namaKegiatan = $kotakMasukPengajuan->pengajuan?->nama_kegiatan_display ?? '-';
                return '<span class="text-gray-800" style="font-size: 11px; line-height: 1.4; white-space: normal; word-break: break-word;">' . e($namaKegiatan) . '</span>';
            })
            ->filter(function ($query) {
                if (request()->has('search') && request()->input('search')['value']) {
                    $search = request()->input('search')['value'];
                    $query->whereHas('pengajuan', function ($q) use ($search) {
                        $q->where(function ($sub) use ($search) {
                            $sub->where('nama_kegiatan', 'like', "%$search%")
                                ->orWhere('judul_kegiatan', 'like', "%$search%")
                                ->orWhere('deskripsi_kegiatan', 'like', "%$search%")
                                ->orWhere('unit_kegiatan', 'like', "%$search%")
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
            ->rawColumns(['pengajuan.nama_kegiatan', 'pengajuan.judul_kegiatan', 'pengajuan.tgl_pelaksanaan', 'pengajuan.tgl_awal', 'pengajuan.tgl_selesai', 'pengajuan.status', 'pengajuan.petugas_ditugaskan', 'pengajuan.user_id'])
            ->setRowId('DT_RowIndex');
    }



    /**
     * Get the query source of dataTable.
     */
    public function query(KotakMasukPengajuan $model): QueryBuilder
    {
        $status = request('status');
        $user = auth()->user();

        // Urutkan berdasarkan deadline selesai terdekat (yang belum selesai di atas, yang sudah selesai/ditolak di bawah)
        $query = $model->newQuery()
            ->select('kotak_masuk_pengajuans.*')
            ->leftJoin('pengajuans', 'pengajuans.id', '=', 'kotak_masuk_pengajuans.pengajuan_id')
            ->leftJoin('publikasis', 'publikasis.pengajuan_id', '=', 'pengajuans.id')
            ->with(['pengajuan.user', 'pengajuan.publikasi'])
            ->orderByRaw("CASE WHEN pengajuans.status = 'ditolak' OR publikasis.status_validasi = 'disetujui' THEN 1 ELSE 0 END ASC")
            ->orderByRaw("CASE WHEN pengajuans.status = 'ditolak' OR publikasis.status_validasi = 'disetujui' THEN '9999-12-31' ELSE COALESCE(pengajuans.tgl_selesai, pengajuans.tgl_awal, '9999-12-31') END ASC")
            ->orderByRaw("COALESCE(pengajuans.tgl_selesai, pengajuans.tgl_awal) DESC")
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
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => false,
                'autoWidth' => false,
                'responsive' => false,
                'searching' => true,
                'order' => [],
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
            Column::make('pengajuan.user_id')
                ->title('SUBMIT PENGGUNA')
                ->addClass('text-start text-nowrap')
                ->width('16%'),
            Column::make('pengajuan.judul_kegiatan')
                ->title('NAMA KEGIATAN')
                ->addClass('text-start')
                ->width('16%'),
            Column::make('pengajuan.nama_kegiatan')
                ->title('JENIS PENGAJUAN')
                ->addClass('text-start')
                ->width('14%'),
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
                ->addClass('text-start')
                ->width('18%');
        }

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
