-- database.sql
-- Made with love ❤️ — Alasly
-- For PostgreSQL / Supabase: creates the private schema table and a public view
-- so the REST API can access access_codes without exposing the private schema.

create schema if not exists school_platform;

create table if not exists school_platform.access_codes (
    id         bigserial primary key,
    code       varchar(50)  not null,
    video_id   varchar(255) not null,
    is_used    boolean      not null default false,
    created_at timestamp with time zone not null default now(),
    used_at    timestamp with time zone null,
    constraint uq_access_codes_code unique (code)
);

create or replace view public.access_codes as
select
    id,
    code,
    video_id,
    is_used,
    created_at,
    used_at
from school_platform.access_codes;

grant select, insert, update, delete on public.access_codes to service_role;
grant usage on sequence school_platform.access_codes_id_seq to service_role;
