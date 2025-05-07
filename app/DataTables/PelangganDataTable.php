<?php

namespace App\DataTables;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PelangganDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            })
            ->editColumn('nama_pelanggan', function ($row) {
                $url = route('user.pelanggan.show', $row->id);
                return '<a href="' . $url . '">' . e($row->nama_pelanggan) . '</a>';
            })
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y H:i') . ' WIB';
            })
            ->setRowId('id')
            ->rawColumns(['checkbox', 'nama_pelanggan']);
    }

    public function query(Pelanggan $model): QueryBuilder
    {
        return $model->newQuery()->with(['odp.odc.otb.oltPort.olt']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('pelanggan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->responsive(true)
            ->autoWidth(false)
            ->dom('Bfrtip')
            ->buttons([
                Button::make('excel')
                    ->text('<i class="bi bi-file-earmark-excel"></i> Excel')
                    ->className('btn btn-sm btn-success me-1'),

                Button::make('csv')
                    ->text('<i class="bi bi-filetype-csv"></i> CSV')
                    ->className('btn btn-sm btn-info me-1'),

                Button::make('pdf')
                    ->text('<i class="bi bi-file-earmark-pdf"></i> PDF')
                    ->className('btn btn-sm btn-danger me-1'),

                Button::make('print')
                    ->text('<i class="bi bi-printer"></i> Cetak')
                    ->className('btn btn-sm btn-secondary'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->title('<input type="checkbox" id="checkAll">')
                ->exportable(false)
                ->printable(false)
                ->width(50)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),

            Column::make('nama_pelanggan')->title('Nama Pelanggan'),
            Column::make('alamat')->title('Alamat'),
            Column::make('nomor_hp')->title('No HP'),
            Column::make('latitude')->title('Latitude'),
            Column::make('longitude')->title('Longitude'),
            Column::make('created_at')->title('Tanggal Daftar'),
        ];
    }

    protected function filename(): string
    {
        return 'Pelanggan_' . date('YmdHis');
    }
}
