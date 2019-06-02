<?php

namespace V2\Interfaces;

interface IController extends IResource
{
    public static function index() : void;
    public static function show() : void;
    public static function store($body) : void;
    public static function update($body) : void;
    public static function destroy() : void;
}
