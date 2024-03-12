<?php

namespace Northrook\Symfony\Stylesheets;

enum SaveCondition
{
    case ForceRegeneration;
    case SaveOnResponse;
    case SaveOnRequest;
    case Manual;
}