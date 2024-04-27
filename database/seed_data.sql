-- Insert users
INSERT INTO users (username, email, password, role, status)
VALUES ('admin', 'admin@example.com', 'password123', 'admin', 'active'),
       ('user1', 'user1@example.com', 'password456', 'member', 'active'),
       ('user2', 'user2@example.com', 'password789', 'member', 'active'),
       ('user3', 'user3@example.com', 'password012', 'member', 'active');

-- Insert menus
INSERT INTO menus (menu_name, description)
VALUES ('Food', 'A variety of delicious dishes'),
       ('Drinks', 'Refreshing beverages to quench your thirst'),
       ('Desserts', 'Indulge in our sweet treats'),
       ('Snacks', 'Perfect bites for any time of day');

-- Insert categories
INSERT INTO categories (menu_id, description)
VALUES (1, 'Main Courses'),
       (1, 'Desserts'),
       (2, 'Coffee'),
       (2, 'Tea'),
       (3, 'Cakes'),
       (3, 'Ice Cream'),
       (4, 'Salty Snacks'),
       (4, 'Sweet Snacks');

-- Insert types
INSERT INTO types (category_id, description)
VALUES (1, 'Burgers'),
       (1, 'Pastas'),
       (3, 'Chocolate Cakes'),
       (3, 'Fruit Tarts'),
       (5, 'Espresso-based'),
       (5, 'Filter Coffee'),
       (7, 'Potato Chips'),
       (7, 'Pretzels'),
       (1, 'Soups'),
       (1, 'Salads');

-- Insert products
INSERT INTO products (type_id, product_name, description)
VALUES (1, 'Cheeseburger', 'A classic cheeseburger with juicy patty, melted cheese, and fresh toppings'),
       (2, 'Spaghetti Bolognese', 'A hearty pasta dish with ground meat sauce'),
       (3, 'Chocolate Cake', 'A rich and decadent chocolate cake'),
       (4, 'Apple Pie', 'A classic American dessert with a flaky crust and sweet apple filling'),
       (5, 'Latte', 'A smooth and creamy coffee drink with steamed milk'),
       (6, 'Black Coffee', 'A strong and bold cup of black coffee'),
       (7, 'Classic Potato Chips', 'Salted and crispy potato chips'),
       (8, 'Soft Pretzels', 'Warm and buttery pretzels with a hint of salt'),
       (9, 'Tomato Soup', 'A classic creamy tomato soup'),
       (10, 'Caesar Salad', 'A refreshing salad with romaine lettuce, croutons, and parmesan cheese');

-- Insert serves (at least 4 rows per product)
INSERT INTO serves (product_id, serve_name, price, discount, status, instruction)
VALUES (1, 'Cheeseburger (Single)', 200000, 0, 1, ''),
       (1, 'Cheeseburger (Double)', 300000, 0, 1, ''),
       (2, 'Spaghetti Bolognese (Regular)', 150000, 0, 1, ''),
       (2, 'Spaghetti Bolognese (Large)', 200000, 0, 1, ''),
       (3, 'Chocolate Cake (Whole)', 350000, 0, 1, ''),
       (3, 'Chocolate Cake (Slice)', 100000, 0, 1, ''),
       (4, 'Apple Pie (Whole)', 250000, 0, 1, ''),
       (4, 'Apple Pie (Slice)', 80000, 0, 1, ''),
       (5, 'Latte (Regular)', 50000, 0, 1, ''),
       (5, 'Latte (Large)', 60000, 0, 1, ''),
       (6, 'Black Coffee (Regular)', 30000, 0, 1, ''),
       (6, 'Black Coffee (Large)', 40000, 0, 1, ''),
       (7, 'Classic Potato Chips (Regular)', 50000, 0, 1, ''),
       (7, 'Classic Potato Chips (Large)', 80000, 0, 1, ''),
       (8, 'Soft Pretzels (2 Pieces)', 60000, 0, 1, ''),
       (8, 'Soft Pretzels (4 Pieces)', 100000, 0, 1, ''),
       (9, 'Tomato Soup (Regular)', 80000, 0, 1, ''),
       (9, 'Tomato Soup (Large)', 120000, 0, 1, ''),
       (10, 'Caesar Salad (Regular)', 100000, 0, 1, ''),
       (10, 'Caesar Salad (Large)', 150000, 0, 1, '');

-- Insert nutritions
INSERT INTO nutritions (serve_id, typical_values, per_100g, per_portion)
VALUES (1, 'Calories: 500kcal, Protein: 30g, Fat: 20g, Carbs: 50g', 'Calories: 250kcal, Protein: 15g, Fat: 10g, Carbs: 25g', 'Calories: 500kcal, Protein: 30g, Fat: 20g, Carbs: 50g'),
       (2, 'Calories: 600kcal, Protein: 40g, Fat: 30g, Carbs: 60g', 'Calories: 300kcal, Protein: 20g, Fat: 15g, Carbs: 30g', 'Calories: 600kcal, Protein: 40g, Fat: 30g, Carbs: 60g'),
       (3, 'Calories: 450kcal, Protein: 10g, Fat: 25g, Carbs: 50g', 'Calories: 112.5kcal, Protein: 2.5g, Fat: 6.25g, Carbs: 12.5g', 'Calories: 450kcal, Protein: 10g, Fat: 25g, Carbs: 50g'),
       (4, 'Calories: 350kcal, Protein: 5g, Fat: 15g, Carbs: 45g', 'Calories: 87.5kcal, Protein: 1.25g, Fat: 3.75g, Carbs: 11.25g', 'Calories: 400kcal, Protein: 20g, Fat: 35g, Carbs: 40g'),

INSERT INTO ingredients (serve_id, ingredient_name, percentage)
VALUES (1, 'Beef patty', 60),
       (1, 'Cheddar cheese', 20),
       (1, 'Lettuce', 10),
       (1, 'Tomato', 5),
       (1, 'Onion', 5),
       (2, 'Ground beef', 40),
       (2, 'Tomato sauce', 30),
       (2, 'Pasta', 20),
       (2, 'Herbs and spices', 10),
       (3, 'Chocolate cake', 50),
       (3, 'Eggs', 20),
       (3, 'Sugar', 15),
       (3, 'Flour', 10),
       (3, 'Cocoa powder', 5),
       (4, 'Apple filling', 60),
       (4, 'Pie crust', 20),
       (4, 'Cinnamon', 10),
       (4, 'Nutmeg', 5),
       (4, 'Sugar', 5),
       (5, 'Espresso', 40),
       (5, 'Steamed milk', 60),
       (6, 'Ground coffee', 100),
       (7, 'Potato', 100),
       (8, 'Pretzel dough', 100),
       (9, 'Tomato puree', 80),
       (9, 'Vegetable broth', 20),
       (10, 'Romaine lettuce', 60),
       (10, 'Caesar dressing', 20),
       (10, 'Croutons', 10),
       (10, 'Parmesan cheese', 10);

INSERT INTO comments (user_id, product_id, content, created_at, updated_at, like_count)
VALUES (1, 1, "This cheeseburger is amazing! The patty is so juicy and the cheese is perfectly melted.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 10),
       (2, 1, "Love this cheeseburger! It's my go-to comfort food.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 5),
       (1, 2, "This spaghetti is delicious! The sauce is flavorful and the pasta is cooked to perfection.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 12),
       (3, 2, "I highly recommend this spaghetti! It's a hearty and satisfying meal.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 8),
       (1, 3, "This chocolate cake is heaven! It's so rich and decadent.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 15),
       (2, 3, "This chocolate cake is a must-try! It's the perfect dessert for any occasion.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 7),
       (3, 4, "This apple pie is so good! The crust is flaky and the filling is sweet and tart.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 9),
       (1, 4, "I love this apple pie! It's a classic American dessert done right.", '2024-04-27 08:07:00', '2024-04-27 08:07:00', 5),

INSERT INTO orders (user_id, pre_total_cost, promotion_cost, delivery_cost, final_cost, delivery_date, leave_order_when_absent, payment_method)
VALUES (1, 250000, 50000, 20000, 220000, '2024-04-25', 'Yes', 'Cash on delivery'),
       (2, 300000, 0, 20000, 280000, '2024-04-28', 'No', 'Credit card'),
       (3, 180000, 20000, 20000, 160000, '2024-04-27', 'Yes', 'Cash on delivery'),
       (4, 400000, 100000, 30000, 310000, '2024-04-26', 'No', 'Mobile wallet');

INSERT INTO order_products (order_id, serve_id, quantity, total_product_cost)
VALUES (1, 1, 1, 200000),
       (1, 3, 1, 350000),
       (2, 2, 2, 300000),
       (2, 5, 1, 60000),
       (3, 4, 1, 250000),
       (3, 8, 2, 120000),
       (4, 1, 2, 400000),
       (4, 10, 1, 100000);

-- -- Insert users
-- INSERT INTO users (username, email, `password`, `role`, `status`)
-- VALUES ('admin', 'admin@example.com', 'password123', 'admin', 'active'),
--        ('user1', 'user1@example.com', 'password456', 'member', 'active');

-- -- Insert menus
-- INSERT INTO menus (menu_name, `description`)
-- VALUES ('Main meals', 'Hundreds of main dish recipes. Choose from top-rated comfort food, healthy, and vegetarian options. Find your main dish star now!'),
--        ('Puddings', 'Desserts, afters, pudding … whatever you call them, they’re always welcome to finish off an amazing dinner party main meal or casual Summer lunch. Handmade at COOK Puddings in Somerset, our easy puddings delivered means your special meal will end with big smiles and clean plates all round. Also perfect if you need some dinner party desserts to round out an evening of entertainment, our desserts delivered are both tasty and easy to prepare (either cooked from the freezer or defrosted and served). So order some desserts online, or pop into one of our shops and get your pud on!'),
--        ("Entertaining", "Perfect party food prepared by hand, from canapes and starters to centrepieces and puddings. Discover the joys of hassle-free entertaining!"),
--        ("Meal boxes", "Give someone you love time out of the kitchen and the gift of good food with one of our COOK meal boxes. The ideal one-click solution to stock up the freezer, one of our meal boxes delivered is the perfect present for new parents or those going through a difficult time. We have put together these combinations of delicious dishes in our food boxes to make the decision-making process that little bit easier....and to make your life even easier, we’ve created a meals subscription service so you can make the most out of the convenience of our meal box delivery.");

-- -- Insert categories
-- INSERT INTO `categories` (`category_id`, `menu_id`, `category_name`, `description`)
-- VALUES (NULL, '1', 'Portion size', 'Portion size'),
--        (NULL, '1', 'By protein', 'Categorized by protein'),
--        (NULL, '5', 'Party and celebration', 'Dishes prepared for party or celebration.'),
--        (NULL, '5', 'Party dishes.', 'Prepare some special dished for your party.')
--        (NULL, '6', 'Portion size', 'Portion size')
--        (NULL, '4', 'Portion size', 'Portion size')
--        (NULL, '4', 'Cold puddings', 'Cold puddings')
--        (NULL, '4', 'Hot puddings', 'Hot puddings')
--        (NULL, '4', 'Portion size', 'Portion size');


-- -- Insert types (assuming some categories are already inserted)
-- INSERT INTO types (category_id, description)
-- VALUES (1, 'Burgers'),
--        (1, 'Pastas'),
--        (2, 'Cakes'),
--        (2, 'Ice Cream'),
--        (3, 'Espresso-based'),
--        (3, 'Filter Coffee'),
--        (4, 'Black Tea'),
--        (4, 'Green Tea');

-- -- Insert products (assuming some types are already inserted)
-- INSERT INTO products (type_id, product_name, description)
-- VALUES (1, 'Cheeseburger', 'A classic cheeseburger with juicy patty, melted cheese, and fresh toppings'),
--        (2, 'Spaghetti Bolognese', 'A hearty pasta dish with ground meat sauce'),
--        (3, 'Chocolate Cake', 'A rich and decadent chocolate cake'),
--        (4, 'Vanilla Ice Cream', 'A classic and creamy vanilla ice cream'),
--        (5, 'Latte', 'A smooth and creamy coffee drink with steamed milk'),
--        (6, 'Black Coffee', 'A strong and bold cup of black coffee'),
--        (7, 'Earl Grey Tea', 'A classic black tea with a distinctive bergamot flavor'),
--        (8, 'Green Tea with Jasmine', 'A fragrant green tea infused with jasmine flowers');
