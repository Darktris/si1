alter table customers
alter firstname drop not null,
alter lastname drop not null,
alter address1 drop not null,
alter city drop not null,
alter country drop not null,
alter region drop not null,
alter creditcardtype drop not null;
select setval('bets_betid_seq', (select max(betid) from bets));
select setval('clientorders_id_seq', (select max(orderid) from clientorders));
select setval('customers_customerid_seq', (select max(customerid) from customers));
select setval('options_optionid_seq', (select max(optionid) from options));
select setval('categories_categoryid_seq', (select max(categoryid) from categories));
