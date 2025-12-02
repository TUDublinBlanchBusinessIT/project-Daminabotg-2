CREATE DATABASE IF NOT EXISTS animewatchlistV1;

USE animewatchlistV1;

CREATE TABLE anime (
    id INT AUTO_INCREMENT,
    title VARCHAR(100),
    episodes_watched INT,
    total_episodes INT,
    status VARCHAR(20),
    start_date DATE,
    PRIMARY KEY (id)
);
