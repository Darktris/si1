create or replace function make_estrellas()
returns void as $$
begin
    create or replace view estrellas as
    select actor.id as actor_id, actor.nombre, aux.agno, max(aux.count) as maximo
    from actor,
    (
        select estrellas.actor_id, pelicula.agno, count(pelicula.agno)
        from reparto, pelicula,
        (
            select pro.id as actor_id
            from (
                select count(*) as c, actor.id as id
                from actor, reparto
                where actor.id = reparto.actor_id and reparto.ord = 1
                group by actor.id) as pro,
            (
                select count(*) as c, actor.id as id
                from actor, reparto
                where actor.id = reparto.actor_id and reparto.ord != 1
                group by actor.id) as sec
            where pro.id = sec.id and pro.c > sec.c) as estrellas
        where reparto.actor_id = estrellas.actor_id and pelicula.id = reparto.pelicula_id and reparto.ord = 1
        group by estrellas.actor_id, pelicula.agno) as aux
    where actor.id = aux.actor_id and (actor_id, aux.count) in (
        select actor.id, max(aux.count)
        from actor,
        (
            select estrellas.actor_id, pelicula.agno, count(pelicula.agno)
            from reparto, pelicula,
            (
                select pro.id as actor_id
                from (
                    select count(*) as c, actor.id as id
                    from actor, reparto
                    where actor.id = reparto.actor_id and reparto.ord = 1
                    group by actor.id) as pro,
                (
                    select count(*) as c, actor.id as id
                    from actor, reparto
                    where actor.id = reparto.actor_id and reparto.ord != 1
                    group by actor.id) as sec
                where pro.id = sec.id and pro.c > sec.c) as estrellas
            where reparto.actor_id = estrellas.actor_id and pelicula.id = reparto.pelicula_id and reparto.ord = 1
            group by estrellas.actor_id, pelicula.agno) as aux
        where actor.id = aux.actor_id
        group by actor.id
    )
    group by actor.id, actor.nombre, aux.agno
    order by maximo desc, actor.nombre;
end; $$
language plpgsql;

select make_estrellas();
select * from estrellas;
