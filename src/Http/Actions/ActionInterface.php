<?php

namespace GeekBrains\LevelTwo\Http\Actions;

use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;

interface ActionInterface
{
public function handle(Request $request): Response;
}