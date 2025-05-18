<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UserDao.php';

class UserService extends BaseService {
    private $userDao;

    public function __construct() {
        $this->userDao = new UserDao();
        parent::__construct($this->userDao);
    }

    public function register($user) {
        // Validate required fields
        if (empty($user['email']) || empty($user['username']) || empty($user['password'])) {
            throw new Exception("Email, username, and password are required");
        }

        // Validate email format
        if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Check if email already exists
        if ($this->userDao->email_exists($user['email'])) {
            throw new Exception("Email already registered");
        }

        // Check if username already exists
        if ($this->userDao->username_exists($user['username'])) {
            throw new Exception("Username already taken");
        }

        // Validate password strength
        if (strlen($user['password']) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }

        return $this->userDao->add_user($user);
    }

    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }

        $user = $this->userDao->authenticate($email, $password);
        if (!$user) {
            throw new Exception("Invalid email or password");
        }

        // Remove sensitive data before returning
        unset($user['password']);
        return $user;
    }

    public function update_user($id, $user) {
        // Validate user exists
        $existing_user = $this->userDao->get_by_id($id);
        if (!$existing_user) {
            throw new Exception("User not found");
        }

        // If email is being updated, check if it's already taken
        if (isset($user['email']) && $user['email'] !== $existing_user['email']) {
            if ($this->userDao->email_exists($user['email'])) {
                throw new Exception("Email already registered");
            }
        }

        // If username is being updated, check if it's already taken
        if (isset($user['username']) && $user['username'] !== $existing_user['username']) {
            if ($this->userDao->username_exists($user['username'])) {
                throw new Exception("Username already taken");
            }
        }

        // If password is being updated, validate its strength
        if (isset($user['password'])) {
            if (strlen($user['password']) < 8) {
                throw new Exception("Password must be at least 8 characters long");
            }
        }

        return $this->userDao->update_user($id, $user);
    }

    public function get_user_stats($user_id) {
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        return $this->userDao->get_user_stats($user_id);
    }

    public function delete_user($id) {
        $user = $this->userDao->get_by_id($id);
        if (!$user) {
            throw new Exception("User not found");
        }

        return $this->userDao->delete_user($id);
    }
}
?> 