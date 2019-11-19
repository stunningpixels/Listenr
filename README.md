# Listenr

## Running Local Tests

### Test requirements and setup

* mysql
* ruby
* php

Tests run with ruby & cucumber, install gems in `component-tests/cucumber` with:
```
$ bundle install
```
The `mysql2` gem seems to be a little awkward so may have to install individually with reference to your mysql-config file.
```
$ gem install mysql2 -- --with-mysql-config=/usr/local/mysql/bin/mysql_config
```

your local mysql instance password is needed as an environment variable e.g. set this in your shell session or add to `test` script.
```
export DB_PASSWORD="your password"
```

### Running

```
$ ./test
```
This will set up the mock environment and the listenr php application locally and then run the all test scenarios.

Individual features can be run with their path. e.g.
```
$ ./test features/initial.feature
```

for more detailed test logging (prints messages) run with...
```
$  ./test DEBUG_MODE=true
```