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
            ->addColumn('DT_RowIndex', '')
            ->addColumn('nama_kegiatan', function ($publikasi) {
                if (!$publikasi->pengajuan) {
                    return '-';
                }
                return '<a href="' . route('pengajuan.edit', $publikasi->pengajuan->id) . '" class="text-decoration-none text-success">' . e($publikasi->pengajuan->nama_kegiatan) . '</a>';
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

            ->addColumn('action', function ($publikasi) {
                $target = !empty($publikasi->slug) ? $publikasi->slug : $publikasi->id;
                return '
                     <a href="' . route('publikasi.show', $target) . '" class="btn btn-sm btn-primary text-white px-3"><i class="fa-solid fa-eye"></i></a>
                     <a href="' . route('publikasi.edit', $target) . '" class="btn btn-sm btn-warning text-white px-3"><i class="fa-solid fa-pen-to-square"></i></a>
                     <form action="' . route('publikasi.destroy', $target) . '" method="POST" style="display: inline">
                         ' . csrf_field() . '
                         ' . method_field('DELETE') . '
                         <button type="submit" class="btn btn-sm btn-danger px-3" onclick="return confirm(\'Yakin ingin menghapus data ini?\')"><i class="fa-solid fa-trash-can"></i></button>
                     </form>
                 ';
            })
            ->rawColumns(['action','nama_kegiatan', 'tgl_awal', 'tgl_akhir', 'upload_laporan', 'link_dokumentasi', 'link_publikasi', 'user_id', 'tahun_akademik_id', 'pengajuan_id'])
            ->setRowId('DT_RowIndex');
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
                ->with(['pengajuan.user', 'tahunAkademik', 'user'])
                ->orderBy('created_at', 'desc');
        }

        // 2. Petugas Humas melihat pelaporan kegiatan yang ditugaskan kepadanya sebagai PIC
        if ($user && $user->is_humas) {
            return $model->newQuery()
                ->with(['pengajuan.user', 'tahunAkademik', 'user'])
                ->where(function ($q) use ($user) {
                    $q->whereHas('pengajuan', function ($pq) use ($user) {
                        $pq->assignedToUser($user);
                    })->orWhere('publikasis.user_id', $user->id);
                })
                ->orderBy('created_at', 'desc');
        }

        // 3. User Unit/Fakultas melihat pelaporan dari kegiatan yang diajukannya
        return $model->newQuery()
            ->with(['pengajuan.user', 'tahunAkademik', 'user'])
            ->where(function ($q) use ($user) {
                $q->where('publikasis.user_id', $user->id)
                  ->orWhereHas('pengajuan', function ($pq) use ($user) {
                      $pq->where('user_id', $user->id);
                  });
            })
            ->orderBy('created_at', 'desc');
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
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'columnDefs' => [
                    [
                        'targets' => 1,
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
                        'className' => 'text-center',
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
                Button::make('reload')
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('DT_RowIndex')
                ->title('No')
                ->addClass('text-start')
                ->width(10),
            Column::make('pengajuan.nama_kegiatan')
                ->title('Nama Kegiatan'),
            Column::make('tgl_awal')
                ->title('Tanggal Kegiatan')
                ->width(20),
            Column::make('tgl_akhir')
                ->title('Tanggal Selesai')
                ->width(20),
            Column::make('link_dokumentasi')
                ->title('Dokumentasi')
                ->orderable(false)
                ->searchable(false),
            Column::make('link_publikasi')
                ->title('Link Publikasi')
                ->orderable(false)
                ->searchable(false),
            Column::make('user_id')
                ->title('Submit Pengguna')
                ->visible(auth()->user()->is_admin)
                ->width(5),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(40)
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
