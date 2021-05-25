<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\QuarkAdmin\Layout;

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
        return $this->setLayoutContent('xxx');
    }
}
