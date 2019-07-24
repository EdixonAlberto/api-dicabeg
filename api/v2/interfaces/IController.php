<?php

namespace V2\Interfaces;

use V2\Modules\Requests;

interface IController extends IResource
{
    public static function index(Requests $req): void;
    public static function show(Requests $req): void;
    public static function store(Requests $req): void;
    public static function update(Requests $req): void;
    public static function destroy(Requests $req): void;
}
