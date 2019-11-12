create table provinsi(
  id_provinsi int(1) unsigned auto_increment,
  nm_provinsi varchar(100) not null default "",
  primary key(id_provinsi)
)engine=MyIsam;

create table kabupaten(
  id_kabupaten int(1) unsigned auto_increment,
  id_provinsi int(1) unsigned not null default 0,
  nm_kabupaten varchar(100) not null default "",
  primary key(id_kabupaten),
  key id_provinsi(id_provinsi)
)engine=MyIsam;

create table kecamatan(
  id_kecamatan int(1) unsigned auto_increment,
  id_kabupaten int(1) unsigned not null default 0,
  lat double,
  lng double,
  nm_kecamatan varchar(100) not null default "",
  primary key(id_kecamatan),
  key id_kabupaten(id_kabupaten)
)engine=MyIsam;