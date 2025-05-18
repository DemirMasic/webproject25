<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ListingDao.php';
require_once __DIR__ . '/../dao/UserDao.php';

class ListingService extends BaseService {
    private $listingDao;
    private $userDao;

    public function __construct() {
        $this->listingDao = new ListingDao();
        $this->userDao = new UserDao();
        parent::__construct($this->listingDao);
    }

    public function create_listing($listing) {
        // Validate required fields
        $required_fields = ['user_id', 'brand', 'model', 'year', 'price', 'mileage', 'gearbox', 'fuel', 'drivetrain'];
        foreach ($required_fields as $field) {
            if (empty($listing[$field])) {
                throw new Exception("Field '$field' is required");
            }
        }

        // Validate user exists
        $user = $this->userDao->get_by_id($listing['user_id']);
        if (!$user) {
            throw new Exception("User not found");
        }

        // Validate numeric fields
        $numeric_fields = ['year', 'price', 'mileage'];
        foreach ($numeric_fields as $field) {
            if (!is_numeric($listing[$field]) || $listing[$field] < 0) {
                throw new Exception("Field '$field' must be a positive number");
            }
        }

        // Validate year range
        $current_year = date('Y');
        if ($listing['year'] < 1900 || $listing['year'] > $current_year) {
            throw new Exception("Year must be between 1900 and current year");
        }

        // Validate price range
        if ($listing['price'] <= 0 || $listing['price'] > 1000000) {
            throw new Exception("Price must be between 0 and 1,000,000");
        }

        // Validate mileage range
        if ($listing['mileage'] < 0 || $listing['mileage'] > 1000000) {
            throw new Exception("Mileage must be between 0 and 1,000,000");
        }

        // Add creation timestamp
        $listing['created_at'] = date('Y-m-d H:i:s');

        return $this->listingDao->add($listing);
    }

    public function update_listing($id, $listing) {
        // Check if listing exists
        $existing_listing = $this->listingDao->get_by_id($id);
        if (!$existing_listing) {
            throw new Exception("Listing not found");
        }

        // Validate numeric fields if they are being updated
        $numeric_fields = ['year', 'price', 'mileage'];
        foreach ($numeric_fields as $field) {
            if (isset($listing[$field])) {
                if (!is_numeric($listing[$field]) || $listing[$field] < 0) {
                    throw new Exception("Field '$field' must be a positive number");
                }
            }
        }

        // Validate year range if being updated
        if (isset($listing['year'])) {
            $current_year = date('Y');
            if ($listing['year'] < 1900 || $listing['year'] > $current_year) {
                throw new Exception("Year must be between 1900 and current year");
            }
        }

        // Validate price range if being updated
        if (isset($listing['price'])) {
            if ($listing['price'] <= 0 || $listing['price'] > 1000000) {
                throw new Exception("Price must be between 0 and 1,000,000");
            }
        }

        // Validate mileage range if being updated
        if (isset($listing['mileage'])) {
            if ($listing['mileage'] < 0 || $listing['mileage'] > 1000000) {
                throw new Exception("Mileage must be between 0 and 1,000,000");
            }
        }

        return $this->listingDao->update($listing, $id, 'listing_id');
    }

    public function get_user_listings($user_id) {
        // Validate user exists
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        return $this->listingDao->get_by_user($user_id);
    }

    public function search_listings($params) {
        // Validate search parameters
        if (isset($params['year_from']) && isset($params['year_to'])) {
            if ($params['year_from'] > $params['year_to']) {
                throw new Exception("Year range is invalid");
            }
        }

        if (isset($params['price_from']) && isset($params['price_to'])) {
            if ($params['price_from'] > $params['price_to']) {
                throw new Exception("Price range is invalid");
            }
        }

        if (isset($params['mileage_from']) && isset($params['mileage_to'])) {
            if ($params['mileage_from'] > $params['mileage_to']) {
                throw new Exception("Mileage range is invalid");
            }
        }

        return $this->listingDao->search_listings($params);
    }

    public function delete_listing($id) {
        $listing = $this->listingDao->get_by_id($id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        return $this->listingDao->delete($id);
    }
}
?> 