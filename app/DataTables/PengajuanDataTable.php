<?php

namespace App\DataTables;

use Carbon\Carbon;
use App\Models\Pengajuan;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class PengajuanDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $user = auth()->user();

        // ✅ JANGAN override query lagi di sini
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')

            ->addColumn('tgl_awal', function ($pengajuan) {
                Carbon::setLocale('id');
                return Carbon::parse($pengajuan->tgl_awal)->translatedFormat('l, d F Y');
            })

            ->editColumn('tgl_selesai', function ($pengajuan) {
                Carbon::setLocale('id');
                return Carbon::parse($pengajuan->tgl_selesai)->translatedFormat('l, d F Y');
            })

            ->editColumn('user_id', function ($pengajuan) use ($user) {
                return $user->is_admin ? optional($pengajuan->user)->name : '';
            })

            ->editColumn('status', function ($pengajuan) {
                if ($pengajuan->status === 'pending') {
                    return '<span class="badge bg-warning px-2 rounded-pill px-3 py-2">Pending <i class="fa-solid fa-spinner"></i></span>';
                } elseif ($pengajuan->status === 'diterima') {
                    return '<span class="badge bg-success text-white px-2 rounded-pill px-3 py-2">Diterima <i class="fa-solid fa-check"></i></span>';
                } elseif ($pengajuan->status === 'ditolak') {
                    return '<span class="badge bg-danger text-white px-2 rounded-pill px-3 py-2">Ditolak <i class="fa-solid fa-xmark"></i></span>';
                }
                return '';
            })

            ->addColumn('action', function ($pengajuan) use ($user) {
                $action = '<a href="' . route('pengajuan.show', $pengajuan->id) . '" class="btn btn-sm btn-primary text-white px-3"><i class="fa-solid fa-eye"></i></a>';

                if ($user->is_admin) {
                    $action .= '
                        <a href="' . route('pengajuan.edit', $pengajuan->id) . '" class="btn btn-sm btn-warning text-white px-3"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('pengajuan.destroy', $pengajuan->id) . '" method="POST" style="display:inline" class="form-action-delete">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-3" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    ';
                }

                return $action;
            })

            ->rawColumns(['action', 'tgl_awal', 'tgl_selesai', 'status', 'user_id'])
            ->setRowId('DT_RowIndex');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Pengajuan $model): QueryBuilder
    {
        $user = auth()->user();

        // ✅ filter status dari URL: /pengajuan?status=pending
        $status = request('status');

        $query = $model->newQuery()
            ->select('pengajuans.*')
            ->with('user')
            ->orderBy('pengajuans.created_at', 'desc');

        // ✅ scope data sesuai tipe user (sama seperti logic kamu sebelumnya)
        if (!$user->is_admin) {
            $query->where('user_id', $user->id);
        }

        // ✅ apply filter status jika ada
        if (!empty($status)) {
            $query->where('status', $status);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        $status = request('status');

        return $this->builder()
            ->setTableId('pengajuan-table')
            ->columns($this->getColumns())

            // ->minifiedAjax([
            //     'data' => 'function(d){ d.status = "' . e($status) . '"; }'
            // ])

            ->orderBy(2, 'desc')
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
                'columnDefs' => [
                    [
                        'targets' => 1,
                        'width' => '250px',
                        'render' => 'function(data, type, row, meta) {
                            return "<div style=\'word-wrap:break-word; word-break:break-word; white-space:normal; overflow-wrap:break-word;\'>" + data + "</div>";
                        }'
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

    public function getColumns(): array
    {
        $isAdmin = auth()->user()->is_admin ?? false;

        return [
            Column::make('DT_RowIndex')->title('No'),
            Column::make('nama_kegiatan')->title('Nama Kegiatan')->addClass('text-start'),
            Column::make('tgl_awal')->title('Tanggal Kegiatan')->width(20),
            Column::make('tgl_selesai')->title('Tanggal Selesai')->width(20),
            Column::make('status')->title('Status Pengajuan'),
            Column::make('user_id')->title('Submit Pengguna')->visible($isAdmin)->width(5),
            Column::computed('action')->title('Aksi')->exportable(false)->printable(false)->width(40)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Pengajuan_' . date('YmdHis');
    }
}
