select credit from customers where customerid = 693;

insert into clientorders (customerid, date, orderid) 
values (693, now(), 999999999);

insert into clientbets (optionid, bet, ratio, betid, orderid) 
values (104, 1000, 6.66, 4526, 999999999);

select * from clientorders where orderid = 999999999;
select credit from customers where customerid = 693;

insert into clientbets (optionid, bet, ratio, betid, orderid) 
values (103, 3000, 6.66, 4526, 999999999);

select * from clientorders where orderid = 999999999;
select credit from customers where customerid = 693;

insert into clientbets (optionid, bet, ratio, betid, orderid) 
values (105, 2000, 6.66, 4526, 999999999);

select * from clientorders where orderid = 999999999;
select credit from customers where customerid = 693;

delete from clientbets
where optionid = 105 and bet = 2000 and orderid =  999999999;

select * from clientorders where orderid = 999999999;
select credit from customers where customerid = 693;

update bets
set winneropt = 104
where betid = 4526;

select credit from customers where customerid = 693;
select * from clientorders where orderid = 999999999;
