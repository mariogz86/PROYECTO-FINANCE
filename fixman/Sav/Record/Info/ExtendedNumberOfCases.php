<?php

namespace sacn\Sav\Record\Info;

use sacn\Buffer;
use sacn\Sav\Record\Info;

class ExtendedNumberOfCases extends Info
{
    const SUBTYPE = 16;

    /**
     * @var float
     */
    public $ncases = 0;

    /**
     * @var int
     */
    protected $dataSize = 8;

    /**
     * @var int
     */
    protected $dataCount = 2;

    public function read(Buffer $buffer)
    {
        parent::read($buffer);
        $buffer->readDouble();
        $this->ncases = $buffer->readDouble();
    }

    public function write(Buffer $buffer)
    {
        parent::write($buffer);
        $buffer->writeDouble(1);
        $buffer->writeDouble($this->ncases);
    }
}
