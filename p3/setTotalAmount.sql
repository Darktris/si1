-- 			APARTADO C
--
--		La suma del total apostado para cada pedido en clientorders.totalamount
--

update clientorders
set totalamount = amount
from (
	select orderid as id, sum(bet) as amount
	from clientbets
	group by orderid
) as aux
where clientorders.orderid = id;