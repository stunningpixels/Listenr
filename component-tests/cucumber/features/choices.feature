Feature: initial encounter

  Scenario: unknown sender (first message)
    Given the sender id "707070" is not in our database
    When we recieve a "message" from the sender id "707070"
      | text          |
      | hello listenr |
    Then the following messages are sent in response
      | recipient_id | message         | text                                                                                                                                                                             |
      | 707070       | text_message    | Hi there!\nWelcome to listenr, a way to connect with people that hold different views from yourself, in the hope that we can start to understand each other a little better 👫 |
      | 707070       | text_message    | In a moment you'll have to opportunity to chat 100% anonymously with another human                                                                                               |
      | 707070       | choose_campaign |                                                                                                                                                                                  |
    And the sender id "707070" now has the status "SENDER_CHOOSE_CAMPAIGN"

  Scenario: choose campaign, received postback message
    Given registered senders in our database
      | id  | status                 |
      | 666 | SENDER_CHOOSE_CAMPAIGN |
    When we recieve a "postback" from the sender id "666"
      | title  | payload |
      | Brexit | 1       |
    Then the following messages are sent in response
      | recipient_id | message       |
      | 666          | choose_values |
    And the sender id "666" now has the status "SENDER_CHOOSE_VALUE"
    And the sender id "666" now has the session value "1"

  Scenario: choose campaign, received normal message
    Given registered senders in our database
      | id     | status                 |
      | 666777 | SENDER_CHOOSE_CAMPAIGN |
    When we recieve a "message" from the sender id "666777"
      | text            |
      | I like politics |
    Then the following messages are sent in response
      | recipient_id | message         |
      | 666777       | choose_campaign |
    And the sender id "666777" now has the status "SENDER_CHOOSE_CAMPAIGN"

  Scenario: choose values, received postback message, go back
    Given registered senders in our database
      | id     | status              | session |
      | 666999 | SENDER_CHOOSE_VALUE | 1       |
    When we recieve a "postback" from the sender id "666999"
      | title          | payload |
      | Back to topics | BACK    |
    Then the following messages are sent in response
      | recipient_id | message         | text                                                                                                                                                                             |
      | 666999       | text_message    | Hi there!\nWelcome to listenr, a way to connect with people that hold different views from yourself, in the hope that we can start to understand each other a little better 👫 |
      | 666999       | text_message    | In a moment you'll have to opportunity to chat 100% anonymously with another human                                                                                               |
      | 666999       | choose_campaign |                                                                                                                                                                                  |
    And the sender id "666999" now has the status "SENDER_CHOOSE_CAMPAIGN"