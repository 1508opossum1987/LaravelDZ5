<?php
/*if (!function_exists('get_basket_for_cookie')) {
    public function get_basket_for_cookie(): ?array
    {
        if(!Cookie::has('basket')) {
            return null;
        }
        return json_decode(Cookie::get('basket'), true);
    }
}*/

use Illuminate\Support\Facades\Session;

if (!function_exists('get_basket_for_session')) {
    function get_basket_for_session(): ?array
    {
        if (!Session::has('basket')) {
            return null;
        }

        return json_decode(Session::get('basket'), true);
    }
}

if (!function_exists('create_new_file')) {
    function create_new_file(string $text, string $filepath): array
    {
        // Проверяем, существует ли уже файл
        if (file_exists($filepath)) {
            return [
                'success' => false,
                'error' => "Файл уже существует: {$filepath}"
            ];
        }

        // Создаем директорию если нужно
        $directory = dirname($filepath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Режим 'x' - создает новый файл, если не существует
        $handle = fopen($filepath, 'x');

        if ($handle === false) {
            return [
                'success' => false,
                'error' => "Не удалось создать файл: {$filepath}"
            ];
        }

        // Записываем текст
        fwrite($handle, $text);
        fclose($handle);

        return [
            'success' => true,
            'path' => $filepath,
            'message' => "Новый файл успешно создан"
        ];
    }
}
