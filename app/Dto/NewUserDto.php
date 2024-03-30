<?php

namespace App\Dto;


readonly class NewUserDto {

    public string $name;

    public string $email;

    public string $password;


    public function __construct(array $data)
    {
        $this->name = $data['name'] ?? 'Не указано';
        $this->email = $data['email'] ?? 'Не указано';
        $this->password = $data['password'] ?? 'Не указано';
    }
}
