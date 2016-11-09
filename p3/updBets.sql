-- 			APARTADO G
--
--		Actualizacion automatica del outcome
--
--
create or replace function updbets() 
returns trigger as $$
begin

	update clientbets
	set outcome = bet*ratio
	where new.winneropt = optionid 
		and new.betid = clientbets.betid;
	
	update clientbets
	set outcome = 0
	where clientbets.betid = new.betid 
		and new.winneropt is not null
		and new.winneropt != optionid;
end; $$
language plpgsql;

drop trigger t_bets on bets;
drop trigger t_updbets on bets;

create trigger t_bets after insert on bets
for each row execute procedure updbets();

create trigger t_updbets after update on bets
for each row execute procedure updbets();
