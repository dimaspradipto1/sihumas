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
            ->addColumn('nama_kegiatan', function ($pengajuan) {
                return '<span class="fw-semibold text-gray-800">' . e($pengajuan->nama_kegiatan) . '</span>';
            })

            ->addColumn('tgl_pelaksanaan', function ($pengajuan) {
                Carbon::setLocale('id');
                $start = $pengajuan->tgl_awal ? Carbon::parse($pengajuan->tgl_awal) : null;
                $end = $pengajuan->tgl_selesai ? Carbon::parse($pengajuan->tgl_selesai) : null;

                $startStr = $start ? $start->translatedFormat('d M Y') : '-';
                $endStr = $end ? $end->translatedFormat('d M Y') : '-';

                $jamMulai = !empty($pengajuan->jam_kegiatan) ? substr($pengajuan->jam_kegiatan, 0, 5) . ' WIB' : '';
                $jamSelesai = !empty($pengajuan->waktu_selesai) ? substr($pengajuan->waktu_selesai, 0, 5) . ' WIB' : '';

                if (in_array($pengajuan->nama_kegiatan, ['Desain', 'Publikasi', 'Desain dan Publikasi'], true) && empty($jamMulai)) {
                    if ($pengajuan->nama_kegiatan === 'Desain dan Publikasi') {
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
                    $isDesain = $pengajuan->nama_kegiatan === 'Desain';
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

            ->editColumn('alasan_ditolak', function ($pengajuan) {
                $ket = trim(strip_tags($pengajuan->alasan_ditolak ?? ''));
                $linkDesain = trim($pengajuan->link_desain ?? '');

                if ($ket === '' && $linkDesain === '') {
                    return '';
                }

                $html = '<div class="text-break small text-gray-800 d-flex flex-column gap-1" style="white-space: normal !important; word-break: break-word !important;">';
                if ($ket !== '') {
                    $html .= '<span>' . e($ket) . '</span>';
                } elseif ($linkDesain !== '') {
                    $html .= '<span>Berikut untuk link desain kegiatan:</span>';
                }

                if ($linkDesain !== '') {
                    $html .= '<div><a href="' . e($linkDesain) . '" target="_blank" class="text-primary text-decoration-underline fw-semibold d-inline-flex align-items-center" title="' . e($linkDesain) . '">'
                           . '<i class="fa-brands fa-google-drive me-1"></i> Buka Link Desain</a></div>';
                }
                $html .= '</div>';

                return $html;
            })

            ->addColumn('action', function ($pengajuan) use ($user) {
                $btnStyle = 'width: 32px; height: 32px; font-size: 0.875rem;';
                $html = '<div class="d-inline-flex align-items-center justify-content-center gap-1 flex-nowrap">';
                $html .= '<a href="' . route('pengajuan.show', $pengajuan->id) . '" class="btn btn-sm btn-primary text-white d-inline-flex align-items-center justify-content-center p-0" style="' . $btnStyle . '" title="Lihat"><i class="fa-solid fa-eye"></i></a>';

                $isCompleted = in_array(strtolower((string) $pengajuan->status), ['diterima', 'selesai'], true)
                    || ($pengajuan->publikasi?->status_validasi === 'disetujui');

                if ($user->is_admin || ((int) $pengajuan->user_id === (int) $user->id && !$isCompleted)) {
                    $html .= '<a href="' . route('pengajuan.edit', $pengajuan->id) . '" class="btn btn-sm btn-warning text-white d-inline-flex align-items-center justify-content-center p-0" style="' . $btnStyle . '" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                    $html .= '<form action="' . route('pengajuan.destroy', $pengajuan->id) . '" method="POST" style="display:inline" class="form-action-delete">'
                           . csrf_field()
                           . method_field('DELETE')
                           . '<button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center p-0" style="' . $btnStyle . '" title="Hapus"><i class="fa-solid fa-trash-can"></i></button></form>';
                }
                $html .= '</div>';
                return $html;
            })

            ->rawColumns(['nama_kegiatan', 'action', 'tgl_pelaksanaan', 'tgl_awal', 'tgl_selesai', 'status', 'alasan_ditolak', 'user_id'])
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
            ->with(['user', 'publikasi'])
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
            ->orderBy(2, 'desc')
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

    public function getColumns(): array
    {
        $isAdmin = auth()->user()->is_admin ?? false;

        if ($isAdmin) {
            return [
                Column::make('DT_RowIndex')->title('NO')->addClass('text-center')->width('4%')->orderable(false)->searchable(false),
                Column::make('nama_kegiatan')->title('JENIS PENGAJUAN')->addClass('text-start')->width('24%'),
                Column::make('tgl_pelaksanaan')->title('TANGGAL')->addClass('text-start')->width('21%')->orderable(false)->searchable(false),
                Column::make('status')->title('STATUS PENGAJUAN')->addClass('text-center')->width('13%'),
                Column::make('alasan_ditolak')->title('KETERANGAN')->addClass('text-start')->width('15%'),
                Column::make('user_id')->title('SUBMIT PENGGUNA')->addClass('text-start')->width('13%'),
                Column::computed('action')->title('AKSI')->exportable(false)->printable(false)->width('10%')->addClass('text-center'),
            ];
        }

        return [
            Column::make('DT_RowIndex')->title('NO')->addClass('text-center')->width('5%')->orderable(false)->searchable(false),
            Column::make('nama_kegiatan')->title('JENIS PENGAJUAN')->addClass('text-start')->width('30%'),
            Column::make('tgl_pelaksanaan')->title('TANGGAL')->addClass('text-start')->width('25%')->orderable(false)->searchable(false),
            Column::make('status')->title('STATUS PENGAJUAN')->addClass('text-center')->width('14%'),
            Column::make('alasan_ditolak')->title('KETERANGAN')->addClass('text-start')->width('16%'),
            Column::computed('action')->title('AKSI')->exportable(false)->printable(false)->width('10%')->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Pengajuan_' . date('YmdHis');
    }
}
