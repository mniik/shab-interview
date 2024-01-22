<?php

namespace App\Enums;

enum CartState: int
{
    case Stale = 0;

    case Active = 1;
}
