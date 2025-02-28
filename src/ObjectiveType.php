<?php

namespace GlobalMoo;

enum ObjectiveType: string
{

    case Exact = 'exact';
    case Percent = 'percent';
    case Value = 'value';
    case LessThan = 'lessthan';
    case LessThanEqual = 'lessthan_equal';
    case GreaterThan = 'greaterthan';
    case GreaterThanEqual = 'greaterthan_equal';
    case Minimize = 'minimize';
    case Maximize = 'maximize';

}
