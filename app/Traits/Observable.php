<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

use App\Models\Logs;

use Illuminate\Support\Facades\Auth;

trait Observable
{

//    public static function bootObservable()
//    {
//        static::saved(function (Model $model) {
//            // create or update?
//            if( $model->wasRecentlyCreated ) {
//                static::logChange( $model, 'CREATED' );
//            } else {
//                if( !$model->getChanges() ) {
//                    return;
//                }
//                static::logChange( $model, 'UPDATED' );
//            }
//        });
//
//        static::deleted(function (Model $model) {
//            static::logChange( $model, 'DELETED' );
//        });
//    }
//
//    /**
//     * String to describe the model being updated / deleted / created
//     *
//     * Override this in your own model to customise - see below for example
//     *
//     * @return string
//     */
//    public static function logSubject(Model $model): string {
//        return static::logImplodeAssoc($model->attributesToArray());
//    }
//
//    /**
//     * Format an assoc array as a key/value string for logging
//     * @return string
//     */
//    public static function logImplodeAssoc(array $attrs): string {
//        $l = '';
//        foreach( $attrs as $k => $v ) {
//            $l .= "{ $k => $v } ";
//        }
//        return $l;
//    }
//
//    /**
//     * String to describe the model being updated / deleted / created
//     * @return string
//     */
//    public static function logChange( Model $model, string $action ) {
//
//        Logs::create([
//            'user_id' => Auth::check() ? Auth::user()->id : null,
//            'status' => Auth::check() ? Auth::user()->status : null,
//            'model'   => static::class,
//            'action'  => $action,
//            'message' => static::logSubject($model),
//            'models'  => [
//                'new'     => $action !== 'DELETED' ? $model->getAttributes() : null,
//                'old'     => $action !== 'CREATED' ? $model->getOriginal()   : null,
//                'changed' => $action === 'UPDATED' ? $model->getChanges()    : null,
//            ]
//        ]);
//    }

}
