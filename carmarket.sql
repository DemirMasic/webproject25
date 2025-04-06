
CREATE TABLE `User` (
    user_id   INT AUTO_INCREMENT PRIMARY KEY,
    username  VARCHAR(50) NOT NULL,
    email     VARCHAR(100) NOT NULL,
    password  VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `Listing` (
    listing_id  INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    brand       VARCHAR(50) NOT NULL,
    model       VARCHAR(50) NOT NULL,
    year        INT NOT NULL,
    mileage     INT NOT NULL,
    power       INT NOT NULL,
    price       INT NOT NULL,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    gearbox     VARCHAR(50),
    fuel        VARCHAR(50),
    drivetrain  VARCHAR(50),
    CONSTRAINT fk_listing_user
        FOREIGN KEY (user_id) REFERENCES `User`(user_id)
);


CREATE TABLE `Message` (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    user_id    INT NOT NULL,
    content    TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_message_listing
        FOREIGN KEY (listing_id) REFERENCES `Listing`(listing_id),
    CONSTRAINT fk_message_user
        FOREIGN KEY (user_id) REFERENCES `User`(user_id)
);


CREATE TABLE `Image` (
    image_id   INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    image_url  VARCHAR(255) NOT NULL,
    CONSTRAINT fk_image_listing
        FOREIGN KEY (listing_id) REFERENCES `Listing`(listing_id)
);


CREATE TABLE `Favorites` (
    user_id    INT NOT NULL,
    listing_id INT NOT NULL,
    PRIMARY KEY (user_id, listing_id),
    CONSTRAINT fk_favorites_user
        FOREIGN KEY (user_id) REFERENCES `User`(user_id),
    CONSTRAINT fk_favorites_listing
        FOREIGN KEY (listing_id) REFERENCES `Listing`(listing_id)
);
