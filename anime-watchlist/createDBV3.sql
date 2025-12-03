USE animewatchlistV1;

CREATE TABLE IF NOT EXISTS genres (
    id INT AUTO_INCREMENT,
    genre_name VARCHAR(50),
    PRIMARY KEY (id)
);

INSERT INTO genres (genre_name) VALUES
('Action'),
('Adventure'),
('Comedy'),
('Drama'),
('Fantasy'),
('Horror'),
('Isekai'),
('Romance'),
('Seinen'),
('Shonen'),
('Slice of Life'),
('Sports');
