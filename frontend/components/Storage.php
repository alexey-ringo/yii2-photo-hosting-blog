<?php

namespace frontend\components;

use Yii;
use yii\base\Component;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * File storage component
 * 
 * @autor A;ex Ringo
 */ 
 
 class Storage extends Component implements StorageInterface {
     
     private $fileName;
     
     /*
     1. Получить объект UploadedFile
     2. Посчитать хэш-сумму для файла
     3. Составить имя файла из хэш-суммы
     4. Сохранить файл на диск
     5. Вернуть имя файла
     */
     
    /**
     * Save given UploadedFile instance to disk
     * @param UploadedFile $file
     * @return string|null
     */
    public function saveUploadedFile(UploadedFile $file) {
        /*
        UploadedFile Object example:
        
        [picture] => yii\web\UploadedFile Object
        (
            [name] => IMG_0522.JPG
            [tempName] => /tmp/phpw2fFhB
            [type] => image/jpeg
            [size] => 611652
            [error] => 0
        )
        */
        
        //Получаем путь, по которому можно сохранить файл
        $path = $this->preparePath($file);
        
        //И если такой путь существует (не null),
        //И метод saveAs() сохраняет файл по заданному пути
        if($path && $file->saveAs($path)) {
            //Возвращаем имя файла, который был создан (записанного ранее в preparePath())
            return $this->fileName;
        }
        
    }
    
    /**
     * Prepare path to save uploaded file
     * @param UploadedFile $file
     * @return string|null
     */
    protected function preparePath(UploadedFile $file) {
        //Формирование хэшированного имени файла с названием папок из хэшированного имени
        //     0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
        //Сохраняем имя файла в свойстве класса
        $this->fileName = $this->getFileName($file);
        
        //Формирование полного пути к папке, куда будем сохранять файл
        //     /var/www/project/frontend/web/uploads/0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
        $path = $this->getStoragePath() . $this->fileName;
        
        $path = FileHelper::normalizePath($path);
        
        //Создаем путь в виде двух папок и возврящаем созданный путь
        if (FileHelper::createDirectory(dirname($path))) {
            return $path;
        }
    }
    
    /**
     * 
     * @param UploadedFile $file
     * @return string
     */
    protected function getFileName(UploadedFile $file) {
        // $file->tempName   -   /tmp/phpw2fFhB
        //Получение хэш-суммы из временного файла
        // 0ca9277f91e40054767f69afeb0426711ca0fddd
        $hash = sha1_file($file->tempName);
        
        //Вставка двух слэшей - имен папок
        $name = substr_replace($hash, '/', 2, 0);
        $name = substr_replace($name, '/', 5, 0);  
        // 0c/a9/277f91e40054767f69afeb0426711ca0fddd
        
        return $name . '.' . $file->extension;
        // 0c/a9/277f91e40054767f69afeb0426711ca0fddd.jpg
    }
    
    /**
     * @return string
     */
    protected function getStoragePath() {
        //Получаем путь к папке с файлами из глобальной конфигурации параметров
        //разрешаем с помощью GetAlias алиас @frontend
        return Yii::GetAlias(Yii::$app->params['storagePath']);
    }
    
    /**
     * 
     * @param string $filename
     * @return string
     */
    //Метод для получения полного пути к файлу -
    //добавления к имени файла, полученного и $user->picture,
    //основного названия хранилища из параметров конфига
    public function getFile(/*string*/ $filename) {
        return Yii::$app->params['storageUri'] . $filename;
    }
 }