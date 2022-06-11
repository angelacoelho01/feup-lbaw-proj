BEGIN;
LOCK TABLE residencies IN EXCLUSIVE MODE;
SELECT setval('residencies_id_seq', COALESCE((SELECT MAX(id)+1 FROM residencies), 1), false);
COMMIT;
