<?php
/**
 * Created by letz.
 * User: letz
 * Date: 2018. 7. 10.
 * Time: AM 3:50
 */

namespace App;


trait Favorable {

    /**
     * Boot the trait.
     */
    protected static function bootFavorable()
    {
        static::deleting(function ($model) {
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if ( ! $this->favorites()->where($attributes)->exists())
        {
            return $this->favorites()->create($attributes);
        }
    }

    public function unfavorite()
    {
//        $this->favorites()->where(['user_id'=>auth()->id()])->delete();
//        $this->favorites()->where(['user_id'=>auth()->id()])->get()->each(function($f){
//            $f->delete();
//        });
        $this->favorites()->where('user_id',auth()->id())->get()->each->delete();
    }

    public function isFavorited()
    {
//        return $this->favorites()->where('user_id',auth()->id())->exists();
        return ! ! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();

    }



    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}