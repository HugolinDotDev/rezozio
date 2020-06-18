--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;

--
-- Name: rezozio; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA rezozio;


--
-- Name: SCHEMA rezozio; Type: COMMENT; Schema: -; Owner: -
--

COMMENT ON SCHEMA rezozio IS 'projet 2020';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: messages; Type: TABLE; Schema: rezozio; Owner: -; Tablespace: 
--

CREATE TABLE rezozio.messages (
    id integer NOT NULL,
    author character varying(25) NOT NULL,
    content text NOT NULL,
    datetime timestamp with time zone DEFAULT now() NOT NULL
);


--
-- Name: TABLE messages; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON TABLE rezozio.messages IS 'messages publiés';


--
-- Name: COLUMN messages.id; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.messages.id IS 'généré automatiquement, par ordre croissant';


--
-- Name: COLUMN messages.datetime; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.messages.datetime IS 'instant de publication. généré automatiquement.';


--
-- Name: messages_id_seq; Type: SEQUENCE; Schema: rezozio; Owner: -
--

CREATE SEQUENCE rezozio.messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: messages_id_seq; Type: SEQUENCE OWNED BY; Schema: rezozio; Owner: -
--

ALTER SEQUENCE rezozio.messages_id_seq OWNED BY rezozio.messages.id;


--
-- Name: subscriptions; Type: TABLE; Schema: rezozio; Owner: -; Tablespace: 
--

CREATE TABLE rezozio.subscriptions (
    follower character varying(25) NOT NULL,
    target character varying(25) NOT NULL
);


--
-- Name: TABLE subscriptions; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON TABLE rezozio.subscriptions IS 'relation "suivre"';


--
-- Name: COLUMN subscriptions.follower; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.subscriptions.follower IS 'utilisateur qui suit';


--
-- Name: COLUMN subscriptions.target; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.subscriptions.target IS 'utilisateur suivi';


--
-- Name: users; Type: TABLE; Schema: rezozio; Owner: -; Tablespace: 
--

CREATE TABLE rezozio.users (
    login character varying(25) NOT NULL,
    pseudo character varying(25) NOT NULL,
    description character varying(1024) DEFAULT ''::character varying NOT NULL,
    password text NOT NULL,
    avatar_type text,
    avatar_small bytea,
    avatar_large bytea
);


--
-- Name: TABLE users; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON TABLE rezozio.users IS 'utilisateurs';


--
-- Name: COLUMN users.login; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.users.login IS 'user id';


--
-- Name: COLUMN users.password; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.users.password IS 'attention : aucun mot de passe en clair';


--
-- Name: COLUMN users.avatar_type; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.users.avatar_type IS 'mimetype';


--
-- Name: COLUMN users.avatar_small; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.users.avatar_small IS '48x48';


--
-- Name: COLUMN users.avatar_large; Type: COMMENT; Schema: rezozio; Owner: -
--

COMMENT ON COLUMN rezozio.users.avatar_large IS '256x256';


--
-- Name: id; Type: DEFAULT; Schema: rezozio; Owner: -
--

ALTER TABLE ONLY rezozio.messages ALTER COLUMN id SET DEFAULT nextval('rezozio.messages_id_seq'::regclass);


--
-- Data for Name: messages; Type: TABLE DATA; Schema: rezozio; Owner: -
--



--
-- Name: messages_id_seq; Type: SEQUENCE SET; Schema: rezozio; Owner: -
--

SELECT pg_catalog.setval('rezozio.messages_id_seq', 27, true);


--
-- Data for Name: subscriptions; Type: TABLE DATA; Schema: rezozio; Owner: -
--



--
-- Data for Name: users; Type: TABLE DATA; Schema: rezozio; Owner: -
--

INSERT INTO rezozio.users VALUES ('aporation', 'Ève Aporation', '', '$2y$10$//IwI6O9e1YbiSp4W//6v.8s6AOo7w0hqQLhC6PqjSr.dC6.1XmOi', NULL, NULL, NULL);
INSERT INTO rezozio.users VALUES ('mallani', 'Annie Malle', '', '$2y$10$m09t6nVdVgjw/qD7hkowFOd4AWtQI5jukA73Cq0D2mfZM0chMPda2', NULL, NULL, NULL);


--
-- Name: messages_pkey; Type: CONSTRAINT; Schema: rezozio; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rezozio.messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (id);


--
-- Name: subscriptions_pkey; Type: CONSTRAINT; Schema: rezozio; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rezozio.subscriptions
    ADD CONSTRAINT subscriptions_pkey PRIMARY KEY (follower, target);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: rezozio; Owner: -; Tablespace: 
--

ALTER TABLE ONLY rezozio.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (login);


--
-- Name: messages_author_fkey; Type: FK CONSTRAINT; Schema: rezozio; Owner: -
--

ALTER TABLE ONLY rezozio.messages
    ADD CONSTRAINT messages_author_fkey FOREIGN KEY (author) REFERENCES rezozio.users(login);


--
-- Name: subscriptions_follower_fkey; Type: FK CONSTRAINT; Schema: rezozio; Owner: -
--

ALTER TABLE ONLY rezozio.subscriptions
    ADD CONSTRAINT subscriptions_follower_fkey FOREIGN KEY (follower) REFERENCES rezozio.users(login);


--
-- Name: subscriptions_target_fkey; Type: FK CONSTRAINT; Schema: rezozio; Owner: -
--

ALTER TABLE ONLY rezozio.subscriptions
    ADD CONSTRAINT subscriptions_target_fkey FOREIGN KEY (target) REFERENCES rezozio.users(login);


--
-- PostgreSQL database dump complete
--
