<?php

namespace V2\Controllers;

use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Format;
use Modules\Exceptions;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\JsonResponse;
use V2\Interfaces\IController;

class ProductController implements IController
{
    public static function index($req): void
    {
        $arrayProducts = Querys::table('products')
            ->select(['product_id', 'name', 'price', 'photo'])
            ->where('user_id', User::$id)
            ->group($req->params->nro, $req->params->order ?? null)
            ->getAll(function () {
                new Exceptions\ResourceError('produts');
            });

        JsonResponse::read($arrayProducts);
    }
    public static function show($req): void
    {
        $arrayProducts = Querys::table('products')
            ->select(self::PRODUCTS_COLUMNS)
            ->where([
                'product_id' => $req->params->id,
                'user_id' => User::$id
            ])->get(function () {
                new Exceptions\ResourceError('produts');
            });

        JsonResponse::read($arrayProducts);
    }
    public static function store($req): void
    {
        $body = $req->body;

        Querys::table('products')->insert($product = [
            'product_id' => Security::generateID(),
            'user_id' => User::$id,
            'category_id' => $body->category,
            'name' => $body->name,
            'price' => Format::number($body->price),
            'description' => $body->description ?? null,
            'quantity' => Format::number($body->quantity) ?? null,
            'photo' => $body->photo ?? null,
            'create_date' => Time::current()->utc
        ])->execute(function () {
            throw new Exception('product not created', 500);
        });

        JsonResponse::read($product);
    }
    public static function update($req): void
    {
        $body = $req->body;

        Querys::table('products')->update($product = [
            'category_id' => $body->category ?? null,
            'name' => $body->name ?? null,

            'price' => isset($body->price) ?
                Format::number($body->price) : null,

            'description' => $body->description ?? null,

            'quantity' => isset($body->quantity) ?
                Format::number($body->quantity) : null,

            'photo' => $body->photo ?? null,
            'update_date' => Time::current()->utc
        ])->where([
            'product_id' => $req->params->id,
            'user_id' => User::$id
        ])->execute(function () {
            new Exceptions\ResourceError('product');
        });

        JsonResponse::updated($product);
    }

    public static function destroy($req): void
    {
        Querys::table('products')->delete()
            ->where([
                'product_id' => $req->params->id,
                'user_id' => User::$id
            ])->execute(function () {
                new Exceptions\ResourceError('product');
            });

        JsonResponse::removed();
    }
}
