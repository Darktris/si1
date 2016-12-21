drop index idx2;

explain select count(*)
from clientbets
where outcome is null;

explain select count(*)
from clientbets
where outcome =0;

create index idx2 on clientbets(outcome);

explain select count(*)
from clientbets
where outcome is null;

explain select count(*)
from clientbets
where outcome =0;

analyze clientbets;

explain select count(*)
from clientbets
where outcome is null;

explain select count(*)
from clientbets
where outcome =0;

explain select count(*)
from clientbets
where outcome > 0;

explain select count(*)
from clientbets
where outcome > 200;
