-- Add a default admin user (password: password)
INSERT INTO
    users (name, email, password, role)
VALUES
    (
        'Admin User',
        'admin@example.com',
        '$2y$10$borbZ3V/HlV5Wzgcz9W.Ue6e81OaTJHUzXPFVNa11TxYx9x5Zf2/6',
        'admin'
    );

    INSERT INTO users (name, email, password, role)
VALUES
('Dr. Juan Dela Cruz', 'juan@toothcare.com', 'password123', 'dentist'),
('Dr. Maria Santos', 'maria@toothcare.com', 'password123', 'dentist'),
('Dr. Jose Reyes', 'jose@toothcare.com', 'password123', 'dentist'),
('Dr. Ana Garcia', 'ana@toothcare.com', 'password123', 'dentist'),
('Dr. Mark Torres', 'mark@toothcare.com', 'password123', 'dentist'),
('Dr. Grace Mendoza', 'grace@toothcare.com', 'password123', 'dentist'),
('Dr. Paolo Flores', 'paolo@toothcare.com', 'password123', 'dentist'),
('Dr. Catherine Ramos', 'catherine@toothcare.com', 'password123', 'dentist'),
('Dr. Michael Aquino', 'michael@toothcare.com', 'password123', 'dentist'),
('Dr. Angela Fernandez', 'angela@toothcare.com', 'password123', 'dentist');

INSERT INTO dentists
(
    user_id,
    firstname,
    lastname,
    specialization,
    license_number,
    contact,
    email,
    status
)
VALUES
(3, 'Juan', 'Dela Cruz', 'General Dentistry', 'DEN-2026-0001', '09171234561', 'juan@toothcare.com', 'active'),
(4, 'Maria', 'Santos', 'Orthodontics', 'DEN-2026-0002', '09171234562', 'maria@toothcare.com', 'active'),
(5, 'Jose', 'Reyes', 'Pediatric Dentistry', 'DEN-2026-0003', '09171234563', 'jose@toothcare.com', 'active'),
(6, 'Ana', 'Garcia', 'Periodontics', 'DEN-2026-0004', '09171234564', 'ana@toothcare.com', 'active'),
(7, 'Mark', 'Torres', 'Endodontics', 'DEN-2026-0005', '09171234565', 'mark@toothcare.com', 'active'),
(8, 'Grace', 'Mendoza', 'Oral Surgery', 'DEN-2026-0006', '09171234566', 'grace@toothcare.com', 'active'),
(9, 'Paolo', 'Flores', 'Prosthodontics', 'DEN-2026-0007', '09171234567', 'paolo@toothcare.com', 'active'),
(10, 'Catherine', 'Ramos', 'Cosmetic Dentistry', 'DEN-2026-0008', '09171234568', 'catherine@toothcare.com', 'active'),
(11, 'Michael', 'Aquino', 'General Dentistry', 'DEN-2026-0009', '09171234569', 'michael@toothcare.com', 'active'),
(12, 'Angela', 'Fernandez', 'Orthodontics', 'DEN-2026-0010', '09171234570', 'angela@toothcare.com', 'active');

INSERT INTO patients
(
    firstname,
    lastname,
    birthdate,
    gender,
    contact,
    email,
    address,
    civil_status,
    status
)
VALUES
('John', 'Santos', '1995-03-12', 'male', '+639171111111', 'john.santos@gmail.com', 'San Pablo City, Laguna', 'single', 'active'),

('Maria', 'Reyes', '1992-07-25', 'female', '+639171111112', 'maria.reyes@gmail.com', 'Calamba City, Laguna', 'married', 'active'),

('Kevin', 'Dela Cruz', '1998-11-18', 'male', '+639171111113', 'kevin.delacruz@gmail.com', 'Santa Rosa City, Laguna', 'single', 'active'),

('Angela', 'Mendoza', '1990-05-30', 'female', '+639171111114', 'angela.mendoza@gmail.com', 'Biñan City, Laguna', 'married', 'active'),

('Paolo', 'Garcia', '1987-08-14', 'male', '+639171111115', 'paolo.garcia@gmail.com', 'Los Baños, Laguna', 'married', 'active'),

('Catherine', 'Flores', '1996-02-09', 'female', '+639171111116', 'catherine.flores@gmail.com', 'Cabuyao City, Laguna', 'single', 'active'),

('Michael', 'Torres', '1993-12-21', 'male', '+639171111117', 'michael.torres@gmail.com', 'San Pedro City, Laguna', 'single', 'active'),

('Grace', 'Aquino', '1989-04-17', 'female', '+639171111118', 'grace.aquino@gmail.com', 'Sta. Cruz, Laguna', 'widowed', 'active'),

('Joshua', 'Fernandez', '2000-09-06', 'male', '+639171111119', 'joshua.fernandez@gmail.com', 'Pagsanjan, Laguna', 'single', 'active'),

('Sophia', 'Ramos', '1997-01-28', 'female', '+639171111120', 'sophia.ramos@gmail.com', 'Nagcarlan, Laguna', 'single', 'active');