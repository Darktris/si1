-- 			APARTADO F
--
--		La suma del total outcome para cada pedido en clientorders.totaloutcome
--

create or replace function setOrderTotalOutcome(orderid_arg integer)
returns void as $$
begin
	update clientorders
	set totaloutcome = aux.sum
	from (
		select sum(clientbets.outcome) as sum
		from clientbets
		where clientbets.orderid = orderid_arg
		group by clientbets.orderid
	) as aux
	where orderid = orderid_arg;
end; $$
language plpgsql;

create or replace function setTotalOutcome()
returns void as $$
begin
	update clientorders
	set totaloutcome = aux.sum
	from (
		select clientbets.orderid, sum(clientbets.outcome) as sum
		from clientbets
		where clientbets.outcome is not null
		group by clientbets.orderid
	) as aux
	where clientorders.orderid = aux.orderid;
end; $$
language plpgsql;
