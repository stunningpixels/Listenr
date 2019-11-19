require 'json'
require 'rest-assured'
require 'rest-client'
require 'mysql2'

AfterConfiguration do

  LISTENR_URL = ENV['LISTENR_URL']
  MOCK_PORT = ENV['MOCK_PORT']

  DB_CONFIG = {
    :user => 'root',
    :host => 'localhost',
    :database => 'listenr_test',
    :password => ENV['DB_PASSWORD'],
  }

  DEBUG = ENV['DEBUG_MODE']

  STDOUT.puts "LISTENR_URL: #{LISTENR_URL}"
  STDOUT.puts "MOCK_PORT: #{MOCK_PORT}"

  # we set $API_ENDPOINT
  RestAssured::Server.start(database: ':memory:', port: ENV['MOCK_PORT'])

  # setup db options
  DB.query('INSERT INTO `campaigns` (`id`, `title`) VALUES (?, ?)', 1, 'Brexit')
  DB.query('INSERT INTO `values` (`text`, `campaign_id`) VALUES (?, ?)', 'Leave', 1)
  DB.query('INSERT INTO `values` (`text`, `campaign_id`) VALUES (?, ?)', 'Remain', 1)

end