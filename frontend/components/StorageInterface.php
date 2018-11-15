<?php

namespace frontend\components;

use yii\web\UploadedFile;

/**
 * File storage interface
 * 
 * @author Alex Ringo
 */
 
 interface StorageInterface {
     //Метод для сохранения файлов
     public function saveUploadedFile(UploadedFile $file);
     
     //Метод для получения полного пути к файлу по его имени
     public function getFile(/*string*/ $filename);
 }