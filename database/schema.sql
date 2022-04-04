CREATE TABLE project (
    # add unsigned attribute
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) Engine=InnoDB;

CREATE TABLE task (
    # add unsigned attribute
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    # add unsigned attribute
    project_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    status VARCHAR(16) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    #add foreign key to project table
) Engine=InnoDB;
