<?php

namespace Braip\Messenger\Contracts;

interface ShouldMessage
{
    public function messageWith(): array;
}
