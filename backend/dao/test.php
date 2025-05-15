<?php
require_once 'userDao.php';


$userDao = new UserDao();

/*

// Insert a new user (Customer)
$userDao->add_user([
    'username' => 'John Doe',
    'email' => 'john@example.com',
    'password' => password_hash('password123', PASSWORD_DEFAULT),
]);

*/

// fetch all users
$users = $userDao->get_all();
print_r($users);