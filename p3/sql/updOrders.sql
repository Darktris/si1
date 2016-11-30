--          APARTADO H
--
--      Actualiza clientorders
--

create or replace function updorders()
returns trigger as $$
begin
    if TG_OP = 'INSERT' then
        update clientorders
        set totalamount = totalamount + new.bet, totaloutcome = totaloutcome + new.outcome
        where clientorders.orderid = new.orderid;
    elsif TG_OP = 'UPDATE' then
        update clientorders
        set totalamount = totalamount - old.bet + new.bet, totaloutcome = totaloutcome - old.outcome + new.outcome
        where clientorders.orderid = old.orderid;
    else -- DELETE
        update clientorders
        set totalamount = totalamount - old.bet, totaloutcome = totaloutcome - old.outcome
        where clientorders.orderid = old.orderid;
    end if;

    return new;
end; $$
language plpgsql;

drop trigger t_orders on clientbets;

create trigger t_orders after insert or update or delete on clientbets
for each row execute procedure updorders();
