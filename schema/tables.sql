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
    `appointments` (
        `id` int (11) NOT NULL AUTO_INCREMENT,
        `patient_id` int (11) NOT NULL,
        `dentist_id` int (11) NOT NULL,
        `appointment_start` datetime NOT NULL,
        `appointment_end` datetime DEFAULT NULL,
        `status` enum ('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
        `payment_status` enum ('unpaid', 'paid') NOT NULL DEFAULT 'unpaid',
        `confirmed_at` datetime DEFAULT NULL,
        `completed_at` datetime DEFAULT NULL,
        `cancelled_at` datetime DEFAULT NULL,
        `reason` text DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        KEY `idx_appointments_patient` (`patient_id`),
        KEY `idx_appointments_dentist` (`dentist_id`),
        KEY `idx_appointments_status` (`status`),
        KEY `idx_appointment_end` (`appointment_end`),
        KEY `idx_appointment_start` (`appointment_start`),
        CONSTRAINT `fk_dentist_id` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `fk_patient_id` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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

CREATE TABLE
    appointment_services (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        service_id INT NOT NULL,
        FOREIGN KEY (appointment_id) REFERENCES appointments (id) ON DELETE CASCADE,
        FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE
    );

CREATE TABLE
    payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        payment_method VARCHAR(100) NULL,
        reference_no VARCHAR(100) NULL,
        paid_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_payment_appointment FOREIGN KEY (appointment_id) REFERENCES appointments (id) ON DELETE CASCADE
    );

CREATE TABLE feedbacks (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NULL,

    rating TINYINT NOT NULL,

    message TEXT NOT NULL,

    status ENUM('pending', 'approved', 'rejected')
        DEFAULT 'pending',

    is_featured TINYINT(1)
        DEFAULT 0,

    ip_address VARCHAR(45) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
);