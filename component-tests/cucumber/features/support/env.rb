require 'json'
require 'rest-assured'
require 'rest-client'
require 'mysql2'

AfterConfiguration do

  LISTENR_PORT = ENV['LISTENR_PORT']
  FB_MOCK_PORT = ENV['FB_MOCK_PORT']
  DB_PASSWORD  = ENV['DB_PASSWORD']
  DEBUG_MODE   = ENV.fetch('DEBUG_MODE', false)

  DB_CONFIG = {
    :user => 'root',
    :host => 'localhost',
    :database => 'listenr_test',
    :password => DB_PASSWORD,
  }

  LISTENR_URL = "localhost:#{LISTENR_PORT}"

  STDOUT.puts "DEBUG_MODE: #{DEBUG_MODE}"
  STDOUT.puts "LISTENR_URL: #{LISTENR_URL}"
  STDOUT.puts "FB_MOCK_URL: localhost:#{FB_MOCK_PORT}"

  # we set $API_ENDPOINT
  RestAssured::Server.start(database: ':memory:', port: FB_MOCK_PORT)

  # setup db options
  DB.query('INSERT INTO `campaigns` (`id`, `title`) VALUES (?, ?)', 1, 'Brexit')
  DB.query('INSERT INTO `values` (`text`, `campaign_id`) VALUES (?, ?)', 'Leave', 1)
  DB.query('INSERT INTO `values` (`text`, `campaign_id`) VALUES (?, ?)', 'Remain', 1)

end