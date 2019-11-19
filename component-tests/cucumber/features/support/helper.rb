

def load_json_fixture(name)
  File.open("fixtures/#{name}.json", 'r') do |fp|
    JSON.load fp
  end
end

def load_response_fixture(fixture_info)
  name = "responses/#{fixture_info['message']}"
  fixture = load_json_fixture(name)
  fixture['recipient']['id'] = fixture_info['recipient_id']
  if fixture_info.key?('text') and !fixture_info['text'].empty?
    fixture['message']['text'] = fixture_info['text']
  end
  fixture
end


class DB

  def self.query(query_string, *args)
    begin
      client = Mysql2::Client.new(
        :host => DB_CONFIG[:host],
        :username => DB_CONFIG[:user],
        :password => DB_CONFIG[:password],
        :database => DB_CONFIG[:database]
      )
      stmt = client.prepare(query_string)
      results = stmt.execute(*args)
      return results
    ensure
      client.close
    end
  end

end