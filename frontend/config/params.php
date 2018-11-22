<?php
return [
    'adminEmail' => 'admin@example.com',
    
    'maxFileSize' => 1024 * 1024 * 2, //2 megabites
    'storagePath' => '@frontend/web/uploads/', //Папка для хранения загруженных файлов
    'storageUri' =>  '/uploads/', //Адрес ресурса, по которому изображения будут доступны извне
    //Параметры для изменения размеров изображений - PostForm - Intervention\Image\ImageManager
    'postPicture' => [
        'maxWidth' => 1024,
        'maxHeight' => 768,
        ],
    //Ограничение максимального кол-ва постов в ленте пользователя
    'feedPostLimit' => 200,
];
