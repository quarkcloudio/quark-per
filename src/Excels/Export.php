<?php

namespace QuarkCMS\QuarkAdmin\Excels;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;

class Export implements WithEvents,WithStrictNullComparison,FromArray,WithHeadings,ShouldAutoSize,WithColumnFormatting
{
    protected $lists,$headings,$columnFormats;

    public function __construct(array $lists,array $headings,array $columnFormats = [])
    {
        $this->lists = $lists;
        $this->headings = $headings;
        $this->columnFormats = $columnFormats;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * 注册事件
     * @return array
     */
    public function registerEvents(): array
    {
        $titleNum = count($this->headings);

        $endSheet = 'A';

        for ($i=1; $i < $titleNum; $i++) { 
            next_char($endSheet);
        }

        return [
            AfterSheet::class => function (AfterSheet $event) use ($endSheet) {

                //设置列宽
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);

                //设置区域单元格水平居中
                $event->sheet->getDelegate()->getStyle('A1:'.$endSheet.'1')->getAlignment()->setHorizontal('center');
                
                //设置区域单元格字体、颜色、背景等，其他设置请查看 applyFromArray 方法，提供了注释
                $event->sheet->getDelegate()->getStyle('A1:'.$endSheet.'1')->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true,
                        'italic' => false,
                        'strikethrough' => false,
                        'color' => [
                            'rgb' => 'FFFFFF'
                        ]
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, //线性填充，类似渐变
                        'startColor' => [
                            'rgb' => '54AE54' //初始颜色
                        ],
                        //结束颜色，如果需要单一背景色，请和初始颜色保持一致
                        'endColor' => [
                            'argb' => '54AE54'
                        ]
                    ]
                ]);
            } 
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        $this->columnFormats;

        $columnFormats = [];

        foreach ($this->columnFormats as $key => $value) {
            if($value == 'FORMAT_DATE_DDMMYYYY') {
                $columnFormats[$key] = NumberFormat::FORMAT_DATE_DDMMYYYY;
            }
            if($value == 'FORMAT_NUMBER_00') {
                $columnFormats[$key] = NumberFormat::FORMAT_NUMBER_00;
            }
            if($value == 'FORMAT_TEXT') {
                $columnFormats[$key] = NumberFormat::FORMAT_TEXT;
            }
        }

        return $columnFormats;
    }

    public function array(): array
    {
        return $this->lists;
    }
}