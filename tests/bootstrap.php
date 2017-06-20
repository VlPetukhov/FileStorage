<?php
spl_autoload_register(function ($className) {
    $matches = [];

    if (!preg_match('#^ESlovo\\\\FileStorage\\\\(?P<path>[\w][\\\\\w]+)$#', $className, $matches)) {
        return;
    }

    if (isset($matches['path'])) {
        $filePath = realpath(__DIR__ . '/../src/'). DIRECTORY_SEPARATOR . $matches['path'] . '.php';

        if (file_exists($filePath)) {
            require_once ($filePath);
        }
    }
});
