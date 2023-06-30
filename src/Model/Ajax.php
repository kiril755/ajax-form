<?php

namespace Model;

class Ajax
{
    private $users = [
        [
            "id" => 1,
            "name" => "John",
            "email" => "john@example.com",
            "password" => "password1"
        ],
        [
            "id" => 2,
            "name" => "Jane",
            "email" => "jane@example.com",
            "password" => "password2"
        ],
        [
            "id" => 3,
            "name" => "David",
            "email" => "david@example.com",
            "password" => "password3"
        ]
    ];

    public function __construct()
    {
        session_start();

        if (!isset($_SESSION['users'])) {
            $_SESSION['users'] = $this->users;
        }
    }

    public function request($data)
    {
        if (!$this->emailValidation($data['email'])) {
            $result = ['error' => "email is incorrect"];
            return json_encode($result);
        }
        if (!$this->passMismatchValidation($data['password'], $data['repeatPassword'])) {
            $result = ['error' => "passwords mismatch!"];
            return json_encode($result);
        }

        if (!$this->emailCheck($data['email'])) {
            $_SESSION['users'][] = [
                'id' => count($_SESSION['users']) + 1,
                'name' => $data['firstName'],
                'email' => $data['email'],
                'password' => $data['password']
            ];
            $result = ['success' => "welcome $data[firstName]!", 'session' => $_SESSION['users']];
        } else {
            $result = ['error' => "Thats email in use!", 'session' => $_SESSION['users']];
        }

        $this->logger($data, $result);
        return json_encode($result);
    }

    // в першу чергу спрацьовує валідація на фротні
    private function emailValidation($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    // в першу чергу спрацьовує валідація на фротні
    private function passMismatchValidation($password, $repeatPassword)
    {
        if ($password === $repeatPassword) {
            return true;
        }
        return false;
    }

    private function emailCheck($email)
    {
        foreach ($_SESSION['users'] as $user) {
            if ($user['email'] === $email) {
                return true;
                break;
            }
        }
    }

    private function logger($data, $result)
    {
        $log_filename = $_SERVER['DOCUMENT_ROOT'] . "/log/request-log" . date("j.n.Y") . '.txt';
        $log = "User: " . $_SERVER['REMOTE_ADDR'] . ' - ' . date("F j, Y, g:i a") . PHP_EOL .
            "Attempt: " . (isset($result['success']) ? 'success sign in' : 'Error, email in use') . PHP_EOL .
            "User: " . $data['firstName'] . PHP_EOL .
            "Email: " . $data['email'] . PHP_EOL .
            "-------------------------" . PHP_EOL;
        file_put_contents($log_filename, $log, FILE_APPEND);
    }

    public function clearSession() {
        $_SESSION = array();
    }
}