---
CREATE TABLE IF NOT EXISTS categories(
    ID INT auto_increment NOT NULL,
    name VARCHAR(50) NOT NULL,
    defaultImage VARCHAR(100) NOT NULL,
    isConfigurationRequired INT DEFAULT 0,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
INSERT INTO categories (name, defaultImage, isConfigurationRequired)
VALUES (
        'Processori',
        'immagini/category_cpu.png',
        1
    ),
    (
        'Schede Madri',
        'immagini/category_motherboard.png',
        1
    ),
    (
        'Schede Video',
        'immagini/category_gpu.png',
        1
    ),
    (
        'Memorie RAM',
        'immagini/category_ram.png',
        1
    ),
    (
        'Alimentatori',
        'immagini/category_psu.png',
        1
    ),
    (
        'Archiviazione',
        'immagini/category_storage.png',
        1
    ),
    (
        'Case',
        'immagini/category_case.png',
        1
    ),
    (
        'Dissipatori',
        'immagini/category_cooler.png',
        1
    );
---
CREATE TABLE IF NOT EXISTS brands(
    ID INT auto_increment NOT NULL,
    categoryID INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    defaultImage VARCHAR(100) NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
INSERT INTO brands (categoryID, name, defaultImage)
VALUES (1, "intel", "immagini/brand_intel.png"),
    (1, "amd", "immagini/brand_amd.png"),
    (2, "asus", "immagini/brand_asus.png"),
    (2, "msi", "immagini/brand_msi.png"),
    (2, "gigabyte", "immagini/brand_gigabyte.png"),
    (2, "asrock", "immagini/brand_asrock.png"),
    (3, "asus", "immagini/brand_asus.png"),
    (3, "amd", "immagini/brand_amd.png"),
    (3, "msi", "immagini/brand_msi.png"),
    (3, "gigabyte", "immagini/brand_gigabyte.png"),
    (3, "asrock", "immagini/brand_asrock.png"),
    (4, "corsair", "immagini/brand_corsair.png"),
    (4, "g.skill", "immagini/brand_gskill.png"),
    (4, "kingston", "immagini/brand_kingston.png"),
    (4, "crucial", "immagini/brand_crucial.png"),
    (5, "corsair", "immagini/brand_corsair.png"),
    (5, "evga", "immagini/brand_evga.png"),
    (5, "seasonic", "immagini/brand_seasonic.png"),
    (5, "be quiet!", "immagini/brand_bequiet.png"),
    (6, "samsung", "immagini/brand_samsung.png"),
    (6, "seagate", "immagini/brand_seagate.png"),
    (6, "western digital", "immagini/brand_wd.png"),
    (6, "crucial", "immagini/brand_crucial.png"),
    (7, "corsair", "immagini/brand_corsair.png"),
    (7, "nzxt", "immagini/brand_nzxt.png"),
    (7, "thermaltake", "immagini/brand_tt.png"),
    (7, "cooler master", "immagini/brand_cm.png"),
    (8, "corsair", "immagini/brand_corsair.png"),
    (8, "nzxt", "immagini/brand_nzxt.png"),
    (8, "be quiet!", "immagini/brand_bequiet.png"),
    (8, "noctua", "immagini/brand_noctua.png");
---
CREATE TABLE IF NOT EXISTS components(
    ID INT auto_increment NOT NULL,
    name VARCHAR(50) NOT NULL,
    categoryID INT NOT NULL,
    brandID INT NOT NULL,
    description MEDIUMTEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    discountPercentage INT DEFAULT 0,
    availability INT DEFAULT 1,
    reviewUrl VARCHAR(100) DEFAULT NULL,
    image VARCHAR(100) DEFAULT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
INSERT INTO components (
        name,
        categoryID,
        brandID,
        description,
        price,
        discountPercentage,
        availability,
        reviewUrl,
        image
    )
VALUES (
        'Intel Core i7-9700K',
        1,
        1,
        '8-Core, 8-Thread Processor',
        329.99,
        0,
        1,
        'https://www.example.com/reviews/cpu-i7-9700k',
        'cpu_i7_9700k.jpg'
    ),
    (
        'AMD Ryzen 5 5600X',
        1,
        2,
        '6-Core, 12-Thread Processor',
        279.99,
        10,
        1,
        'https://www.example.com/reviews/cpu-ryzen-5600x',
        'cpu_ryzen_5600x.jpg'
    ),
    (
        'ASUS ROG Strix B450-F Gaming',
        2,
        3,
        'ATX Gaming Motherboard',
        149.99,
        0,
        1,
        'https://www.example.com/reviews/motherboard-strix-b450f',
        'mb_strix_b450f.jpg'
    ),
    (
        'MSI B550-A PRO',
        2,
        4,
        'ATX Motherboard',
        139.99,
        5,
        1,
        'https://www.example.com/reviews/motherboard-b550a-pro',
        'mb_b550a_pro.jpg'
    ),
    (
        'Gigabyte GeForce RTX 3060',
        3,
        9,
        '12GB GDDR6 Graphics Card',
        499.99,
        0,
        0,
        NULL,
        'gpu_rtx_3060.jpg'
    ),
    (
        'ASUS TUF Gaming GeForce GTX 1660 Super',
        3,
        3,
        '6GB GDDR6 Graphics Card',
        259.99,
        15,
        1,
        'https://www.example.com/reviews/gpu-gtx-1660-super',
        'gpu_gtx_1660_super.jpg'
    ),
    (
        'Corsair Vengeance RGB Pro',
        4,
        12,
        '16GB (2 x 8GB) DDR4 3200MHz RAM',
        119.99,
        0,
        1,
        'https://www.example.com/reviews/ram-vengeance-rgb-pro',
        'ram_vengeance_rgb_pro.jpg'
    ),
    (
        'G.Skill Ripjaws V Series',
        4,
        13,
        '16GB (2 x 8GB) DDR4 3600MHz RAM',
        129.99,
        0,
        1,
        'https://www.example.com/reviews/ram-ripjaws-v-series',
        'ram_ripjaws_v_series.jpg'
    ),
    (
        'Corsair RM750',
        5,
        17,
        '750W 80+ Gold Certified Power Supply',
        129.99,
        0,
        1,
        'https://www.example.com/reviews/psu-rm750',
        'psu_rm750.jpg'
    ),
    (
        'EVGA SuperNOVA 650 GA',
        5,
        18,
        '650W 80+ Gold Certified Power Supply',
        109.99,
        10,
        1,
        'https://www.example.com/reviews/psu-supernova-650ga',
        'psu_supernova_650ga.jpg'
    ),
    (
        'Samsung 970 EVO Plus',
        6,
        21,
        '500GB NVMe M.2 Solid State Drive',
        119.99,
        0,
        1,
        'https://www.example.com/reviews/storage-970-evo-plus',
        'storage_970_evo_plus.jpg'
    ),
    (
        'Seagate Barracuda 2TB',
        6,
        22,
        '3.5" Internal Hard Drive',
        64.99,
        0,
        1,
        'https://www.example.com/reviews/storage-barracuda-2tb',
        'storage_barracuda_2tb.jpg'
    ),
    (
        'NZXT H510',
        7,
        27,
        'ATX Mid Tower Case',
        79.99,
        0,
        1,
        'https://www.example.com/reviews/case-h510',
        'case_h510.jpg'
    ),
    (
        'Corsair 4000D Airflow',
        7,
        25,
        'ATX Mid Tower Case',
        94.99,
        0,
        1,
        'https://www.example.com/reviews/case-4000d-airflow',
        'case_4000d_airflow.jpg'
    ),
    (
        'Noctua NH-D15',
        8,
        32,
        'Dual-Tower CPU Cooler',
        99.99,
        0,
        1,
        'https://www.example.com/reviews/cooler-nh-d15',
        'cooler_nh_d15.jpg'
    ),
    (
        'Cooler Master Hyper 212 RGB',
        8,
        35,
        'Single-Tower CPU Cooler',
        44.99,
        20,
        1,
        'https://www.example.com/reviews/cooler-hyper-212-rgb',
        'cooler_hyper_212_rgb.jpg'
    );
---
CREATE TABLE IF NOT EXISTS componentsInfo(
    ID INT auto_increment NOT NULL,
    componentID INT NOT NULL,
    infoKey VARCHAR(50) NOT NULL,
    infoValue VARCHAR(50) NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
INSERT INTO componentsInfo (componentID, infoKey, infoValue)
VALUES -- Processor information
    (1, 'n_core', '8'),
    (1, 'n_thread', '8'),
    (1, 'speed', '4.9GHz'),
    (2, 'n_core', '6'),
    (2, 'n_thread', '12'),
    (2, 'speed', '4.6GHz'),
    -- Motherboard information
    (3, 'form_factor', 'ATX'),
    (3, 'socket', 'AM4'),
    (3, 'chipset', 'B450'),
    (4, 'form_factor', 'ATX'),
    (4, 'socket', 'AM4'),
    (4, 'chipset', 'B550'),
    -- Graphics card information
    (5, 'memory', '12GB GDDR6'),
    (5, 'core_clock', '1700MHz'),
    (6, 'memory', '6GB GDDR6'),
    (6, 'core_clock', '1830MHz'),
    -- RAM information
    (7, 'capacity', '16GB (2 x 8GB)'),
    (7, 'speed', '3200MHz'),
    (8, 'capacity', '16GB (2 x 8GB)'),
    (8, 'speed', '3600MHz'),
    -- Power supply information
    (9, 'wattage', '750W'),
    (9, 'efficiency', '80+ Gold'),
    (10, 'wattage', '650W'),
    (10, 'efficiency', '80+ Gold'),
    -- Storage information
    (11, 'capacity', '500GB'),
    (11, 'interface', 'NVMe M.2'),
    (12, 'capacity', '2TB'),
    (12, 'interface', 'SATA 6Gb/s'),
    -- Case information
    (13, 'form_factor', 'ATX Mid Tower'),
    (13, 'color', 'Black'),
    (14, 'form_factor', 'ATX Mid Tower'),
    (14, 'color', 'White'),
    -- CPU cooler information
    (15, 'type', 'Dual-Tower'),
    (15, 'fan_size', '140mm'),
    (16, 'type', 'Single-Tower'),
    (16, 'fan_size', '120mm');
---
CREATE TABLE IF NOT EXISTS users(
    ID INT auto_increment NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(320) NOT NULL,
    password CHAR(32) NOT NULL,
    isAdmin SMALLINT DEFAULT 0,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
---
CREATE TABLE IF NOT EXISTS cartContents(
    ID INT auto_increment NOT NULL,
    userID INT NOT NULL,
    componentID INT NOT NULL,
    quantity INT DEFAULT 1,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
---
CREATE TABLE IF NOT EXISTS configs(
    ID INT auto_increment NOT NULL,
    uuid VARCHAR(36) NOT NULL,
    UNIQUE(uuid),
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
---
CREATE TABLE IF NOT EXISTS configContents(
    ID INT auto_increment NOT NULL,
    configID INT NOT NULL,
    componentID INT NOT NULL,
    quantity INT DEFAULT 1,
    PRIMARY KEY (ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
---
CREATE TABLE IF NOT EXISTS orders(
    ID INT auto_increment NOT NULL,
    userID INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
---
CREATE TABLE IF NOT EXISTS ordersContents(
    ID INT auto_increment NOT NULL,
    orderID INT NOT NULL,
    componentID INT NOT NULL,
    quantity INT DEFAULT 1,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
---
CREATE TABLE IF NOT EXISTS offers(
    ID INT auto_increment NOT NULL,
    title VARCHAR(100) NOT NULL,
    componentID INT NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
INSERT INTO offers (componentID)
VALUES(17);
---
CREATE TABLE IF NOT EXISTS news(
    ID INT auto_increment NOT NULL,
    title VARCHAR(100) NOT NULL,
    description MEDIUMTEXT NOT NULL,
    image VARCHAR(100) NOT NULL,
    link VARCHAR(100) NOT NULL,
    PRIMARY KEY(ID)
) ENGINE = InnoDB DEFAULT CHARSET = latin1;
INSERT INTO news (title, description, image, link)
VALUES (
        "RTX 4070 potrebbe arrivare ad aprile",
        "nonstante siamo a maggi",
        "immagini/4070.jpg",
        "https://www.tomshw.it/hardware/la-geforce-rtx-4070-potrebbe-arrivare-ad-aprile/"
    );