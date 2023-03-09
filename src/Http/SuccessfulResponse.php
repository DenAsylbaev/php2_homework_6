<?php declare(strict_types=1);

namespace GeekBrains\LevelTwo\Http;


// Класс успешного ответа
class SuccessfulResponse extends Response
{
    protected const SUCCESS = true;
    private array $data = [];

    // Успешный ответ содержит массив с данными,
    // по умолчанию - пустой
    public function __construct($data) {
        $this->data = $data;
    }
    
    // Реализация абстрактного метода
    // родительского класса
    protected function payload(): array
    {
        return ['data' => $this->data];
    }
}