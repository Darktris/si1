-- 			APARTADO H
--
--		Actualiza clientorders 
--

create or replace function updorders() 
returns trigger as $$
begin
	update clientorders
	set totalamount = amount
	from (
		select clientbets.orderid as id, sum(bet) as amount
		from clientbets
		where clientbets.orderid = new.orderid
		group by orderid
	) as aux
	where clientorders.orderid = new.orderid;

	perform setOrderTotaloutcome(new.orderid);		
    return new;
end; $$
language plpgsql;

drop trigger t_orders on clientbets;
drop trigger t_updorders on clientbets;

create trigger t_orders after insert on clientbets
for each row execute procedure updorders();

create trigger t_updorders after update on clientbets
for each row execute procedure updorders();
