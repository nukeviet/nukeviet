<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$field_not_allow = 'add, all, alter, analyze, and, as, asc, before, between, bigint, binary, both, by, call, cascade, case, change, char, character, check, collate, column, comment, condition, constraint, continue, convert, create, cross, current_user, cursor, database, databases, date, day_hour, day_minute, day_second, dec, decimal, declare, default, delayed, delete, desc, describe, distinct, distinctrow, drop, dual, else, elseif, enclosed, escaped, exists, exit, explain, false, fetch, file, float4, float8, for, force, foreign, from, fulltext, get, grant, group, having, high_priority, hour_minute, hour_second, identified, if, ignore, ignore_server_ids, in, index, infile, inner, insert, int1, int2, int3, int4, int8, integer, interval, into, is, iterate, join, key, keys, kill, leading, leave, left, level, like, limit, lines, load, lock, long, loop, low_priority, master_bind, master_heartbeat_period, master_ssl_verify_server_cert, match, middleint, minute_second, mod, mode, modify, natural, no_write_to_binlog, not, null, number, numeric, on, optimize, option, optionally, or, order, outer, outfile, partition, precision, primary, privileges, procedure, public, purge, read, real, references, release, rename, repeat, replace, require, resignal, restrict, return, revoke, right, rlike, rows, schema, schemas, select, separator, session, set, share, show, signal, spatial, sql_after_gtids, sql_before_gtids, sql_big_result, sql_calc_found_rows, sql_small_result, sqlstate, ssl, start, starting, straight_join, table, terminated, then, to, trailing, trigger, true, undo, union, unique, unlock, unsigned, update, usage, use, user, using, values, varcharacter, varying, view, when, where, while, with, write, year_month, zerofill';
$field_not_allow = explode( ',', $field_not_allow );
$field_not_allow = array_map( 'trim', $field_not_allow );
$field_not_allow = array_unique( $field_not_allow );