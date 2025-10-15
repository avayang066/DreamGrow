<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TrackableItemService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportTrackableItemPercentPdf extends Command
{
    protected $signature = 'export:trackable-percent-pdf';
    protected $description = '每日產生 TrackableItem 百分比 PDF 報表';

    public function handle()
    {
        $typeId = 10; // 可依需求調整
        $request = new Request();
        $service = new TrackableItemService($request);
        $service->getLevelPercentByName($typeId);
        $data = $service->getResponse()->getData(true);

        $rows = [];
        foreach ($data['percent_by_name'] as $name => $percent) {
            $rows[] = [
                'name' => $name,
                'percent' => $percent
            ];
        }

        // 產生 PDF
        $pdf = Pdf::loadView('pdf.trackable_percent', [
            'date' => now()->toDateString(),
            'rows' => $rows,
            'total' => $data['total_level']
        ]);

        $fileName = 'trackable_percent_' . now()->format('Ymd') . '.pdf';
        $pdf->save(storage_path('app/' . $fileName));

        $this->info('PDF 報表已產生：' . $fileName);
    }
}