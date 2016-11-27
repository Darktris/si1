-- 			APARTADO H
--
--		Actualizacion del campo 
--		credit de la tabla 'customers' 
--		cada vez que cambie totalOutcome 
--		(suma nuevo y resta el viejo).
--
--
create or replace function updcredit() 
returns trigger as $$
begin

	update customers
	set credit = new.totaloutcome - new.totalamount + customers.credit
	where new.customerid = customers.customerid
		and new.totaloutcome is not null
		and new.date is not null;
		
    return new;
end; $$
language plpgsql;

drop trigger t_credits on bets;
drop trigger t_updcredit on bets;

create trigger t_credits after insert on clientorders
for each row execute procedure updcredit();

create trigger t_updcredit before update on clientorders
for each row execute procedure updcredit();
