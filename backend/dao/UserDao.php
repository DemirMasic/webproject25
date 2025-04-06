<?php
require_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao
{

    protected $table_name;
    public function __construct()
    {
        $this->table_name = "user";
        parent::__construct($this->table_name);
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM ' . $this->table_name, []);
    }

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE user_id=:id', ['id' => $id]);
    }

    public function get_by_email($email)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE email=:email', ['email' => $email]);
    }

    public function get_by_username($username)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE username=:username', ['username' => $username]);
    }

    public function add_user($user)
    {
        // Hash the password before storing
        if (isset($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
        
        $user['created_at'] = date('Y-m-d H:i:s');
        return $this->add($user);
    }

    public function update_user($id, $user)
    {
        // Hash the password if it's being updated
        if (isset($user['password'])) {
            $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        }
        
        return $this->update($user, $id, 'user_id');
    }

    public function authenticate($email, $password)
    {
        $user = $this->get_by_email($email);
        
        if (!$user) {
            return false;
        }

        return password_verify($password, $user['password']) ? $user : false;
    }

    public function get_user_stats($user_id)
    {
        return $this->query_unique(
            'SELECT 
                u.*,
                COUNT(DISTINCT l.listing_id) as total_listings,
                COUNT(DISTINCT f.listing_id) as total_favorites,
                COUNT(DISTINCT m.message_id) as total_messages
            FROM ' . $this->table_name . ' u
            LEFT JOIN listing l ON u.user_id = l.user_id
            LEFT JOIN favorites f ON u.user_id = f.user_id
            LEFT JOIN message m ON u.user_id = m.user_id
            WHERE u.user_id = :user_id
            GROUP BY u.user_id',
            ['user_id' => $user_id]
        );
    }

    public function email_exists($email)
    {
        $result = $this->get_by_email($email);
        return !empty($result);
    }

    public function username_exists($username)
    {
        $result = $this->get_by_username($username);
        return !empty($result);
    }

    public function delete_user($id)
    {
        // First delete related records
        $this->query('DELETE FROM favorites WHERE user_id = :id', ['id' => $id]);
        $this->query('DELETE FROM message WHERE user_id = :id', ['id' => $id]);
        
        // Delete listings and their related images
        $listings = $this->query('SELECT listing_id FROM listing WHERE user_id = :id', ['id' => $id]);
        foreach ($listings as $listing) {
            $this->query('DELETE FROM image WHERE listing_id = :listing_id', ['listing_id' => $listing['listing_id']]);
        }
        $this->query('DELETE FROM listing WHERE user_id = :id', ['id' => $id]);
        
        // Finally delete the user
        return parent::delete($id);
    }
}