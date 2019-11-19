Feature: In coversation

  Scenario: users send each other messages
    Given registered senders in our database
      | id     | status                 | session |
      | 404040 | SENDER_IN_CONVERSATION | 777777  |
      | 777777 | SENDER_IN_CONVERSATION | 404040  |
    When we recieve a "message" from the sender id "404040"
      | text                              |
      | I agree, you were right after all |
    Then the following messages are sent in response
      | recipient_id | message      | text                              |
      | 777777       | text_message | I agree, you were right after all |
    When we recieve a "message" from the sender id "777777"
      | text      |
      | Thank you |
    Then the following messages are sent in response
      | recipient_id | message      | text      |
      | 404040       | text_message | Thank you |

  Scenario: user types 'end'
    Given registered senders in our database
      | id     | status                 | session |
      | 404040 | SENDER_IN_CONVERSATION | 777777  |
      | 777777 | SENDER_IN_CONVERSATION | 404040  |
    When we recieve a "message" from the sender id "404040"
      | text |
      | end  |
    Then the following messages are sent in response
      | recipient_id | message     |
      | 404040       | confirm_end |

  Scenario: user ends coversation
    Given registered senders in our database
      | id     | status                 | session |
      | 404040 | SENDER_IN_CONVERSATION | 777777  |
      | 777777 | SENDER_IN_CONVERSATION | 404040  |
    When we recieve a "postback" from the sender id "404040"
      | title        | payload |
      | End the chat | 0       |
    Then the following messages are sent in response
      | recipient_id | message         | text                                    |
      | 777777       | text_message    | Your partner has ended the conversation |
      | 404040       | text_message    | The conversation has ended              |
      | 777777       | choose_campaign |                                         |
      | 404040       | choose_campaign |                                         |
    And the sender id "777777" now has the status "SENDER_CHOOSE_CAMPAIGN"
    And the sender id "777777" now has the session value ""
    And the sender id "404040" now has the status "SENDER_CHOOSE_CAMPAIGN"
    And the sender id "404040" now has the session value ""