-- 			APARTADO H
--
--		Actualiza clientorders 
--

create or replace function updorders() 
returns trigger as $$
begin
	if TG_OP = 'DELETE' then
		new := old;
	end if;
	
	update clientorders
	set totalamount = amount,
		totaloutcome = out
	from (
		select clientbets.orderid as id, sum(bet) as amount, sum(outcome) as out
		from clientbets
		where clientbets.orderid = new.orderid
		group by orderid
	) as aux
	where clientorders.orderid = new.orderid;

    return new;
end; $$
language plpgsql;

drop trigger t_orders on clientbets;

create trigger t_orders after insert or update or delete on clientbets
for each row execute procedure updorders();
