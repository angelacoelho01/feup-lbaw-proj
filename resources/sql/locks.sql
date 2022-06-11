BEGIN;
LOCK TABLE users IN EXCLUSIVE MODE;
SELECT setval('users_id_seq', COALESCE((SELECT MAX(id)+1 FROM users), 1), false);
COMMIT;

BEGIN;
LOCK TABLE notifications IN EXCLUSIVE MODE;
SELECT setval('notifications_id_seq', COALESCE((SELECT MAX(id)+1 FROM notifications), 1), false);
COMMIT;

BEGIN;
LOCK TABLE bans IN EXCLUSIVE MODE;
SELECT setval('bans_id_seq', COALESCE((SELECT MAX(id)+1 FROM bans), 1), false);
COMMIT;

BEGIN;
LOCK TABLE cities IN EXCLUSIVE MODE;
SELECT setval('cities_id_seq', COALESCE((SELECT MAX(id)+1 FROM cities), 1), false);
COMMIT;

BEGIN;
LOCK TABLE comments IN EXCLUSIVE MODE;
SELECT setval('comments_id_seq', COALESCE((SELECT MAX(id)+1 FROM comments), 1), false);
COMMIT;

BEGIN;
LOCK TABLE educations IN EXCLUSIVE MODE;
SELECT setval('educations_id_seq', COALESCE((SELECT MAX(id)+1 FROM educations), 1), false);
COMMIT;

BEGIN;
LOCK TABLE employments IN EXCLUSIVE MODE;
SELECT setval('employments_id_seq', COALESCE((SELECT MAX(id)+1 FROM employments), 1), false);
COMMIT;

BEGIN;
LOCK TABLE friend_requests IN EXCLUSIVE MODE;
SELECT setval('friend_requests_id_seq', COALESCE((SELECT MAX(id)+1 FROM friend_requests), 1), false);
COMMIT;

BEGIN;
LOCK TABLE is_group_admin IN EXCLUSIVE MODE;
SELECT setval('is_group_admin_id_seq', COALESCE((SELECT MAX(id)+1 FROM is_group_admin), 1), false);
COMMIT;

BEGIN;
LOCK TABLE join_requests IN EXCLUSIVE MODE;
SELECT setval('join_requests_id_seq', COALESCE((SELECT MAX(id)+1 FROM join_requests), 1), false);
COMMIT;

BEGIN;
LOCK TABLE multimedia_contents IN EXCLUSIVE MODE;
SELECT setval('multimedia_contents_id_seq', COALESCE((SELECT MAX(id)+1 FROM multimedia_contents), 1), false);
COMMIT;

BEGIN;
LOCK TABLE places IN EXCLUSIVE MODE;
SELECT setval('places_id_seq', COALESCE((SELECT MAX(id)+1 FROM places), 1), false);
COMMIT;

BEGIN;
LOCK TABLE posts IN EXCLUSIVE MODE;
SELECT setval('posts_id_seq', COALESCE((SELECT MAX(id)+1 FROM posts), 1), false);
COMMIT;

BEGIN;
LOCK TABLE reactions IN EXCLUSIVE MODE;
SELECT setval('reactions_id_seq', COALESCE((SELECT MAX(id)+1 FROM reactions), 1), false);
COMMIT;

BEGIN;
LOCK TABLE tags IN EXCLUSIVE MODE;
SELECT setval('tags_id_seq', COALESCE((SELECT MAX(id)+1 FROM tags), 1), false);
COMMIT;

BEGIN;
LOCK TABLE user_groups IN EXCLUSIVE MODE;
SELECT setval('user_groups_id_seq', COALESCE((SELECT MAX(id)+1 FROM user_groups), 1), false);
COMMIT;
