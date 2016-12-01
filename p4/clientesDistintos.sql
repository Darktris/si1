drop index idx1;
explain select count(distinct customers.customerid) from customers, clientorders where clientorders.customerid = customers.customerid and date_part('month', clientorders.date) = '03' and date_part('year', clientorders.date) = '2013' and clientorders.totalamount > 100;
create index idx1 on clientorders (totalamount);
explain select count(distinct customers.customerid) from customers, clientorders where clientorders.customerid = customers.customerid and date_part('month', clientorders.date) = '03' and date_part('year', clientorders.date) = '2013' and clientorders.totalamount > 100;
