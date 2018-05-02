
create database lab2;
use lab2;

create table zipcodes(
  zip      varchar(9),
  city     varchar(40),
  primary key (zip)
);

create table employees(
  eno      integer primary key, 
  ename    varchar(30),
  zip      varchar(9),
  hdate    date,
  foreign key (zip) references zipcodes(zip)
);

create table parts(
  pno      integer primary key,
  pname    varchar(30),
  qoh      integer check(qoh >= 0),
  price    decimal(6,2) check(price >= 0.0),
  level    integer
);

create table customers (
  cno      integer auto_increment primary key,
  cname    varchar(30),
  street   varchar(30),
  zip      varchar(9),
  phone    varchar(12),
  foreign key (zip) references zipcodes(zip)
);
 
create table orders (
  ono      integer auto_increment primary key,
  cno      integer,
  eno      integer,
  received timestamp default now(),
  shipped  timestamp null,
  foreign key (cno) references customers(cno),
  foreign key (eno) references employees(eno)
);

create table order_line (
  ono      integer,
  pno      integer,
  qty      integer check(qty > 0),
  primary key (ono,pno),
  foreign key (ono) references orders(ono),
  foreign key (pno) references parts(pno)
);
