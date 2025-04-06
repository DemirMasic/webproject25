<?php
require_once __DIR__ . '/BaseDao.php';

class ListingDao extends BaseDao
{
    protected $table_name;

    public function __construct()
    {
        $this->table_name = "listing";
        parent::__construct($this->table_name);
    }

    public function get_all()
    {
        return $this->query('SELECT * FROM ' . $this->table_name, []);
    }

    public function get_by_id($id)
    {
        return $this->query_unique('SELECT * FROM ' . $this->table_name . ' WHERE listing_id=:id', ['id' => $id]);
    }

    public function get_by_user($user_id)
    {
        return $this->query('SELECT * FROM ' . $this->table_name . ' WHERE user_id=:user_id', ['user_id' => $user_id]);
    }

    public function search_listings($params)
    {
        $query = 'SELECT * FROM ' . $this->table_name . ' WHERE 1=1';
        $parameters = [];

        if (isset($params['brand'])) {
            $query .= ' AND brand=:brand';
            $parameters['brand'] = $params['brand'];
        }
        if (isset($params['model'])) {
            $query .= ' AND model=:model';
            $parameters['model'] = $params['model'];
        }
        if (isset($params['year_from'])) {
            $query .= ' AND year>=:year_from';
            $parameters['year_from'] = $params['year_from'];
        }
        if (isset($params['year_to'])) {
            $query .= ' AND year<=:year_to';
            $parameters['year_to'] = $params['year_to'];
        }
        if (isset($params['price_from'])) {
            $query .= ' AND price>=:price_from';
            $parameters['price_from'] = $params['price_from'];
        }
        if (isset($params['price_to'])) {
            $query .= ' AND price<=:price_to';
            $parameters['price_to'] = $params['price_to'];
        }
        if (isset($params['mileage_from'])) {
            $query .= ' AND mileage>=:mileage_from';
            $parameters['mileage_from'] = $params['mileage_from'];
        }
        if (isset($params['mileage_to'])) {
            $query .= ' AND mileage<=:mileage_to';
            $parameters['mileage_to'] = $params['mileage_to'];
        }
        if (isset($params['gearbox'])) {
            $query .= ' AND gearbox=:gearbox';
            $parameters['gearbox'] = $params['gearbox'];
        }
        if (isset($params['fuel'])) {
            $query .= ' AND fuel=:fuel';
            $parameters['fuel'] = $params['fuel'];
        }
        if (isset($params['drivetrain'])) {
            $query .= ' AND drivetrain=:drivetrain';
            $parameters['drivetrain'] = $params['drivetrain'];
        }

        return $this->query($query, $parameters);
    }
} 