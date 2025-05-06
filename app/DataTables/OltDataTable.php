<?php

namespace App\DataTables;

use App\Models\Olt;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OltDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder<Olt> $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('checkbox', fn($row) => '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">')
            ->editColumn('created_at', fn($row) => \Carbon\Carbon::parse($row->created_at)->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i') . ' WIB')
            ->setRowId('id')
            ->rawColumns(['checkbox']);
    }

    /**
     * Get the query source of dataTable.
     *
     * @return QueryBuilder<Olt>
     */
    public function query(Olt $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('olt-table')
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('checkbox')->title('<input type="checkbox" id="checkAll">')->exportable(false)->printable(false)->width(60)->addClass('text-center')->orderable(false)->searchable(false),
            Column::make('nama_olt')->title('Nama OLT'),
            Column::make('ip_address')->title('IP Address'),
            Column::make('lokasi')->title('Lokasi'),
            Column::make('losses')->title('Losses'),
            Column::make('created_at')->title('Dibuat'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'OLT_' . date('YmdHis');
    }
}
