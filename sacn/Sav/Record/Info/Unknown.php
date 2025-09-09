<?php

namespace sacn\Sav\Record\Info;

use sacn\Buffer;
use sacn\Sav\Record\Info;

class Unknown extends Info
{
    public function read(Buffer $buffer)
    {
        parent::read($buffer);
        $this->data['raw'] = $buffer->readString($this->dataSize * $this->dataCount);
    }

    public function write(Buffer $buffer)
    {
        if (!isset($this->data['raw'])) {
            $this->data['raw'] = '';
        }
        $this->dataCount = \strlen($this->data['raw']);
        parent::write($buffer);
        $buffer->writeString($this->data['raw']);
    }
}
