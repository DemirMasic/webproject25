<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/MessageDao.php';
require_once __DIR__ . '/../dao/ListingDao.php';
require_once __DIR__ . '/../dao/UserDao.php';

class MessageService extends BaseService {
    private $messageDao;
    private $listingDao;
    private $userDao;

    public function __construct() {
        $this->messageDao = new MessageDao();
        $this->listingDao = new ListingDao();
        $this->userDao = new UserDao();
        parent::__construct($this->messageDao);
    }

    public function send_message($user_id, $listing_id, $content) {
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

        // Prevent users from messaging themselves
        if ($listing['user_id'] == $user_id) {
            throw new Exception("Cannot send message to your own listing");
        }

        // Validate message content
        if (empty($content)) {
            throw new Exception("Message content cannot be empty");
        }

        if (strlen($content) > 1000) {
            throw new Exception("Message content cannot exceed 1000 characters");
        }

        // Sanitize message content
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        return $this->messageDao->add_message($user_id, $listing_id, $content);
    }

    public function get_conversation($user1_id, $user2_id) {
        // Validate both users exist
        $user1 = $this->userDao->get_by_id($user1_id);
        $user2 = $this->userDao->get_by_id($user2_id);
        
        if (!$user1 || !$user2) {
            throw new Exception("One or both users not found");
        }

        return $this->messageDao->get_conversation($user1_id, $user2_id);
    }

    public function get_messages_by_listing($listing_id) {
        // Validate listing exists
        $listing = $this->listingDao->get_by_id($listing_id);
        if (!$listing) {
            throw new Exception("Listing not found");
        }

        return $this->messageDao->get_messages_by_listing($listing_id);
    }

    public function get_user_messages($user_id) {
        // Validate user exists
        $user = $this->userDao->get_by_id($user_id);
        if (!$user) {
            throw new Exception("User not found");
        }

        return $this->messageDao->get_user_messages($user_id);
    }

    public function delete_message($message_id, $user_id) {
        // Get the message
        $message = $this->messageDao->get_by_id($message_id);
        if (!$message) {
            throw new Exception("Message not found");
        }

        // Check if user is authorized to delete the message
        if ($message['user_id'] != $user_id) {
            throw new Exception("Not authorized to delete this message");
        }

        return $this->messageDao->delete($message_id);
    }
}
?> 