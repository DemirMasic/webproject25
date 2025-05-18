<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ImageDao.php';
require_once __DIR__ . '/../dao/ListingDao.php';

class ImageService extends BaseService {
    private $imageDao;
    private $listingDao;

    public function __construct() {
        $this->imageDao = new ImageDao();
        $this->listingDao = new ListingDao();
        parent::__construct($this->imageDao);
    }

    public function add_image($listing_id, $image_url) {
        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        // Validate image URL
        if (empty($image_url)) {
            throw new Exception("Image URL cannot be empty");
        }

        // Validate image URL format
        if (!filter_var($image_url, FILTER_VALIDATE_URL)) {
            throw new Exception("Invalid image URL format");
        }

        // Validate image file extension
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $extension = strtolower(pathinfo($image_url, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowed_extensions)) {
            throw new Exception("Invalid image file type. Allowed types: " . implode(', ', $allowed_extensions));
        }

        return $this->imageDao->add_image($listing_id, $image_url);
    }

    public function get_listing_images($listing_id) {
        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        return $this->imageDao->get_by_listing($listing_id);
    }

    public function delete_listing_images($listing_id) {
        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        return $this->imageDao->delete_by_listing($listing_id);
    }

    public function delete_image($image_id) {
        // Validate image exists
        $image = $this->imageDao->get_by_id($image_id);
        if (!$image) {
            throw new Exception("Image not found");
        }

        return $this->imageDao->delete($image_id);
    }
}
?> 