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