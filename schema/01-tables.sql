CREATE TABLE
    users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100) UNIQUE,
        password VARCHAR(255),
        role ENUM ('admin', 'dentist', 'staff') DEFAULT 'staff',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

CREATE TABLE
    patients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        birthdate DATE NULL,
        gender ENUM ('male', 'female', 'other') NULL,
        contact VARCHAR(30) NULL,
        email VARCHAR(150) NULL,
        address TEXT NULL,
        civil_status ENUM ('single', 'married', 'widowed', 'separated') NULL,
        status ENUM ('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_patients_name (firstname, lastname),
        INDEX idx_patients_contact (contact),
        INDEX idx_patients_email (email),
        INDEX idx_patients_status (status)
    );

CREATE TABLE
    dentists (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        specialization VARCHAR(150) NULL,
        license_number VARCHAR(100) NULL,
        contact VARCHAR(30) NULL,
        email VARCHAR(150) NULL,
        status ENUM ('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_dentists_name (firstname, lastname),
        INDEX idx_dentists_user (user_id),
        CONSTRAINT fk_dentists_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
    );

CREATE TABLE
    appointments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        patient_id INT NOT NULL,
        dentist_id INT NOT NULL,
        appointment_start DATETIME NOT NULL,
        appointment_end DATETIME NOT NULL,
        status ENUM ('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
        reason TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_appointments_patient (patient_id),
        INDEX idx_appointments_dentist (dentist_id),
        INDEX idx_appointments_start (appointment_start),
        INDEX idx_appointments_end (appointment_end),
        INDEX idx_appointments_status (status)
    );

CREATE TABLE
    services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT NULL,
        price DECIMAL(10, 2) DEFAULT 0,
        duration_minutes INT DEFAULT 30,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

INSERT INTO
    services (name, description, price, duration_minutes)
VALUES
    -- GENERAL DENTISTRY
    (
        'Dental Checkup',
        'Routine oral examination and consultation',
        500.00,
        30
    ),
    (
        'Oral Prophylaxis',
        'Professional dental cleaning procedure',
        1200.00,
        45
    ),
    (
        'Fluoride Treatment',
        'Fluoride application for cavity prevention',
        800.00,
        20
    ),
    (
        'Tooth Extraction',
        'Simple tooth extraction procedure',
        2500.00,
        60
    ),
    (
        'Wisdom Tooth Extraction',
        'Surgical removal of impacted wisdom tooth',
        8000.00,
        120
    ),
    (
        'Dental Filling',
        'Composite tooth restoration filling',
        1800.00,
        45
    ),
    (
        'Root Canal Treatment',
        'Root canal therapy for infected tooth',
        9500.00,
        120
    ),
    (
        'Dental Crown',
        'Porcelain dental crown installation',
        15000.00,
        90
    ),
    (
        'Dental Bridge',
        'Fixed dental bridge restoration',
        22000.00,
        120
    ),
    (
        'Dentures',
        'Complete or partial removable dentures',
        18000.00,
        90
    ),
    -- COSMETIC
    (
        'Teeth Whitening',
        'Professional teeth whitening treatment',
        6000.00,
        60
    ),
    (
        'Veneers',
        'Cosmetic porcelain veneer application',
        18000.00,
        90
    ),
    (
        'Smile Makeover',
        'Comprehensive cosmetic dental enhancement',
        35000.00,
        180
    ),
    -- ORTHODONTICS
    (
        'Braces Consultation',
        'Orthodontic assessment and planning',
        1000.00,
        45
    ),
    (
        'Metal Braces Installation',
        'Traditional metal braces placement',
        45000.00,
        120
    ),
    (
        'Ceramic Braces Installation',
        'Tooth-colored ceramic braces placement',
        65000.00,
        120
    ),
    (
        'Monthly Braces Adjustment',
        'Routine orthodontic braces adjustment',
        1500.00,
        30
    ),
    (
        'Retainer Fabrication',
        'Custom orthodontic retainer',
        5000.00,
        45
    ),
    -- PEDIATRIC
    (
        'Pediatric Dental Checkup',
        'Dental consultation for children',
        600.00,
        30
    ),
    (
        'Dental Sealants',
        'Protective coating for cavity prevention',
        1500.00,
        30
    ),
    -- SURGICAL
    (
        'Impacted Tooth Surgery',
        'Minor oral surgical procedure',
        12000.00,
        120
    ),
    (
        'Gum Treatment',
        'Periodontal cleaning and gum therapy',
        3500.00,
        60
    ),
    (
        'Dental Implant Consultation',
        'Dental implant assessment and planning',
        1200.00,
        45
    ),
    (
        'Dental Implant Placement',
        'Titanium dental implant procedure',
        65000.00,
        180
    ),
    -- EMERGENCY
    (
        'Emergency Dental Treatment',
        'Immediate dental pain or trauma care',
        2500.00,
        60
    ),
    (
        'Tooth Recementation',
        'Reattachment of loose crown or bridge',
        1800.00,
        30
    );

CREATE TABLE
    appointment_services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        service_id INT NOT NULL,
        FOREIGN KEY (appointment_id) REFERENCES appointments (id) ON DELETE CASCADE,
        FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE
    );