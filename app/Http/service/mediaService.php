<?php

namespace App\Http\service;

class mediaService{
    public function createMedia( $model , $file , $collection = 'images'){
       return $model->addMedia($file->file('image'))->toMediaCollection($collection);
    }

    public function updateMedia($model , $file , $collection = 'images'){
        if($model->getMedia('collection')->isNotEmpty()){
            $model->clearMediaCollection($collection);
        }
        return $model->addMedia($file)->toMediaCollection($collection);
    }

    public function deleteMedia($model , $collection = 'images'){
        if($model->getMedia('collection')->isNotEmpty()){
            return $model->clearMediaCollection($collection);
        }
        return true;
    }
}