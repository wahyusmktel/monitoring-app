<?php

namespace App\DataTables;

use App\Models\OltPort;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OltPortDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            })
            ->editColumn('created_at', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)
                    ->timezone('Asia/Jakarta')
                    ->translatedFormat('d F Y H:i') . ' WIB';
            })
            ->setRowId('id')
            ->rawColumns(['checkbox']);
    }

    public function query(OltPort $model): QueryBuilder
    {
        return $model->newQuery()->with('olt');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('olt-port-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle()
            ->responsive(true)
            ->autoWidth(false)
            ->dom('Bfrtip')
            ->buttons([
                Button::make('excel')->text('<i class="bi bi-file-earmark-excel"></i> Excel')->className('btn btn-sm btn-success me-1'),
                Button::make('csv')->text('<i class="bi bi-filetype-csv"></i> CSV')->className('btn btn-sm btn-info me-1'),
                Button::make('pdf')->text('<i class="bi bi-file-earmark-pdf"></i> PDF')->className('btn btn-sm btn-danger me-1'),
                Button::make('print')->text('<i class="bi bi-printer"></i> Cetak')->className('btn btn-sm btn-secondary'),
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')
                ->title('<input type="checkbox" id="checkAll">')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),

            Column::make('nama_port')->title('Nama Port'),
            Column::make('olt_id')->title('ID OLT'),
            Column::make('status')->title('Status'),
            Column::make('kapasitas')->title('Kapasitas'),
            Column::make('losses')->title('Losses'),
            Column::make('created_at')->title('Dibuat'),
        ];
    }

    protected function filename(): string
    {
        return 'OltPort_' . date('YmdHis');
    }
}
