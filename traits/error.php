<?php

namespace EcommerceTest\Traits;

trait Error{

    private int $errno = 0;
    private ?string $error = null;

    public function getErrno(){return $this->errno;}
}
?>