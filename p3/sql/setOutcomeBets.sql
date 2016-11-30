-- 			APARTADO D
--
--		Actualizacion de clientbets.outcome
--

update clientbets
set outcome = bet*ratio
from bets
where bets.winneropt = optionid
	and bets.betid = clientbets.betid;

update clientbets
set outcome = 0
from bets
where clientbets.betid = bets.betid
	and bets.winneropt is not null
	and bets.winneropt != optionid;
