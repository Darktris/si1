insert into clientorders (customerid, date, orderid)
values (693, NULL, 999999998);

insert into clientbets (optionid, bet, ratio, betid, orderid)
values (111, 1000, 6.66, 3920, 999999998);

insert into clientbets (optionid, bet, ratio, betid, orderid)
values (110, 1000, 6.66, 3920, 999999998);

update clientorders set date = now() where orderid = 999999998;

select * from clientbets where orderid=999999998;
select * from clientorders where orderid=999999998;

update bets SET winneropt = 111 where betid = 3920;

select * from clientbets where orderid=999999998;
select * from clientorders where orderid=999999998;
