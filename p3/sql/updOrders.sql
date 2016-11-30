--          APARTADO H
--
--      Actualiza clientorders
--

create or replace function updorders()
returns trigger as $$
begin
    if TG_OP = 'INSERT' then
        update clientorders
        set totalamount = totalamount + coalesce(new.bet,0), totaloutcome = totaloutcome + coalesce(new.outcome,0)
        where orderid = new.orderid;
    elsif TG_OP = 'UPDATE' then
        update clientorders
        set totalamount = totalamount - coalesce(old.bet,0) + coalesce(new.bet,0), totaloutcome = totaloutcome - coalesce(old.outcome,0) + coalesce(new.outcome,0)
        where orderid = new.orderid;
    else -- DELETE
        update clientorders
        set totalamount = totalamount - coalesce(old.bet,0), totaloutcome = totaloutcome - coalesce(old.outcome,0)
        where orderid = old.orderid;
    end if;

    return new;
end; $$
language plpgsql;

drop trigger t_orders on clientbets;

create trigger t_orders after insert or update or delete on clientbets
for each row execute procedure updorders();
