<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    use ResponseTrait;
    private $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = model('UsuarioModel');
    }
    function register()
    {

        $data = $this->request->getJSON();

        if ($this->usuarioModel->save($data) === false) {
            return $this->failValidationErrors($this->usuarioModel->errors(), "malo");
        } else {
            $insertedID = $this->usuarioModel->getInsertID();
            $savedRecord = $this->usuarioModel->find($insertedID);
            // return $this->respondCreated($savedRecord);
            return $this->getJWTForUser($data->email, ResponseInterface::HTTP_CREATED);
        }

    }
    public function login()
    {


        // $data = $this->request->getJSON();

        $rules = [
            'email' => 'required',
            'password' => 'required|validateUsuario[email, password]'
        ];
        $errors = [
            'password' => [
                'validateUsuario' => 'Invalid login credentials provided'
            ]
        ];

        $input = $this->getRequestInput($this->request);

        if (!$this->validateRequest($input, $rules, $errors)) {
            // Los datos de inicio de sesión son válidos
            // Aquí puedes realizar la lógica de autenticación
            return $this->respond($this->validator->getErrors(), ResponseInterface::HTTP_BAD_REQUEST);            
        } 

        return $this->getJWTForUser($input['email']);





        // $this->usuarioModel->getValidationRules()




        // $data = $this->request->getJSON();

        // var_dump($data);
        // exit();

        // $this->usuarioModel->validationRules;

        // $this->usuarioModel->

        // if (!$this->validateRequest($input, $rules, $errors)) {
        //     return $this->getResponse($this->validator->getErrors(), ResponseInterface::HTTP_BAD_REQUEST);
        // }

        // return $this->getJWTForUser($input['email']);
    }

    private function getJWTForUser(string $email, int $responseCode = ResponseInterface::HTTP_OK)
    {
        try {
            // $model = new UserModel();
            $user = $this->usuarioModel->findUserByEmailAddress($email);
            unset($user['password']);

            helper('jwt');

            return $this->getResponse([
                'message' => 'User authenticated successfully',
                'user' => $user,
                'access_token' => getSignedJWTForUser($email)
            ]);
        } catch (\Exception $e) {
            return $this->getResponse([
                'error' => $e->getMessage()
            ], $responseCode);
        }
    }
}
