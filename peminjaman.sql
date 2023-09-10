CREATE TABLE IF NOT EXISTS gedung (
    id INT UNSIGNED AUTO_INCREMENT,
    nama_gedung VARCHAR(255),
    lokasi VARCHAR(255),
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS ruang (
    id INT UNSIGNED AUTO_INCREMENT,
    gedung_id INT UNSIGNED,
    nama_ruang VARCHAR(255),
    kapasitas INT(5),
    fasilitas TEXT,
    dapat_disewa BOOLEAN DEFAULT true,
    harga_sewa DECIMAL(10,2) DEFAULT 0.00,
    catatan TEXT,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (gedung_id) REFERENCES gedung(id)
);

CREATE TABLE IF NOT EXISTS surat_peminjaman (
    id VARCHAR(25) NOT NULL,
    no_surat VARCHAR(255),
    nama_surat VARCHAR(255),
    tembusan TEXT,
    user_id INT UNSIGNED,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS surat_peminjaman_jadwal (
    id INT AUTO_INCREMENT,
    surat_peminjaman_id VARCHAR(25) NOT NULL,
    ruang_id INT UNSIGNED,
    tanggal_mulai_pinjam DATE NULL,
    tanggal_selesai_pinjam DATE NULL,
    jam_mulai_pinjam TIME NULL,
    jam_selesai_pinjam TIME NULL,
    hari_pinjam VARCHAR(50),
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (surat_peminjaman_id) REFERENCES surat_peminjaman(id),
    FOREIGN KEY (ruang_id) REFERENCES ruang(id)
);






INSERT INTO gedung (nama_gedung, lokasi) VALUES
    ('Gedung A', 'Location A'),
    ('Gedung B', 'Location B'),
    ('Gedung C', 'Location C');

INSERT INTO ruang (gedung_id, nama_ruang, kapasitas, fasilitas, dapat_disewa, harga_sewa, catatan, created_at, updated_at) VALUES
    (1, 'Ruang 101', 50, 'Projector, Whiteboard', 1, 100000.00, 'Standard meeting room', NULL, NULL),
    (1, 'Ruang 102', 30, 'Whiteboard', 1, 75000.00, 'Small meeting room', NULL, NULL),
    (2, 'Ruang 201', 100, 'Projector, Whiteboard, Sound System', 1, 200000.00, 'Large conference room', NULL, NULL),
    (1, 'Ruang 111', 50, 'Projector, Whiteboard', 1, 100000.00, 'Standard meeting room', NULL, NULL),
    (1, 'Ruang 112', 30, 'Whiteboard', 1, 75000.00, 'Small meeting room', NULL, NULL),
    (2, 'Ruang 211', 100, 'Projector, Whiteboard, Sound System', 1, 200000.00, 'Large conference room', NULL, NULL),
    (1, 'Ruang 103', 40, 'Whiteboard', 1, 90000.00, 'Medium meeting room', NULL, NULL),
    (2, 'Ruang 202', 80, 'Projector, Whiteboard', 1, 150000.00, 'Conference room with facilities', NULL, NULL),
    (3, 'Ruang 301', 60, 'Whiteboard, Sound System', 1, 120000.00, 'Soundproof meeting room', NULL, NULL),
    (1, 'Ruang 104', 35, 'Whiteboard', 1, 80000.00, 'Small meeting room', NULL, NULL),
    (2, 'Ruang 203', 70, 'Projector, Whiteboard', 1, 160000.00, 'Large meeting room', NULL, NULL),
    (3, 'Ruang 302', 55, 'Whiteboard, Sound System', 1, 110000.00, 'Premium meeting room', NULL, NULL),
    (1, 'Ruang 105', 45, 'Whiteboard', 1, 95000.00, 'Medium meeting room', NULL, NULL),
    (2, 'Ruang 204', 90, 'Projector, Whiteboard', 1, 180000.00, 'Conference room with view', NULL, NULL),
    (3, 'Ruang 303', 70, 'Whiteboard, Sound System', 1, 140000.00, 'Executive board room', NULL, NULL);


INSERT INTO surat_peminjaman (id, no_surat, nama_surat, tembusan, user_id) VALUES
    ('SP001', '2023/001', 'Surat Peminjaman 1', 'Tembusan 1, Tembusan 2', 1),
    ('SP002', '2023/002', 'Surat Peminjaman 2', 'Tembusan 3', 2),
    ('SP003', '2023/003', 'Surat Peminjaman 3', 'Tembusan 4', 3);

INSERT INTO surat_peminjaman_jadwal (surat_peminjaman_id, ruang_id, tanggal_mulai_pinjam, tanggal_selesai_pinjam, jam_mulai_pinjam, jam_selesai_pinjam, hari_pinjam) VALUES
    ('SP001', 1, '2023-09-15', '2023-09-16', '09:00:00', '17:00:00', '3'),
    ('SP001', 2, '2023-09-15', '2023-09-16', '10:00:00', '16:00:00', '3'),
    ('SP002', 1, '2023-09-20', '2023-09-21', '14:00:00', '18:00:00', '1'),
    ('SP003', 3, '2023-09-25', '2023-09-26', '11:00:00', '19:00:00', '5');
