<?php
require_once __DIR__ . '/BaseDao.php';

class MessageDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "message";
        parent::__construct($this->table_name);
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM ' . $this->table_name, []);
    }

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE message_id=:id', ['id' => $id]);
    }

    public function get_conversation($user1_id, $user2_id)
    {
        return $this->query(
            'SELECT m.*, u.username as sender_username 
            FROM ' . $this->table_name . ' m 
            JOIN user u ON m.user_id = u.user_id 
            WHERE (m.user_id = :user1_id AND m.listing_id IN (SELECT listing_id FROM listing WHERE user_id = :user2_id))
            OR (m.user_id = :user2_id AND m.listing_id IN (SELECT listing_id FROM listing WHERE user_id = :user1_id))
            ORDER BY m.created_at ASC',
            [
                'user1_id' => $user1_id,
                'user2_id' => $user2_id
            ]
        );
    }

    public function get_messages_by_listing($listing_id)
    {
        return $this->query(
            'SELECT m.*, u.username as sender_username 
            FROM ' . $this->table_name . ' m 
            JOIN user u ON m.user_id = u.user_id 
            WHERE m.listing_id = :listing_id 
            ORDER BY m.created_at ASC',
            ['listing_id' => $listing_id]
        );
    }

    public function get_user_messages($user_id)
    {
        return $this->query(
            'SELECT m.*, u.username as sender_username, l.brand, l.model 
            FROM ' . $this->table_name . ' m 
            JOIN user u ON m.user_id = u.user_id 
            JOIN listing l ON m.listing_id = l.listing_id 
            WHERE m.user_id = :user_id 
            OR l.user_id = :user_id 
            ORDER BY m.created_at DESC',
            ['user_id' => $user_id]
        );
    }

    public function add_message($user_id, $listing_id, $content)
    {
        return $this->add([
            'user_id' => $user_id,
            'listing_id' => $listing_id,
            'content' => $content,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
} 