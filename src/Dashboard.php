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

            $cols[] = Col::span($card->col)->body($card->calculate(request()));
        }

        $rows = Row::gutter(8)->body($cols);

        return $this->setLayoutContent($rows);
    }
}
