SELECT a1.name, m1.title, a2.name, m2.title, a3.name, m3.title, a4.name, m4.title, a5.name
FROM cast AS m1c1

LEFT JOIN cast AS m1c2
ON m1c1.movie_id = m1c2.movie_id
AND m1c1.actor_id != m1c2.actor_id
AND m1c2.enabled = 1

LEFT JOIN cast AS m2c1
ON m1c1.movie_id != m2c1.movie_id
AND m1c2.actor_id = m2c1.actor_id
AND m2c1.enabled = 1

LEFT JOIN cast AS m2c2
ON m2c1.movie_id = m2c2.movie_id
AND m2c1.actor_id != m2c2.actor_id
AND m1c1.actor_id != m2c2.actor_id
AND m2c2.enabled = 1

LEFT JOIN cast AS m3c1
ON m1c1.movie_id != m3c1.movie_id
AND m2c1.movie_id != m3c1.movie_id
AND m2c2.actor_id = m3c1.actor_id
AND m3c1.enabled = 1

LEFT JOIN cast AS m3c2
ON m3c1.movie_id = m3c2.movie_id
AND m3c1.actor_id != m3c2.actor_id
AND m2c1.actor_id != m3c2.actor_id
AND m1c1.actor_id != m3c2.actor_id
AND m3c2.enabled = 1

LEFT JOIN cast AS m4c1
ON m1c1.movie_id != m4c1.movie_id
AND m2c1.movie_id != m4c1.movie_id
AND m3c1.movie_id != m4c1.movie_id
AND m3c2.actor_id = m4c1.actor_id
AND m4c1.enabled = 1

LEFT JOIN cast AS m4c2
ON m4c1.movie_id = m4c2.movie_id
AND m4c1.actor_id != m4c2.actor_id
AND m3c1.actor_id != m4c2.actor_id
AND m2c1.actor_id != m4c2.actor_id
AND m1c1.actor_id != m4c2.actor_id
AND m4c2.enabled = 1

INNER JOIN actors AS a1
ON a1.id = m1c1.actor_id
AND a1.name = 'Pegg, Simon'
AND a1.enabled = 1

INNER JOIN actors AS a2
ON a2.id = m1c2.actor_id
AND a2.enabled = 1

INNER JOIN actors AS a3
ON a3.id = m2c2.actor_id
AND a3.enabled = 1

INNER JOIN actors AS a4
ON a4.id = m3c2.actor_id
AND a4.enabled = 1

INNER JOIN actors AS a5
ON a5.id = m4c2.actor_id
AND a5.name = 'Perry, Matthew (I)'
AND a5.enabled = 1

INNER JOIN movies AS m1
ON m1.id = m1c1.movie_id
AND m1.enabled = 1

INNER JOIN movies AS m2
ON m2.id = m2c1.movie_id
AND m2.enabled = 1

INNER JOIN movies AS m3
ON m3.id = m3c1.movie_id
AND m3.enabled = 1

INNER JOIN movies AS m4
ON m4.id = m4c1.movie_id
AND m4.enabled = 1

LIMIT 10;
