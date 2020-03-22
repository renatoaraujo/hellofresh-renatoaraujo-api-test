DROP TABLE IF EXISTS recipe;
CREATE TABLE recipe (
  recipe_id UUID PRIMARY KEY NOT NULL,
  data JSONB NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX recipe_pkey
  ON recipe (recipe_id);

DROP TABLE IF EXISTS rate;
CREATE TABLE rate (
  recipe_id UUID NOT NULL,
  value INTEGER NOT NULL
);

ALTER TABLE recipe_rate
  ADD CONSTRAINT recipe_id_recipe_rate_id FOREIGN KEY (recipe_id) REFERENCES recipe (recipe_id)
  ON DELETE CASCADE;

