<?php

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
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
            ->addColumn('action', function($user){
                return '
                    <a href="'.route('users.updatePassword', $user->id).'" class="btn btn-sm btn-info text-white px-3" title="Ubah Password"><i class="fa-solid fa-key"></i></a>
                    <a href="'.route('users.edit', $user->id).'" class="btn btn-sm btn-warning text-white px-3" title="Edit Pengguna"><i class="fa-solid fa-pen-to-square"></i></a>
                    <form action="'.route('users.destroy', $user->id).'" method="POST" style="display: inline">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger px-3" onclick="return confirm(\'Yakin ingin menghapus data ini?\')" title="Hapus Pengguna"><i class="fa-solid fa-trash"></i></button>
                    </form>
                ';
            })
            ->editColumn('no_wa', function ($user) {
                if (empty($user->no_wa)) {
                    return '<span class="text-muted">-</span>';
                }
                $clean = preg_replace('/[^0-9]/', '', $user->no_wa);
                // WhatsApp URL link requires international format (62)
                $waUrl = $clean;
                if (str_starts_with($waUrl, '0')) {
                    $waUrl = '62' . substr($waUrl, 1);
                } elseif (str_starts_with($waUrl, '8')) {
                    $waUrl = '62' . $waUrl;
                }

                // Display text: always show in 08 format
                $display = $clean;
                if (str_starts_with($display, '628')) {
                    $display = '0' . substr($display, 2);
                } elseif (str_starts_with($display, '8')) {
                    $display = '0' . $display;
                }

                return '<a href="https://wa.me/' . $waUrl . '" target="_blank" class="text-success fw-bold d-inline-flex align-items-center" title="Hubungi via WhatsApp"><i class="fa-brands fa-whatsapp me-1 text-success fs-6"></i> ' . e($display) . '</a>';
            })
            ->editColumn('fakultas', function ($user) {
                $unitNama = $user->unitKegiatan?->unit_kegiatan ?: $user->fakultas;
                return $unitNama ? e($unitNama) : '<span class="text-muted">-</span>';
            })
            ->addColumn('role', function ($user) {
                return $user->role_badges;
            })
            ->rawColumns(['action', 'no_wa', 'fakultas', 'role'])
            ->setRowId('DT_RowIndex');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->with('unitKegiatan');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->orderBy(1)
            ->selectStyleSingle()
            ->parameters([
                'scrollX' => true,
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
            Column::make('DT_RowIndex')->title('No'),
            Column::make('name')->title('Nama Pengguna'),
            Column::make('email')->title('Email'),
            Column::make('no_wa')->title('No. WhatsApp'),
            Column::make('fakultas')->title('Unit'),
            Column::make('role')->title('Role Akses')->orderable(false)->searchable(false),
            Column::computed('action')
                ->title('Aksi')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
