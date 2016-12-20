alter table customers
add column promo float;

create or replace function applypromo()
returns trigger as $$
begin
    update clientbets
    set bet = (1 + coalesce(new.promo,0)) * bet
    where orderid in (
        select orderid
        from clientorders
        where customerid = new.customerid and date is null
    );
    perform pg_sleep(10);
    return new;
end; $$
language plpgsql;

drop trigger t_promo on customers;
create trigger t_promo after
insert or update on customers
for each row execute procedure applypromo();

update clientorders
set date = null
where orderid in (
    select orderid
    from clientorders
    where customerid = 1
    limit 1
);
