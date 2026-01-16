-- Unilak Medical Center Database Schema
-- Database for Medical Clinic Information System

CREATE DATABASE IF NOT EXISTS umc_clinic;
USE umc_clinic;

-- Table: users (untuk login - Admin, Kasir, Dokter)
CREATE TABLE users (
    user_id VARCHAR(10) PRIMARY KEY,
    username VARCHAR(10) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Kasir', 'Dokter') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: patients (master data pasien)
CREATE TABLE patients (
    patient_id VARCHAR(20) PRIMARY KEY,
    nik VARCHAR(16) NOT NULL,
    birth_date DATE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    status ENUM('Dosen', 'Karyawan', 'Mahasiswa', 'Umum') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: consultations (data konsultasi medis)
CREATE TABLE consultations (
    consultation_id VARCHAR(20) PRIMARY KEY,
    consultation_date VARCHAR(6) NOT NULL COMMENT 'Format: DDMMYY',
    patient_id VARCHAR(20) NOT NULL,
    nik VARCHAR(20) NOT NULL,
    complaint TEXT NOT NULL,
    diagnosis TEXT NOT NULL,
    medication TEXT NOT NULL,
    doctor_id VARCHAR(10) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(user_id)
);

-- Table: payments (data pembayaran)
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    consultation_id VARCHAR(20) NOT NULL,
    patient_id VARCHAR(20) NOT NULL,
    patient_status ENUM('Dosen', 'Karyawan', 'Mahasiswa', 'Umum') NOT NULL,
    amount DECIMAL(10, 2) NOT NULL DEFAULT 0,
    payment_status ENUM('Lunas', 'Gratis') NOT NULL,
    cashier_id VARCHAR(10) NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (consultation_id) REFERENCES consultations(consultation_id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(patient_id) ON DELETE CASCADE,
    FOREIGN KEY (cashier_id) REFERENCES users(user_id)
);

-- Insert default users untuk testing (ID 10 digit angka)
INSERT INTO users (user_id, username, password, role, full_name) VALUES
('1234567890', 'admin', MD5('admin123'), 'Admin', 'Administrator'),
('1234567891', 'kasir', MD5('kasir123'), 'Kasir', 'Kasir 1'),
('1234567892', 'dokter', MD5('dokter123'), 'Dokter', 'Dr. Ahmad');

-- Insert sample patients for testing
INSERT INTO patients (patient_id, nik, birth_date, full_name, address, phone, status) VALUES
('P001', '1471051234567890', '1990-05-15', 'Budi Santoso', 'Jl. Merdeka No. 10, Pekanbaru', '081234567890', 'Dosen'),
('P002', '1471051234567891', '1985-08-20', 'Siti Rahmawati', 'Jl. Sudirman No. 25, Pekanbaru', '081234567891', 'Karyawan'),
('P003', '1471051234567892', '2000-03-12', 'Rizki Pratama', 'Jl. Ahmad Yani No. 5, Pekanbaru', '081234567892', 'Mahasiswa'),
('P004', '1471051234567893', '1995-11-30', 'Dewi Lestari', 'Jl. Gatot Subroto No. 8, Pekanbaru', '081234567893', 'Umum');

-- Insert sample consultations
INSERT INTO consultations (consultation_id, consultation_date, patient_id, nik, complaint, diagnosis, medication, doctor_id) VALUES
('C001', '150126', 'P001', '1471051234567890', 'Demam dan batuk', 'Influenza', 'Paracetamol 3x1, Ambroxol 3x1', '1234567892'),
('C002', '150126', 'P002', '1471051234567891', 'Sakit kepala', 'Migrain', 'Ibuprofen 3x1', '1234567892'),
('C003', '160126', 'P003', '1471051234567892', 'Sakit perut', 'Gastritis', 'Antasida 3x1, Omeprazole 2x1', '1234567892'),
('C004', '160126', 'P004', '1471051234567893', 'Demam tinggi', 'Tifus', 'Ciprofloxacin 2x1, Paracetamol 3x1', '1234567892');

-- Insert sample payments
INSERT INTO payments (consultation_id, patient_id, patient_status, amount, payment_status, cashier_id) VALUES
('C001', 'P001', 'Dosen', 0, 'Gratis', '1234567891'),
('C002', 'P002', 'Karyawan', 0, 'Gratis', '1234567891'),
('C003', 'P003', 'Mahasiswa', 50000, 'Lunas', '1234567891'),
('C004', 'P004', 'Umum', 100000, 'Lunas', '1234567891');
