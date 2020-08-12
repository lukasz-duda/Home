#!/bin/bash
mysql --login-path=local --database=home_test <src/CarMaintenance/Infrastructure/database.sql
mysql --login-path=local --database=home_test <src/CatFeeding/Infrastructure/database.sql
mysql --login-path=local --database=home_test <src/Coffee/Infrastructure/database.sql
mysql --login-path=local --database=home_test <src/Flat/Infrastructure/database.sql
mysql --login-path=local --database=home_test <src/Knowledge/Infrastructure/database.sql
mysql --login-path=local --database=home_test <src/Shopping/Infrastructure/database.sql
mysql --login-path=local --database=home_test <src/ToDo/Infrastructure/database.sql

mysql --login-path=local --database=home <src/CarMaintenance/Infrastructure/database.sql
mysql --login-path=local --database=home <src/CatFeeding/Infrastructure/database.sql
mysql --login-path=local --database=home <src/Coffee/Infrastructure/database.sql
mysql --login-path=local --database=home <src/Flat/Infrastructure/database.sql
mysql --login-path=local --database=home <src/Knowledge/Infrastructure/database.sql
mysql --login-path=local --database=home <src/Shopping/Infrastructure/database.sql
mysql --login-path=local --database=home <src/ToDo/Infrastructure/database.sql
