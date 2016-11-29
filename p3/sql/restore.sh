#!/bin/sh
sh reset_db.sh
psql si1 -f actualiza.sql
psql si1 -f setTotalAmount.sql
psql si1 -f setOutcomeBets.sql
psql si1 -f setOrderTotalOutcome.sql
psql si1 -f updBets.sql
psql si1 -f updOrders.sql
psql si1 -f updCredit.sql
psql si1 -f adapta.sql

