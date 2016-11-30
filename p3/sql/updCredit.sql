--          APARTADO H
--
--      Actualizacion del campo
--      credit de la tabla 'customers'
--      cada vez que cambie totalOutcome
--      (suma nuevo y resta el viejo).
--
--
create or replace function updcredit()
returns trigger as $$
declare
    credit numeric;
begin
    credit := (select customers.credit from customers where customerid = new.customerid);

    if TG_OP = 'UPDATE' then
        if new.date is not null and old.date is null then
            if new.totalamount > credit then
                return null;
            end if;
            --- Carrito recien cerrado
            update customers
            set credit = coalesce(new.totaloutcome, 0) - coalesce(new.totalamount, 0) + customers.credit
            where new.customerid = customers.customerid
                and new.date is not null;
        else
            if new.date is not null and coalesce(new.totalamount,0) - coalesce(old.totalamount, 0) > credit then
                return null;
            end if;
            --- Actualizamos con la diferencia de una apuesta.
            --- No debería permitirse modificar un carrito cerrado,
            --- pero se permite por compatibilidad
            update customers
            set credit = coalesce(new.totaloutcome, 0) - coalesce(old.totaloutcome, 0) - (coalesce(new.totalamount,0) - coalesce(old.totalamount, 0)) + customers.credit
            where new.customerid = customers.customerid
                and new.date is not null;
        end if;
    elsif TG_OP = 'INSERT' then
        if new.date is not null then
            if new.totalamount > credit then
                return null;
            end if;
            update customers
            set credit = coalesce(new.totaloutcome, 0) - coalesce(new.totalamount, 0) + customers.credit
            where new.customerid = customers.customerid
                and new.date is not null;
        end if;
    end if;
    return new;
end; $$
language plpgsql;

drop trigger t_credits on clientorders;

create trigger t_credits before insert or update on clientorders
for each row execute procedure updcredit();
