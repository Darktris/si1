#!/bin/sh
dropdb si1
createdb si1
cat dump-*.sql | psql si1
