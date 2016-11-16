create or replace function make_top_protas()
returns void as $$
begin
    create or replace view top_protas as
    select actor.id as actor_id, actor.nombre as nombre, top.c as num_protagonista
    from (
        select count(*) as c, actor_id
        from (
            select id
            from pelicula
            where agno between 1980 and 1999
        ) as pelicula inner join reparto
        on pelicula.id = reparto.pelicula_id and reparto.ord = 1
        group by actor_id
        order by c desc
        limit 10
    ) as top inner join actor
    on top.actor_id = actor.id;
end; $$
language plpgsql;

create or replace function trigger_top_protas()
returns trigger as $$
begin
    perform make_top_protas();
    return new;
end; $$
language plpgsql;

drop trigger t_top_protas on reparto;
create trigger t_top_protas after insert on reparto
execute procedure trigger_top_protas();

select make_top_protas();
