<?php
require_once __DIR__ . '/BaseDao.php';

class FavoritesDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "favorites";
        parent::__construct($this->table_name);
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM ' . $this->table_name, []);
    }

    public function get_user_favorites($user_id)
    {
        return $this->query(
            'SELECT f.*, l.*, i.image_url 
            FROM ' . $this->table_name . ' f 
            JOIN listing l ON f.listing_id = l.listing_id 
            LEFT JOIN (
                SELECT listing_id, MIN(image_url) as image_url 
                FROM image 
                GROUP BY listing_id
            ) i ON l.listing_id = i.listing_id 
            WHERE f.user_id = :user_id',
            ['user_id' => $user_id]
        );
    }

    public function is_favorite($user_id, $listing_id)
    {
        $result = $this->query_unique(
            'SELECT * FROM ' . $this->table_name . ' 
            WHERE user_id = :user_id AND listing_id = :listing_id',
            [
                'user_id' => $user_id,
                'listing_id' => $listing_id
            ]
        );
        return !empty($result);
    }

    public function add_favorite($user_id, $listing_id)
    {
        if (!$this->is_favorite($user_id, $listing_id)) {
            return $this->add([
                'user_id' => $user_id,
                'listing_id' => $listing_id
            ]);
        }
        return null;
    }

    public function remove_favorite($user_id, $listing_id)
    {
        return $this->query(
            'DELETE FROM ' . $this->table_name . ' 
            WHERE user_id = :user_id AND listing_id = :listing_id',
            [
                'user_id' => $user_id,
                'listing_id' => $listing_id
            ]
        );
    }
} 