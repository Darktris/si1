#!/bin/sh
sh reset_db.sh
psql si1 -U alumnodb -f actualiza.sql
psql si1 -U alumnodb -f setTotalAmount.sql
psql si1 -U alumnodb -f setOutcomeBets.sql
psql si1 -U alumnodb -f setOrderTotalOutcome.sql
psql si1 -U alumnodb -f updBets.sql
psql si1 -U alumnodb -f updOrders.sql
psql si1 -U alumnodb -f updCredit.sql
psql si1 -U alumnodb -f adapta.sql

