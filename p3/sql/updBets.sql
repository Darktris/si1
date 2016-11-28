-- 			APARTADO G
--
--		Actualizacion automatica del outcome
--
--
create or replace function updbets() 
returns trigger as $$
begin
	--- Caso en el que no es necesario actualizar nada.
	if TG_OP = 'UPDATE' then
		if new.winneropt = old.winneropt then
			return new;
		end if;
	end if;

    if new.winneropt is null then
        return new;
    end if;
	
	update clientbets
	set outcome = bet*ratio
	where new.winneropt = optionid 
		and new.betid = clientbets.betid;
	
	update clientbets
	set outcome = 0
	where betid = new.betid 
		and new.winneropt != optionid;
    return new;
end; $$
language plpgsql;

drop trigger t_bets on bets;

create trigger t_bets after insert or update on bets
for each row execute procedure updbets();

