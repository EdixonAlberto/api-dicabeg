<?php

namespace V2\Interfaces;

interface ControllerInterface
{
    public static function index();
    public static function show();
    public static function store();
    public static function update();
    public static function destroy();
}
