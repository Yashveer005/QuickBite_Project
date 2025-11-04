
-- food_db.sql: Database schema + sample data for QuickBite (Online Food Ordering System)

CREATE DATABASE IF NOT EXISTS food_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE food_db;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255),
  phone VARCHAR(15),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS restaurants (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  address VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  rest_id INT,
  name VARCHAR(100),
  description TEXT,
  price DECIMAL(10,2),
  image VARCHAR(255),
  FOREIGN KEY (rest_id) REFERENCES restaurants(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  item_id INT,
  quantity INT DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (item_id) REFERENCES menu_items(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  total DECIMAL(10,2),
  status VARCHAR(50) DEFAULT 'Placed',
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Sample restaurants
INSERT INTO restaurants (name, address) VALUES
('Pizza Palace', 'Sector 62, Noida'),
('Burger Haven', 'Sector 18, Noida'),
('Desi Dhaba', 'Sector 12, Noida');

-- Sample menu items
INSERT INTO menu_items (rest_id, name, description, price, image) VALUES
(1, 'Margherita Pizza', 'Classic cheese margherita', 299.00, 'pizza1.jpg'),
(1, 'Veggie Supreme', 'Loaded vegetarian pizza', 399.00, 'pizza2.jpg'),
(2, 'Cheese Burger', 'Soft bun with cheese slice', 199.00, 'burger1.jpg'),
(2, 'Double Patty Burger', 'Extra patty for more protein', 249.00, 'burger2.jpg'),
(3, 'Paneer Butter Masala', 'Creamy paneer gravy', 220.00, 'paneer.jpg'),
(3, 'Dal Makhani', 'Slow-cooked black lentils', 180.00, 'dal.jpg');
