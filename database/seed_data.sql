-- Insert users
INSERT INTO users (username, email, `password`, `role`, `status`)
VALUES ('admin', 'admin@example.com', 'password123', 'admin', 'active'),
       ('user1', 'user1@example.com', 'password456', 'member', 'active');

-- Insert menus
INSERT INTO menus (menu_name, `description`)
VALUES ('Main meals', 'Hundreds of main dish recipes. Choose from top-rated comfort food, healthy, and vegetarian options. Find your main dish star now!'),
       ('Puddings', 'Desserts, afters, pudding … whatever you call them, they’re always welcome to finish off an amazing dinner party main meal or casual Summer lunch. Handmade at COOK Puddings in Somerset, our easy puddings delivered means your special meal will end with big smiles and clean plates all round. Also perfect if you need some dinner party desserts to round out an evening of entertainment, our desserts delivered are both tasty and easy to prepare (either cooked from the freezer or defrosted and served). So order some desserts online, or pop into one of our shops and get your pud on!'),
       ("Entertaining", "Perfect party food prepared by hand, from canapes and starters to centrepieces and puddings. Discover the joys of hassle-free entertaining!"),
       ("Meal boxes", "Give someone you love time out of the kitchen and the gift of good food with one of our COOK meal boxes. The ideal one-click solution to stock up the freezer, one of our meal boxes delivered is the perfect present for new parents or those going through a difficult time. We have put together these combinations of delicious dishes in our food boxes to make the decision-making process that little bit easier....and to make your life even easier, we’ve created a meals subscription service so you can make the most out of the convenience of our meal box delivery.");

-- Insert categories
INSERT INTO `categories` (`category_id`, `menu_id`, `category_name`, `description`)
VALUES (NULL, '1', 'Portion size', 'Portion size'),
       (NULL, '1', 'By protein', 'Categorized by protein'),
       (NULL, '5', 'Party and celebration', 'Dishes prepared for party or celebration.'),
       (NULL, '5', 'Party dishes.', 'Prepare some special dished for your party.')
       (NULL, '6', 'Portion size', 'Portion size')
       (NULL, '4', 'Portion size', 'Portion size')
       (NULL, '4', 'Cold puddings', 'Cold puddings')
       (NULL, '4', 'Hot puddings', 'Hot puddings')
       (NULL, '4', 'Portion size', 'Portion size');


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
