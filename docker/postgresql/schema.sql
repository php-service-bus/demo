CREATE TABLE IF NOT EXISTS scheduler_registry
(
    id              uuid PRIMARY KEY,
    processing_date timestamp NOT NULL,
    command         bytea     NOT NULL,
    is_sent         bool      NOT NULL
);

CREATE TABLE IF NOT EXISTS sagas_store
(
    id               UUID,
    identifier_class VARCHAR   NOT NULL,
    saga_class       VARCHAR   NOT NULL,
    payload          BYTEA     NOT NULL,
    state_id         VARCHAR   NOT NULL,
    created_at       TIMESTAMP NOT NULL,
    expiration_date  TIMESTAMP NOT NULL,
    closed_at        TIMESTAMP,
    CONSTRAINT saga_identifier PRIMARY KEY (id, identifier_class)
);

CREATE TABLE IF NOT EXISTS sagas_association
(
    id  UUID,
    saga_id          uuid         not null,
    identifier_class varchar(255) not null,
    saga_class       varchar(255) not null,
    property_name    varchar(255) not null,
    property_value   varchar(255) not null,
    CONSTRAINT sagas_association_pk PRIMARY KEY (id)
);


CREATE INDEX IF NOT EXISTS sagas_state ON sagas_store (state_id);
CREATE INDEX IF NOT EXISTS saga_closed_index ON sagas_store (state_id, closed_at);
CREATE UNIQUE INDEX IF NOT EXISTS  sagas_association_property ON sagas_association (saga_id, saga_class, property_name);
CREATE INDEX IF NOT EXISTS  sagas_association_property_value ON sagas_association (identifier_class, saga_class, property_name, property_value);

CREATE TABLE IF NOT EXISTS event_store_stream
(
    id               uuid PRIMARY KEY,
    identifier_class varchar   NOT NULL,
    aggregate_class  varchar   NOT NULL,
    created_at       timestamp NOT NULL,
    closed_at        timestamp
);

CREATE TABLE IF NOT EXISTS event_store_stream_events
(
    id          uuid PRIMARY KEY,
    stream_id   uuid,
    playhead    int       NOT NULL,
    event_class varchar   NOT NULL,
    payload     bytea     NOT NULL,
    occured_at  timestamp NOT NULL,
    recorded_at timestamp NOT NULL,
    canceled_at timestamp,
    CONSTRAINT event_store_stream_fk FOREIGN KEY (stream_id) REFERENCES event_store_stream (id) ON DELETE CASCADE
);

CREATE UNIQUE INDEX IF NOT EXISTS event_store_stream_identifier ON event_store_stream (id, identifier_class);
CREATE UNIQUE INDEX IF NOT EXISTS event_store_stream_events_playhead ON event_store_stream_events (stream_id, playhead) WHERE canceled_at IS NULL;
CREATE INDEX IF NOT EXISTS event_store_stream_events_stream ON event_store_stream_events (id, stream_id);

CREATE TABLE IF NOT EXISTS event_sourcing_indexes
(
    index_tag  varchar NOT NULL,
    value_key  varchar NOT NULL,
    value_data varchar NOT NULL,
    CONSTRAINT event_sourcing_indexes_pk PRIMARY KEY (index_tag, value_key)
);

CREATE TABLE IF NOT EXISTS event_store_snapshots
(
    id                 uuid PRIMARY KEY,
    aggregate_id_class varchar   NOT NULL,
    aggregate_class    varchar   NOT NULL,
    version            int       NOT NULL,
    payload            bytea     NOT NULL,
    created_at         timestamp NOT NULL,
    CONSTRAINT event_store_snapshots_fk FOREIGN KEY (id) REFERENCES event_store_stream (id) ON DELETE CASCADE
);

CREATE UNIQUE INDEX IF NOT EXISTS event_store_snapshots_aggregate ON event_store_snapshots (id, aggregate_id_class);
CREATE UNIQUE INDEX IF NOT EXISTS event_store_snapshots_identifier ON event_store_snapshots (id, aggregate_id_class);

CREATE TABLE IF NOT EXISTS failed_messages
(
    id              uuid constraint failed_messages_pk   primary key,
    message_id      uuid      not null,
    trace_id        uuid      not null,
    message_hash    varchar   not null,
    message_class   varchar   not null,
    message_payload bytea     not null,
    failure_context jsonb     not null,
    recorded_at     timestamp not null,
    recovered_at    timestamp
);

CREATE index IF NOT EXISTS failed_messages_message_hash on failed_messages (message_hash);
CREATE index IF NOT EXISTS failed_messages_message_id on failed_messages (message_id);
CREATE index IF NOT EXISTS failed_messages_recovered_at on failed_messages (recorded_at);

CREATE TABLE IF NOT EXISTS customer
(
    id      uuid PRIMARY KEY,
    profile jsonb NOT NULL
);

CREATE TABLE IF NOT EXISTS vehicle_brand
(
    id    uuid PRIMARY KEY,
    title varchar not null
);

CREATE UNIQUE INDEX IF NOT EXISTS vehicle_brand ON vehicle_brand (title);

INSERT INTO vehicle_brand (id, title)
VALUES ('5d592299-82ed-46e4-b605-4599a47c213c', 'BMW'),
       ('bb306f48-142a-4dc0-82d0-bae6d6ce57ad', 'Mercedes-Benz'),
       ('dce1390a-cdef-46f5-92c6-c2aacafff3da', 'Honda'),
       ('49063d56-5686-4d19-b383-4e7428c2e796', 'Hummer'),
       ('b51ada61-cfd3-4518-8383-7e5186760a91', 'Audi'),
       ('759529ea-27a2-4483-a27b-ed113d554a0f', 'Bentley')
ON CONFLICT DO NOTHING;

CREATE TABLE IF NOT EXISTS document_store
(
    id             uuid PRIMARY KEY,
    file_name      varchar   NOT NULL,
    extension      varchar   NOT NULL,
    mime_type_base varchar   NOT NULL,
    mime_type_sub  varchar   NOT NULL,
    file_path      varchar   NOT NULL,
    created_at     timestamp NOT NULL
);