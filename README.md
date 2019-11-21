# Listenr

A chatbot that connects people with opposing views, in the hope that we can start to understand each other a little better. Think omeagle meets politics

## Running Local Tests

### Test requirements and setup
Tests run with ruby & cucumber, install gems in `component-tests/cucumber` with:
```
$ bundle install
```
You need a MySQL instance running installed and running locally—could probably change the config to point to a remote instance.

The `mysql2` ruby gem seems to be a little awkward so may have to install individually with reference to your mysql-config file.
```
$ gem install mysql2 -- --with-mysql-config=/usr/local/mysql/bin/mysql_config
```

### Running
Your local MySQL instance password is needed as an environment variable e.g. set this in your shell session or add to `test` script.
```
export DB_PASSWORD="your password"
```
Set up the mock environment and the listenr php application locally and then run the all test scenarios. For more detailed logging, set `DEBUG_MODE=true`.
```
$ ./test
```