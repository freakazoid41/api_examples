CREATE TABLE "users" (
  "id" SERIAL PRIMARY KEY NOT NULL,
  "title" varchar(50) NOT NULL,
  "username" varchar(50) NOT NULL,
  "password" varchar(50) NOT NULL,
  "type" smallint NOT NULL DEFAULT 0,
  "barcode" varchar(200),
  "created_at" timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE "menus" (
  "id" SERIAL PRIMARY KEY NOT NULL,
  "user_id" integer NOT NULL,
  "title" varchar(50) NOT NULL,
  "parent_id" integer NOT NULL DEFAULT 0,
  "price" decimal NOT NULL DEFAULT 0,
  "currency_id" integer NOT NULL DEFAULT 1,
  "image" varchar(150),
  "created_at" timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE "currencies" (
  "id" SERIAL PRIMARY KEY NOT NULL,
  "symbol" varchar(10),
  "key" varchar(5),
  "created_at" timestamp NOT NULL DEFAULT (now())
);

CREATE TABLE "users_keys" (
  "id" SERIAL PRIMARY KEY NOT NULL,
  "user_id" int NOT NULL,
  "user_label" varchar(150) NOT NULL,
  "user_key" varchar(150) NOT NULL,
  "end_at" timestamp NOT NULL DEFAULT (now()),
  "created_at" timestamp NOT NULL DEFAULT (now())
);

ALTER TABLE "menus" ADD FOREIGN KEY ("currency_id") REFERENCES "currencies" ("id");

ALTER TABLE "menus" ADD FOREIGN KEY ("user_id") REFERENCES "users" ("id");
