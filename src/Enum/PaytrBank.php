<?php

namespace Yoeb\Paytr\Enum;

enum PaytrBank: string
{
    case ISBANK = 'isbank';
    case AKBANK = 'akbank';
    case DENIZBANK = 'denizbank';
    case FINANSBANK = 'finansbank';
    case HALKBANK = 'halkbank';
    case PTT = 'ptt';
    case TEB = 'teb';
    case VAKIFBANK = 'vakifbank';
    case YAPIKREDI = 'yapikredi';
    case ZIRAAT = 'ziraat';
}