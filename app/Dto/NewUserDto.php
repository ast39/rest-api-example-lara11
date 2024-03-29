<?php

namespace App\Dto;


class NewUserDto {

    public readonly string $name;

    public readonly string $email;

    public readonly string $password;


    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? 'Пользователь';
        $this->email = $data['email'] ?? 'Не указан';
        $this->password = $data['password'] ?? 'Не указан';
    }
}
