<?php
require_once __DIR__ . '/BaseDao.php';

class ImageDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "image";
        parent::__construct($this->table_name);
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM ' . $this->table_name, []);
    }

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE image_id=:id', ['id' => $id]);
    }

    public function get_by_listing($listing_id)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE listing_id=:listing_id', ['listing_id' => $listing_id]);
    }

    public function add_image($listing_id, $image_url)
    {
        return $this->add([
            'listing_id' => $listing_id,
            'image_url' => $image_url
        ]);
    }

    public function delete_by_listing($listing_id)
    {
        return $this->query('DELETE FROM ' . $this->table_name . ' WHERE listing_id=:listing_id', ['listing_id' => $listing_id]);
    }
} 