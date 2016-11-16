alter table customers
alter firstname drop not null,
alter lastname drop not null,
alter address1 drop not null,
alter city drop not null,
alter country drop not null,
alter region drop not null,
alter creditcardtype drop not null;
select setval('customers_customerid_seq', (select max(customerid) from customers));
select setval('options_optionid_seq', (select max(optionid) from options));
