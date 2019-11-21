Feature: choose values and queue

  Scenario: choose value, matching senders in queue
    Given registered senders in our database
      | id     | status              | session |
      | 666999 | SENDER_CHOOSE_VALUE | 1       |
      | 777000 | SENDER_IN_QUEUE     | 1       |
    Given the senders in the queue
      | sender | value  |
      | 777000 | Remain |
    When we recieve a "postback" from the sender id "666999"
      | title | payload |
      | Leave | 1       |
    Then the following messages are sent in response
      | recipient_id | message      | text                                                                                                          |
      | 666999       | text_message | Matching you with a person who's Remain...                                                                    |
      | 666999       | text_message | You're now chatting, say hello :)\n\nRemember to be nice and type 'end' at any point to stop the conversation |
      | 777000       | text_message | You're now chatting, say hello :)\n\nRemember to be nice and type 'end' at any point to stop the conversation |
    And the sender id "666999" now has the status "SENDER_IN_CONVERSATION"
    And the sender id "777000" now has the status "SENDER_IN_CONVERSATION"
  # TODO: check queue status

  Scenario: choose value, empty queue
    Given registered senders in our database
      | id     | status              | session |
      | 666999 | SENDER_CHOOSE_VALUE | 1       |
    When we recieve a "postback" from the sender id "666999"
      | title | payload |
      | Leave | 1       |
    Then the following messages are sent in response
      | recipient_id | message        | text                                                                                                           |
      | 666999       | text_message   | Matching you with a person who's Remain...                                                                     |
      | 666999       | text_message   | Don't like queuing? Share Listenr with your friends to get more people in conversation me. m.me/listenrconnect |
      | 666999       | added_to_queue |                                                                                                                |
    And the sender id "666999" now has the status "SENDER_IN_QUEUE"

  Scenario: choose value, non matching senders in queue
    Given registered senders in our database
      | id     | status              | session |
      | 666999 | SENDER_CHOOSE_VALUE | 1       |
      | 777000 | SENDER_IN_QUEUE     | 1       |
    Given the senders in the queue
      | sender | value |
      | 777000 | Leave |
    When we recieve a "postback" from the sender id "666999"
      | title | payload |
      | Leave | 1       |
    Then the following messages are sent in response
      | recipient_id | message         | text                                                                                                           |
      | 666999       | text_message    | Matching you with a person who's Remain...                                                                     |
      | 666999       | text_message    | Don't like queuing? Share Listenr with your friends to get more people in conversation me. m.me/listenrconnect |
      | 666999       | added_to_queue2 |                                                                                                                |
    And the sender id "666999" now has the status "SENDER_IN_QUEUE"