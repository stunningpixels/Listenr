
Given("the sender id {string} is not in our database") do |sender_id|
  DB.query("DELETE FROM client_status where sender = ?", sender_id)
end

Given("the senders in the queue") do |table|
  values = {'Leave' => 1, 'Remain' => 2}
  table.hashes.each do |queue|
    DB.query(
      "INSERT INTO queue (sender, campaign_id, value_id) VALUES (?, ?, ?)",
      queue['sender'], 1, values[queue['value']]
    )
  end
end

Given("registered senders in our database") do |table|
  table.hashes.each do |sender|
    DB.query(
      "INSERT INTO client_status (sender, status, session) VALUES (?, ?, ?)",
      sender['id'], sender['status'], sender['session']
    )
  end
end

When("we recieve a {string} from the sender id {string}") do |message_type, sender_id, table|
  @FB_DOUBLE = RestAssured::Double.create(fullpath: '/messages', verb: 'POST')
  message = load_json_fixture('message')
  message['entry'][0]['messaging'][0][message_type] = table.hashes.first
  message['entry'][0]['messaging'][0]['sender']['id'] = sender_id
  STDOUT.puts message if DEBUG
  RestClient.post(LISTENR_URL, message.to_json, {content_type: :json, accept: :json})
end

Then("the following messages are sent in response") do |table|
  expected_count = table.hashes.size
  @FB_DOUBLE.wait_for_requests(expected_count, timeout: 5)
  @FB_DOUBLE.reload
  # check we recived the right number of messages
  actual_count = @FB_DOUBLE.requests.size
  expect(actual_count).to eq(expected_count)
  # check all essages match our expectation
  @FB_DOUBLE.requests.each do |res|
    expected = load_response_fixture(table.hashes.shift)
    actual = JSON.parse(res.body)
    STDOUT.puts JSON.pretty_generate(actual) if DEBUG
    expect(actual).to eq(expected)
  end
end

Then("the sender id {string} now has the status {string}") do |sender_id, expected|
  results = DB.query("SELECT * FROM client_status WHERE sender=?", sender_id)
  actual = results.first['status']
  expect(actual).to eq(expected)
end

Then("the sender id {string} now has the session value {string}") do |sender_id, expected|
  results = DB.query("SELECT * FROM client_status WHERE sender=?", sender_id)
  actual = results.first['session']
  expect(actual).to eq(expected)
end

After do |s|
  # delete all client_status db entries after each senario
  DB.query("DELETE FROM client_status")
  DB.query("DELETE FROM queue")
end