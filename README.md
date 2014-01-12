# IMDb-actor-connections

Import and search IMDb for connections between actors.

## Usage

Create a new database and matching `app/config/db.config.php`. Import the schema from `app/Resources/database.sql`.

Download `actors.list` and `actresses.list` from IMDb's FTP interface, then run the following to import (it will take quite a while...)
````
app/console imdb:import ~/Downloads/actors.list
app/console imdb:import ~/Downloads/actresses.list
````

## Todo

 - Complete Bacon-number search command
 - Create any actor-to-actor search command
 - Implement easier filtering to temporarily remove specific movies/actors from the results
