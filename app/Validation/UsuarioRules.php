<?php

namespace App\Validation;

class UsuarioRules
{
    public function validateUsuario(string $str, string $fields, array $data): bool
    {
        try {
            $usuarioModel = model('UsuarioModel');
            $usuarioData = $usuarioModel->findUserByEmailAddress($data['email']);
            return password_verify($data['password'], $usuarioData['password']);
        } catch (\Exception $e) {
            return false;
        }
    }
}
