CREATE TABLE user
(
    id            INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    username      VARCHAR(50) UNIQUE                NOT NULL,
    password      VARCHAR(32) DEFAULT NULL,
    password_salt VARCHAR(32) DEFAULT NULL,
    full_name     VARCHAR(150)                      NOT NULL,
    locale        VARCHAR(5) DEFAULT NULL
);

INSERT INTO USER (id, username, password, password_salt, full_name, locale) VALUES (1, 'bruce', 'c5d24ce567ed92dc0f6a6cf06881f9b6', 'cc8d0760631a5526a0c0c230188a7214', 'Bruce Wayne', 'de_DE');
INSERT INTO USER (id, username, password, password_salt, full_name, locale) VALUES (2, 'clark', 'c5d24ce567ed92dc0f6a6cf06881f9b6', 'cc8d0760631a5526a0c0c230188a7214', 'Clark Kent', 'en_US');

CREATE TABLE report
(
    id             INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    owner_id       INTEGER                           NOT NULL,
    year           SMALLINT                          NOT NULL,
    number         SMALLINT                          NOT NULL,
    start_date     DATE                              NOT NULL,
    end_date       DATE                              NOT NULL,
    occasion       VARCHAR(255)                      NOT NULL,
    destination    VARCHAR(255)                      NOT NULL,
    classification VARCHAR(255) DEFAULT NULL,
    CONSTRAINT fk_owner FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
);
CREATE INDEX idx_owner_id ON report (owner_id);
CREATE UNIQUE INDEX idx_number ON report (owner_id, year, number);

INSERT INTO report (id, owner_id, year, number, start_date, end_date, occasion, destination, classification) VALUES (1, 1, 2019, 1, '2019-04-05', '2019-04-07', 'EU FOSSA2 Hackathon', 'Brüssel', 'Freiberufliche Tätigkeit');
INSERT INTO report (id, owner_id, year, number, start_date, end_date, occasion, destination, classification) VALUES (2, 1, 2019, 2, '2019-06-06', '2019-06-08', 'Dutch PHP Conference', 'Amsterdam', 'Freiberufliche Tätigkeit');
INSERT INTO report (id, owner_id, year, number, start_date, end_date, occasion, destination, classification) VALUES (3, 1, 2019, 3, '2019-09-24', '2019-09-27', 'SymfonyLive Berlin', 'Berlin', 'Freiberufliche Tätigkeit');

CREATE TABLE expense
(
    id          INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    report_id   INTEGER                           NOT NULL,
    type        VARCHAR(16)                       NOT NULL,
    date        DATE                              NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    gross       NUMERIC(10, 2)                    NOT NULL,
    tax         NUMERIC(10, 2)                    NOT NULL,
    CONSTRAINT fk_report FOREIGN KEY (report_id) REFERENCES report (id) NOT DEFERRABLE INITIALLY IMMEDIATE
);
CREATE INDEX idx_report_id ON expense (report_id);

INSERT INTO expense (report_id, type, date, description, gross, tax) VALUES (1, 'transportation', '2019-03-12', 'Train Tickets', 120, 19.16);
INSERT INTO expense (report_id, type, date, description, gross, tax) VALUES (1, 'accommodation', '2019-04-07', 'Hotel', 240, 0);
