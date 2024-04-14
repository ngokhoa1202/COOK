CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'member',
    status VARCHAR(255) NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE menus (
    menu_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    menu_name VARCHAR(255) UNIQUE NOT NULL,
    description VARCHAR(255) NULL,
);

CREATE TABLE categories (
    category_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    description VARCHAR(255) NULL,
    FOREIGN KEY (menu_id) REFERENCES menus(menu_id),
);

CREATE TABLE types (
    type_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    description VARCHAR(255) NULL,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
);

CREATE TABLE products (
    product_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    description VARCHAR(255) NULL,
    FOREIGN KEY type_id REFERENCES types(type_id),
);

CREATE TABLE product_images (
    image_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL
    image_name VARCHAR(255) NOT NULL
    image_url VARCHAR(4096),
    FOREIGN KEY product_id REFERENCES products(product_id),
);

CREATE TABLE serves (
    serve_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    serve_name VARCHAR(255) NOT NULL,
    price INT NOT NULL DEFAULT 0,
    discount INT NOT NULL DEFAULT 0,
    status BOOLEAN NOT NULL DEFAULT 1,
    instruction varchar(8192) NULL,
    FOREIGN KEY product_id REFERENCES products(product_id),
);

CREATE TABLE nutritions (
    nutrition_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    serve_id INT NOT NULL,
    typical_values VARCHAR(255) NOT NULL,
    per_100g VARCHAR(32),
    per_portion VARCHAR(32),
    FOREIGN KEY serve_id REFERENCES serves(serve_id),
);

CREATE TABLE ingredients (
    ingredient_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    serve_id int NOT NULL,
    ingredient_name VARCHAR(255) NOT NULL,
    percentage VARCHAR(16),
    FOREIGN KEY serve_id REFERENCES serves (serve_id),
);

CREATE TABLE comments (
    comment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    content varchar(4096) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    like_count INT NOT NULL DEFAULT 0,
    FOREIGN KEY user_id REFERENCES users(user_id),
    FOREIGN KEY product_id REFERENCES products(product_id),
);

CREATE TABLE orders (
    order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pre_total_cost INT NOT NULL DEFAULT 0,
    promotion_cost INT NOT NULL DEFAULT 0,
    delivery_cost INT NOT NULL DEFAULT 0,
    final_cost INT NOT NULL DEFAULT 0,
    delivery_date DATE NOT NULL DEFAULT (CURRENT_DATE),
    leave_order_when_absent VARCHAR(255) NULL,
    payment_method VARCHAR(255) NULL,
    FOREIGN KEY user_id REFERENCES users(user_id),
)

CREATE TABLE order_products (
    order_product_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    serve_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_product_cost INT NOT NULL DEFAULT 0,
    FOREIGN KEY order_id REFERENCES orders(order_id),
    FOREIGN KEY serve_id REFERENCES serves(serve_id),
);



