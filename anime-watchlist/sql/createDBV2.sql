CREATE DATABASE IF NOT EXISTS animewatchlistV2;

USE animewatchlistV2;

CREATE TABLE anime (
    id INT AUTO_INCREMENT,
    title VARCHAR(100),
    episodes_watched INT,
    total_episodes INT,
    status VARCHAR(20),
    dubbed VARCHAR(10),
    start_date DATE,
    PRIMARY KEY (id)
);
