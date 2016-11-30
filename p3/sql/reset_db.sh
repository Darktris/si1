#!/bin/sh
dropdb -U alumnodb si1
createdb -U alumnodb si1
cat dump-*.sql | psql -U alumnodb si1
createlang -U alumnodb plpgsql si1
