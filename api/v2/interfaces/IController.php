<?php

namespace V2\Interfaces;

interface IController extends IResource
{
    public static function index();
    public static function show();
    public static function store($body);
    public static function update($body);
    public static function destroy();
}
