--			APARTADO A
--
--		Restricciones
--

-- clientorders customerid no es fkey, orderid no es pkey
alter table clientorders
add constraint pk_orders
primary key (orderid); 

alter table clientorders
add constraint fk_customerid
foreign key (customerid)
references customers(customerid)
on delete cascade; -- Si se borra un usuario, 
                   -- se borran los carritos

alter table clientorders
add column totaloutcome numeric 
check (totaloutcome >= 0);

-- bets winneropt no es foreign key
alter table bets
add constraint fk_winoptid
foreign key (winneropt)
references options(optionid);

-- clientbets orderid no es foreign key. Customer id es redundante
alter table clientbets
add constraint fk_orderid
foreign key (orderid)
references clientorders(orderid)
on delete cascade;

alter table clientbets
drop column customerid;

-- customers Check en cada atributo
alter table customers
add constraint check_all
check (
	(zip similar to '[0-9]{5}')
	and (email ~* '^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+[.][A-Za-z]+$')
	and (age > 0));

alter table optionbet
drop column	optiondesc;

--			APARTADO B
--
--		Actualizacion atributo category
--

create table categories (
	categoryid serial primary key,
	categorystring varchar
);

alter table bets
add column categoryid integer references categories(categoryid);

alter table options
add column categoryid integer references categories(categoryid);

insert into categories (categorystring)
 	select distinct category
	from bets
	union
	select distinct categoria as category
	from options;

update bets
set categoryid = categories.categoryid 
from categories 
where categories.categorystring = bets.category;

update options
set categoryid = categories.categoryid 
from categories 
where categories.categorystring = options.categoria;

alter table bets
drop column category;

alter table options
drop column categoria;


---- INDICES
create index bets_wopt_idx on bets(winneropt);
create index bets_wopt_idx on bets(categoryid);

create index clbets_oid_idx on clientbets(orderid);
create index clbts_betid_idx on clientbets(betid); -- No ha funcionado muy bien

create index clo_oid_idx on clientorders(orderid);
create index clo_cid_idx on clientorders(customerid);

create index obet_betid_idx on optionbet(betid);
create index obet_optid_idx on optionbet(optionid);

create index opt_catid_idx on options(categoryid)

