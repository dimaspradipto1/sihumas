<?php

namespace App\DataTables;

use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TahunAkademikDataTable extends DataTable
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
            ->addColumn('is_active', function($item) {
                if ($item->is_active) {
                    return '<span class="badge bg-success text-white px-3 py-2"><i class="fa-solid fa-circle-check me-1"></i> Aktif (Default)</span>';
                }
                return '
                    <form action="'.route('tahun-akademik.set-active', $item->id).'" method="POST" style="display: inline">
                        '.csrf_field().'
                        <button type="submit" class="btn btn-xs btn-outline-primary" title="Jadikan Tahun Akademik Aktif">
                            <i class="fa-solid fa-toggle-off me-1"></i> Jadikan Aktif
                        </button>
                    </form>
                ';
            })
            ->addColumn('action', function($item){
                return '
                    <div class="d-flex justify-content-center gap-1">
                        <a href="'.route('tahun-akademik.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-2 py-1" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                        <form action="'.route('tahun-akademik.destroy', $item->id).'" method="POST" style="display: inline" class="form-action-delete">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger px-2 py-1" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>
                ';
            })
            ->rawColumns(['action', 'is_active'])
            ->setRowId('DT_RowIndex');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TahunAkademik $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('tahunakademik-table')
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
        return [
            Column::make('DT_RowIndex')->title('NO')->addClass('text-center')->width('6%')->orderable(false)->searchable(false),
            Column::make('tahun_akademik')->title('TAHUN AKADEMIK')->addClass('text-start'),
            Column::make('is_active')->title('STATUS AKTIF')->addClass('text-center')->width('18%'),
            Column::computed('action')
                  ->title('ACTION')
                  ->exportable(false)
                  ->printable(false)
                  ->width('12%')
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TahunAkademik_' . date('YmdHis');
    }
}
