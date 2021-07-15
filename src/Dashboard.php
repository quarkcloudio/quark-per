<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Row;
use QuarkCMS\Quark\Facades\Col;

/**
 * Class Dashboard.
 */
abstract class Dashboard
{
    use Layout;

    /**
     * 页面标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 获取页面标题
     *
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * 卡片列表
     *
     * @return array
     */
    public function cards()
    {
        return [];
    }

    /**
     * 资源对象
     *
     * @param  void
     * @return array
     */
    public function resource()
    {
        $cards = $this->cards();
        
        $colNum = 0;
        $rows = $cols = [];

        foreach ($cards as $key => $card) {
            $colNum = $colNum + $card->col;
            $cardItem = Card::style(['padding' => '24px'])->body(
                $card->calculate(request())
            );
            $cols[] = Col::span($card->col)->body($cardItem);
            if($colNum%24 === 0) {
                $row = Row::gutter(8);
                if($key !== 1) {
                    $row = $row->style(['marginTop' => '20px']);
                }
                $rows[] = $row->body($cols);
                $cols = [];
            }
        }

        if($cols) {
            $row = Row::gutter(8);
            if($colNum > 24) {
                $row = $row->style(['marginTop' => '20px']);
            }
            $rows[] = $row->body($cols);
        }

        return $this->setLayoutContent($rows);
    }
}
