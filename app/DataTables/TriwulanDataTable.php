<?php

namespace App\DataTables;

use App\Models\Triwulan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TriwulanDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $currentMonth = (int) Carbon::now()->month;

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->editColumn('kode', function ($item) {
                return '<span class="badge bg-primary px-2 py-1">' . e($item->kode) . '</span>';
            })
            ->addColumn('rentang_bulan', function ($item) use ($namaBulan) {
                $mulai = $namaBulan[$item->bulan_mulai] ?? $item->bulan_mulai;
                $selesai = $namaBulan[$item->bulan_selesai] ?? $item->bulan_selesai;
                return $mulai . ' – ' . $selesai;
            })
            ->addColumn('is_active', function ($item) use ($currentMonth) {
                $isCurrentMonth = $item->containsMonth($currentMonth);
                if ($item->is_active) {
                    $label = $isCurrentMonth ? 'Aktif (Bulan Berjalan)' : 'Aktif';
                    return '<span class="badge bg-success text-white px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> ' . $label . '</span>';
                }

                return '
                    <form action="' . route('triwulan.set-active', $item->id) . '" method="POST" style="display: inline">
                        ' . csrf_field() . '
                        <button type="submit" class="btn btn-xs btn-outline-primary" title="Jadikan Triwulan Aktif">
                            <i class="fa-solid fa-toggle-off me-1"></i> Jadikan Aktif
                        </button>
                    </form>
                ';
            })
            ->addColumn('action', function ($item) {
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="' . route('triwulan.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-2 py-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="' . route('triwulan.destroy', $item->id) . '" method="POST" style="display: inline" class="form-action-delete">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['kode', 'action', 'is_active'])
            ->setRowId('DT_RowIndex');
    }

    public function query(Triwulan $model): QueryBuilder
    {
        return $model->newQuery()->orderBy('id');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('triwulan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'asc')
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
                Button::make('reload'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')->title('NO')->addClass('text-center')->width('6%')->orderable(false)->searchable(false),
            Column::make('kode')->title('KODE')->addClass('text-center')->width('10%'),
            Column::make('nama')->title('TRIWULAN (TW)')->addClass('text-start'),
            Column::computed('rentang_bulan')->title('RENTANG BULAN')->addClass('text-start'),
            Column::make('is_active')->title('STATUS AKTIF')->addClass('text-center')->width('22%'),
            Column::computed('action')
                ->title('ACTION')
                ->exportable(false)
                ->printable(false)
                ->width('14%')
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Triwulan_' . date('YmdHis');
    }
}
