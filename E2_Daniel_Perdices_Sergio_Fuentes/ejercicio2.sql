select pro.id as actor_id, pro.nombre as nombre
from (
    select count(*) as c, actor.id as id, actor.nombre as nombre
    from actor, reparto
    where actor.id = reparto.actor_id and reparto.ord = 1
    group by actor.id) as pro,
(
    select count(*) as c, actor.id as id
    from actor, reparto
    where actor.id = reparto.actor_id and reparto.ord != 1
    group by actor.id) as sec
where pro.id = sec.id and pro.c > sec.c;
