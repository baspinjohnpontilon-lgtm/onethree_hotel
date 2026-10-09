CREATE DATABASE IF NOT EXISTS onethree_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE onethree_db;

CREATE TABLE IF NOT EXISTS users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 full_name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS rooms (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 type ENUM('Standard','Studio','Suite','Penthouse') NOT NULL,
 price DECIMAL(10,2) NOT NULL,
 sqft INT NOT NULL DEFAULT 300,
 floor_label VARCHAR(40) NOT NULL,
 max_guests TINYINT UNSIGNED NOT NULL DEFAULT 2,
 description VARCHAR(500) NOT NULL,
 image_url VARCHAR(500) NOT NULL,
 status ENUM('available','maintenance') NOT NULL DEFAULT 'available',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reservations (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 reservation_code VARCHAR(24) NOT NULL UNIQUE,
 user_id INT UNSIGNED NULL,
 room_id INT UNSIGNED NOT NULL,
 guest_name VARCHAR(120) NOT NULL,
 guest_email VARCHAR(190) NOT NULL,
 check_in DATE NOT NULL,
 check_out DATE NOT NULL,
 guests TINYINT UNSIGNED NOT NULL DEFAULT 2,
 nights SMALLINT UNSIGNED NOT NULL,
 room_subtotal DECIMAL(10,2) NOT NULL,
 tax_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
 total_amount DECIMAL(10,2) NOT NULL,
 special_requests TEXT NULL,
 payment_method VARCHAR(50) NOT NULL DEFAULT 'Pay at hotel',
 payment_status ENUM('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
 status ENUM('pending','confirmed','rejected','cancelled','completed') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_reservation_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
 CONSTRAINT fk_reservation_room FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT,
 INDEX idx_reservation_dates (room_id, check_in, check_out, status)
) ENGINE=InnoDB;

INSERT INTO rooms (name,type,price,sqft,floor_label,max_guests,description,image_url,status) VALUES
('Classic King','Standard',329,380,'4–8',2,'A calm, considered room with a king bed and warm natural textures.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85','available'),
('Deluxe Suite','Suite',559,620,'12–18',3,'A spacious suite with a separate sitting area for slower mornings.','https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=85','available'),
('Grand Penthouse','Penthouse',1350,1400,'28–30',4,'A generous private retreat with refined details and room to unwind.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85','available'),
('Garden Studio','Studio',249,290,'2–3',2,'A quiet studio designed for a simple, comfortable stay.','https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1200&q=85','available'),
('Executive Twin','Standard',305,350,'6–10',2,'Twin beds and a clean, functional layout for shared stays.','https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=1200&q=85','available'),
('Corner Junior Suite','Suite',429,510,'10–15',3,'A bright corner suite with extra space and a relaxed lounge nook.','https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=85','available'),
('Heritage Queen','Standard',315,360,'3–7',2,'An understated room with a queen bed and a warm, welcoming mood.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85','available'),
('Terrace Studio','Studio',285,330,'5–8',2,'A compact studio with a little more breathing room for a restful visit.','https://images.unsplash.com/photo-1616594039964-ae9021a400a0?auto=format&fit=crop&w=1200&q=85','available'),
('Lambunao Suite','Suite',625,710,'16–21',3,'A signature suite inspired by the calm of a thoughtful local escape.','https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=85','available'),
('Gallery Suite','Suite',695,780,'19–24',3,'An elevated suite with generous proportions and a gallery-like feel.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85','available'),
('OneThree Residence','Penthouse',1580,1750,'30',4,'The most spacious OneThree stay, made for special occasions.','https://images.unsplash.com/photo-1611892440504-42a792e24d32?auto=format&fit=crop&w=1200&q=85','available');
