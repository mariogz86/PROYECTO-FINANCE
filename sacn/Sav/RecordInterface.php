<?php

namespace sacn\Sav;

use sacn\Buffer;

interface RecordInterface
{
    /**
     * @var int Record type code
     */
    const TYPE = 0;

    /**
     * @return void
     */
    public function read(Buffer $buffer);

    /**
     * @return void
     */
    public function write(Buffer $buffer);
}
