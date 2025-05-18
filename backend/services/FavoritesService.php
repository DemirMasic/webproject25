<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/FavoritesDao.php';
require_once __DIR__ . '/../dao/ListingDao.php';
require_once __DIR__ . '/../dao/UserDao.php';

class FavoritesService extends BaseService {
    private $favoritesDao;
    private $listingDao;
    private $userDao;

    public function __construct() {
        $this->favoritesDao = new FavoritesDao();
        $this->listingDao = new ListingDao();
        $this->userDao = new UserDao();
        parent::__construct($this->favoritesDao);
    }

    public function add_favorite($user_id, $listing_id) {
        // Validate user exists
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        // Prevent users from favoriting their own listings
        if ($listing['user_id'] == $user_id) {
            throw new Exception("Cannot favorite your own listing");
        }

        // Check if already favorited
        if ($this->favoritesDao->is_favorite($user_id, $listing_id)) {
            throw new Exception("Listing is already in favorites");
        }

        return $this->favoritesDao->add_favorite($user_id, $listing_id);
    }

    public function remove_favorite($user_id, $listing_id) {
        // Validate user exists
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        // Check if favorited
        if (!$this->favoritesDao->is_favorite($user_id, $listing_id)) {
            throw new Exception("Listing is not in favorites");
        }

        return $this->favoritesDao->remove_favorite($user_id, $listing_id);
    }

    public function get_user_favorites($user_id) {
        // Validate user exists
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        return $this->favoritesDao->get_user_favorites($user_id);
    }

    public function is_favorite($user_id, $listing_id) {
        // Validate user exists
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        return $this->favoritesDao->is_favorite($user_id, $listing_id);
    }
}
?> 