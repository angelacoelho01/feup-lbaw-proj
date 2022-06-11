CREATE SCHEMA IF NOT EXISTS lbaw2133;
SET search_path TO lbaw2133;
-- Drop tables
DROP TABLE IF EXISTS friend_requests CASCADE;
DROP TABLE IF EXISTS join_requests CASCADE;
DROP TABLE IF EXISTS employments CASCADE;
DROP TABLE IF EXISTS educations CASCADE;
DROP TABLE IF EXISTS places CASCADE;
DROP TABLE IF EXISTS multimedia_contents CASCADE;
DROP TABLE IF EXISTS tags CASCADE;
DROP TABLE IF EXISTS bans CASCADE;
DROP TABLE IF EXISTS cities CASCADE;
DROP TABLE IF EXISTS posts CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS comments CASCADE;
DROP TABLE IF EXISTS notifications CASCADE;
DROP TABLE IF EXISTS reactions CASCADE;
DROP TABLE IF EXISTS user_groups CASCADE;
DROP TABLE IF EXISTS is_group_admin CASCADE; -- DOES NOT ADHERE TO PLURAL CONVENTION
DROP TABLE IF EXISTS residencies CASCADE;
-- Drop types
DROP TYPE IF EXISTS place_type CASCADE;
DROP TYPE IF EXISTS belief CASCADE;
DROP TYPE IF EXISTS ideology CASCADE;
DROP TYPE IF EXISTS notification_type CASCADE;
DROP TYPE IF EXISTS content_type CASCADE;
-- Drop triggers
DROP TRIGGER IF EXISTS user_search_update ON users;
DROP TRIGGER IF EXISTS comment_search_update ON comments;
DROP TRIGGER IF EXISTS group_search_update ON user_groups;
DROP TRIGGER IF EXISTS user_search_update ON users;
DROP TRIGGER IF EXISTS check_inviter_admin ON join_requests;
DROP TRIGGER IF EXISTS add_friend_request_notification ON friend_requests;
DROP TRIGGER IF EXISTS add_like_post_notification ON reactions;
DROP TRIGGER IF EXISTS add_comment_post_notification ON comments;
DROP TRIGGER IF EXISTS add_like_comment_notification ON reactions;
DROP TRIGGER IF EXISTS add_reply_comment_notification ON comments;
DROP TRIGGER IF EXISTS add_join_request_notification ON join_requests;
-- Drop indexes
DROP INDEX IF EXISTS post_idx;
DROP INDEX IF EXISTS comment_idx;
DROP INDEX IF EXISTS group_idx;
-- Types
CREATE TYPE ideology AS ENUM (
  'Anarchism',
  'Liberalism',
  'Conservatism',
  'Socialism',
  'Communism'
);
CREATE TYPE belief AS ENUM (
  'Christianity',
  'Islamism',
  'Hinduism',
  'Buddism',
  'Judaism'
);
CREATE TYPE place_type AS ENUM ('School', 'University', 'Job');
CREATE TYPE content_type AS ENUM ('Photo', 'Video');
CREATE TYPE notification_type AS ENUM (
  'Friend Request',
  'Group Invite',
  'Like on Post',
  'Comment on Post',
  'Like on Comment',
  'Reply to Comment',
  'Join Group Request'
);
-- Tables
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  tsvectors TSVECTOR,
  name TEXT NOT NULL,
  email TEXT UNIQUE NOT NULL CHECK(email LIKE '%@%.%'),
  password TEXT NOT NULL CHECK (length(password) >= 8),
  is_public BOOLEAN NOT NULL DEFAULT TRUE,
  is_admin BOOLEAN NOT NULL DEFAULT FALSE,
  is_blocked BOOLEAN NOT NULL DEFAULT FALSE,
  motive TEXT,
  website TEXT,
  phone_number TEXT UNIQUE,
  gender TEXT,
  birthdate DATE,
  political_ideology ideology,
  religious_belief belief,
  nickname TEXT,
  profile_pic TEXT NOT NULL DEFAULT 'images/profiles/default.jpg',
  description TEXT
);
CREATE TABLE bans (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  date_time TIMESTAMP NOT NULL,
  motive TEXT NOT NULL,
  banned_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE user_groups (
  id SERIAL PRIMARY KEY,
  tsvectors TSVECTOR,
  name TEXT NOT NULL,
  description TEXT,
  is_public BOOLEAN NOT NULL,
  group_pic TEXT NOT NULL DEFAULT 'images/groups/default_group.png'
);

CREATE TABLE posts (
  id SERIAL PRIMARY KEY,
  tsvectors TSVECTOR,
  date_time TIMESTAMP NOT NULL DEFAULT NOW(),
  is_public BOOLEAN NOT NULL DEFAULT TRUE,
  post_text TEXT,
  user_id INTEGER REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  group_id INTEGER REFERENCES user_groups(id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE tags (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  post_id INTEGER REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE multimedia_contents (
  id SERIAL PRIMARY KEY,
  content_type content_type NOT NULL,
  post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
  content text NOT NULL
);
CREATE TABLE places (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL,
  type place_type NOT NULL,
  company TEXT,
  CHECK((type = 'Job'::place_type AND company IS NOT NULL) OR (type <> 'Job'::place_type AND company IS NULL ))
);
CREATE TABLE educations (
  id SERIAL PRIMARY KEY,
  field_of_study TEXT,
  description TEXT,
  start_date TIMESTAMP,
  end_date TIMESTAMP CHECK (start_date < end_date),
  place_id INTEGER REFERENCES places(id) ON DELETE SET NULL ON UPDATE CASCADE,
  user_id INTEGER REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
);
CREATE TABLE cities (
  id SERIAL PRIMARY KEY,
  name TEXT NOT NULL
);
CREATE TABLE employments (
  id SERIAL PRIMARY KEY,
  position TEXT,
  description TEXT,
  start_date TIMESTAMP,
  end_date TIMESTAMP CHECK (start_date < end_date),
  city_id INTEGER REFERENCES cities(id) ON DELETE SET NULL ON UPDATE CASCADE,
  user_id INTEGER REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  place_id INTEGER REFERENCES places(id) ON DELETE SET NULL ON UPDATE CASCADE
);
CREATE TABLE join_requests (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_group_id INTEGER REFERENCES user_groups(id) ON DELETE CASCADE ON UPDATE CASCADE,
  inviter_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  accepted BOOLEAN,
  is_request BOOLEAN,
  CHECK (
    (
      is_request IS FALSE
      AND inviter_id IS NOT NULL
    )
    OR(
      is_request IS TRUE
      AND inviter_id IS NULL
    )
  ),
  UNIQUE (user_id, user_group_id)
);
CREATE TABLE friend_requests (
  id SERIAL PRIMARY KEY,
  sender_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  receiver_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  accepted BOOLEAN NOT NULL DEFAULT FALSE,
  UNIQUE (sender_id, receiver_id)
);
CREATE TABLE comments (
  id SERIAL PRIMARY KEY,
  tsvectors TSVECTOR,
  content TEXT NOT NULL,
  date_time TIMESTAMP NOT NULL DEFAULT NOW(),
  user_id INTEGER REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  post_id INTEGER REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
  comment_id INTEGER REFERENCES comments(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CHECK (
    (
      post_id IS NULL
      AND comment_id IS NOT NULL
    )
    OR(
      comment_id IS NULL
      AND post_id IS NOT NULL
    )
  )
);
CREATE TABLE reactions (
  id SERIAL PRIMARY KEY,
  is_positive BOOLEAN NOT NULL,
  user_id INTEGER REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
  post_id INTEGER REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
  comment_id INTEGER REFERENCES comments(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CHECK (
    (
      post_id IS NULL
      AND comment_id IS NOT NULL
    )
    OR(
      comment_id IS NULL
      AND post_id IS NOT NULL
    )
  )
);
CREATE TABLE notifications (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  date_time TIMESTAMP NOT NULL,
  notification_type Notification_type NOT NULL,
  friend_request_id INTEGER REFERENCES friend_requests(id) ON DELETE CASCADE ON UPDATE CASCADE,
  join_request_id INTEGER REFERENCES join_requests(id) ON DELETE CASCADE ON UPDATE CASCADE,
  reaction_id INTEGER REFERENCES reactions(id) ON DELETE CASCADE ON UPDATE CASCADE,
  comment_id INTEGER REFERENCES comments(id) ON DELETE CASCADE ON UPDATE CASCADE
  CHECK (
    (notification_type = 'Friend Request' AND friend_request_id IS NOT NULL) OR
    (notification_type = 'Group Invite' AND join_request_id IS NOT NULL) OR
    (notification_type = 'Like on Post' AND reaction_id IS NOT NULL ) OR
    (notification_type = 'Comment on Post' AND comment_id IS NOT NULL) OR
    (notification_type = 'Like on Comment' AND reaction_id IS NOT NULL) OR
    (notification_type = 'Reply to Comment' AND comment_id IS NOT NULL) OR
    (notification_type = 'Join Group Request' AND join_request_id IS NOT NULL)
  )
);
CREATE TABLE is_group_admin (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  user_group_id INTEGER REFERENCES user_groups(id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE residencies (
  id SERIAL PRIMARY KEY,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  city_id INTEGER NOT NULL REFERENCES cities(id) ON DELETE CASCADE ON UPDATE CASCADE,
  is_current BOOLEAN NOT NULL,
  is_hometown BOOLEAN NOT NULL,
  is_old_city BOOLEAN NOT NULL
);
-- Functions
CREATE OR REPLACE FUNCTION post_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.post_text), 'A')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.post_text <> OLD.post_text) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.post_text), 'A')
            );
        END IF;
    END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION comment_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.content), 'A')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.content <> OLD.content) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.content), 'A')
            );
        END IF;
    END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION group_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A')
            );
        END IF;
    END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english', NEW.name), 'A')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF (NEW.name <> OLD.name) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.name), 'A')
            );
        END IF;
    END IF;
RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION check_inviter_admin() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.inviter_id IS NOT NULL THEN
    IF NOT EXISTS (SELECT * FROM is_group_admin WHERE user_id = NEW.inviter_id) THEN
      RAISE EXCEPTION 'Invite does not come from an administrator';
    END IF;
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION add_friend_request_notification() RETURNS TRIGGER AS
$BODY$
BEGIN

  INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
  VALUES (DEFAULT, NEW.receiver_id, NOW(), 'Friend Request', NEW.id, NULL, NULL, NULL);
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION add_group_invite_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.inviter_id IS NOT NULL THEN
      INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
      VALUES (DEFAULT, NEW.user_id, NOW(), 'Group Invite', NULL, NULL, NULL, NEW.id);
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;


CREATE OR REPLACE FUNCTION add_like_post_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.post_id IS NOT NULL THEN
      INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
      VALUES (DEFAULT, (SELECT user_id FROM posts WHERE id = NEW.post_id ), NOW(), 'Like on Post', NULL, NEW.id, NULL, NULL);
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION add_comment_post_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.post_id IS NOT NULL THEN
      INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
      VALUES (DEFAULT, (SELECT user_id FROM posts WHERE id = NEW.post_id ), NOW(), 'Comment on Post', NULL, NULL, NEW.id, NULL);
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION add_like_comment_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.comment_id IS NOT NULL THEN
      INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
      VALUES (DEFAULT, (SELECT user_id FROM comments WHERE id = NEW.comment_id), NOW(), 'Like on Comment', NULL, NEW.id, NULL, NULL);
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION add_reply_comment_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.comment_id IS NOT NULL THEN
      INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
      VALUES (DEFAULT, (SELECT user_id FROM comments WHERE id = NEW.comment_id), NOW(), 'Reply to Comment', NULL, NULL, NEW.id, NULL);
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION add_join_request_notification() RETURNS TRIGGER AS
$BODY$
BEGIN
  IF NEW.inviter_id IS NULL THEN
      INSERT INTO notifications(id, user_id, date_time, notification_type, friend_request_id, reaction_id, comment_id, join_request_id)
      VALUES (DEFAULT, NEW.user_id, NOW(), 'Join Group Request', NULL, NULL, NULL, NEW.id);
  END IF;
RETURN NEW;
END;
$BODY$
LANGUAGE plpgsql;

--Triggers
CREATE TRIGGER post_search_update
  BEFORE INSERT OR UPDATE ON posts
  FOR EACH ROW
  EXECUTE PROCEDURE post_search_update();

CREATE TRIGGER comment_search_update
  BEFORE INSERT OR UPDATE ON comments
  FOR EACH ROW
  EXECUTE PROCEDURE comment_search_update();

CREATE TRIGGER group_search_update
  BEFORE INSERT OR UPDATE ON user_groups
  FOR EACH ROW
  EXECUTE PROCEDURE group_search_update();

CREATE TRIGGER user_search_update
  BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE user_search_update();

CREATE TRIGGER check_inviter_admin
  BEFORE INSERT ON join_requests
  FOR EACH ROW
  EXECUTE PROCEDURE check_inviter_admin();

CREATE TRIGGER add_friend_request_notification
  AFTER INSERT ON friend_requests
  FOR EACH ROW
  EXECUTE PROCEDURE add_friend_request_notification();

CREATE TRIGGER add_group_invite_notification
  AFTER INSERT ON join_requests
  FOR EACH ROW
  EXECUTE PROCEDURE add_group_invite_notification();

CREATE TRIGGER add_like_post_notification
  AFTER INSERT ON reactions
  FOR EACH ROW
  EXECUTE PROCEDURE add_like_post_notification();

CREATE TRIGGER add_comment_post_notification
  AFTER INSERT ON comments
  FOR EACH ROW
  EXECUTE PROCEDURE add_comment_post_notification();

CREATE TRIGGER add_like_comment_notification
  AFTER INSERT ON reactions
  FOR EACH ROW
  EXECUTE PROCEDURE add_like_comment_notification();

CREATE TRIGGER add_reply_comment_notification
  AFTER INSERT ON comments
  FOR EACH ROW
  EXECUTE PROCEDURE add_reply_comment_notification();

CREATE TRIGGER add_join_request_notification
  AFTER INSERT ON join_requests
  FOR EACH ROW
  EXECUTE PROCEDURE add_join_request_notification();

--Indexes
CREATE INDEX post_idx ON posts USING GIN (tsvectors);
CREATE INDEX comment_idx ON comments USING GIN (tsvectors);
CREATE INDEX group_idx ON user_groups USING GIN (tsvectors);
CREATE INDEX user_idx ON users USING GIN (tsvectors);
