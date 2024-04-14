-- Insert users
INSERT INTO users (username, email, password, role, status)
VALUES ('admin', 'admin@example.com', 'password123', 'admin', 'active'),
       ('user1', 'user1@example.com', 'password456', 'member', 'active');

-- Insert menus
INSERT INTO menus (menu_name, description)
VALUES ('Food', 'A variety of delicious dishes'),
       ('Drinks', 'Refreshing beverages to quench your thirst');

-- Insert categories
INSERT INTO categories (menu_id, description)
VALUES (1, 'Main Courses'),
       (1, 'Desserts'),
       (2, 'Coffee'),
       (2, 'Tea');

-- Insert types (assuming some categories are already inserted)
INSERT INTO types (category_id, description)
VALUES (1, 'Burgers'),
       (1, 'Pastas'),
       (2, 'Cakes'),
       (2, 'Ice Cream'),
       (3, 'Espresso-based'),
       (3, 'Filter Coffee'),
       (4, 'Black Tea'),
       (4, 'Green Tea');

-- Insert products (assuming some types are already inserted)
INSERT INTO products (type_id, product_name, description)
VALUES (1, 'Cheeseburger', 'A classic cheeseburger with juicy patty, melted cheese, and fresh toppings'),
       (2, 'Spaghetti Bolognese', 'A hearty pasta dish with ground meat sauce'),
       (3, 'Chocolate Cake', 'A rich and decadent chocolate cake'),
       (4, 'Vanilla Ice Cream', 'A classic and creamy vanilla ice cream'),
       (5, 'Latte', 'A smooth and creamy coffee drink with steamed milk'),
       (6, 'Black Coffee', 'A strong and bold cup of black coffee'),
       (7, 'Earl Grey Tea', 'A classic black tea with a distinctive bergamot flavor'),
       (8, 'Green Tea with Jasmine', 'A fragrant green tea infused with jasmine flowers');

-- You can add more data for other tables following the same format
