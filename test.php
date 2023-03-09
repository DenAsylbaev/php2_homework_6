<?php

// Функция с двумя параметрами возвращает строку
function someFunction(bool $one, int $two = 123): string
{
    return $one . $two;
}

// Создаём объект рефлексии
// Передаём ему имя интересующей нас функции
$reflection = new ReflectionFunction('someFunction');

// Получаем тип возвращаемого функцией значения
echo $reflection->getReturnType()->getName() . "\n";


// Получаем параметры функции
foreach ($reflection->getParameters() as $parameter) {

    // Для каждого параметра функции
    // получаем его имя и тип
    echo $parameter->getName().'['.$parameter->getType()->getName()."]\n";
}

// $constructor = $reflection->getConstructor();
