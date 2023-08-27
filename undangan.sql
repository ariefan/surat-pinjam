create table undangan (
	id int auto_increment primary key,
  tanggal_undangan date,
	hal VARCHAR(100),
	lampiran VARCHAR(100),
	pengundang VARCHAR(100),
	sehubungan_dengan VARCHAR(100),
	hari VARCHAR(20),
	tanggal date,
	pukul time,
	tempat varchar(100),
	acara VARCHAR(50),
	agenda VARCHAR(50),
  penerima varchar(255),
  absen varchar(255),
  notulen varchar(255)
);

create table ruangan (
	id int auto_increment primary key,
	nama varchar(255),
	lokasi varchar(255),
  akses varchar(255)
);