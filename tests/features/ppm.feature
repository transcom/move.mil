@javascript @api
Feature: PPM Estimator
  In order to get an estimate for how much the government will pay for the move
  As an anonymous user
  I need to be able to use the PPM estimator tool

  Scenario: Visit the ppm tool page
    Given I visit "/resources/ppm-estimator"
    Then I should see "What is your rank?"
    And I should see "Calculate"

  Scenario: Move from Fairfax to Beverly Hills
    Given there is an entitlement "rank-2"
    And there is PPM data
    And I set the configuration item "parser.settings" with key "distancesurl" to "http://localhost/test/ppm/distances"
    And I press "Tools & Resources"
    And I click "PPM Estimator"
    And I wait 12 seconds until I see text "What is your rank?"
    And I select "rank-2" from "entitlement"
    And I select the radio button "Yes, I have dependents" with the id "with-dependent"
    And I fill in the following:
      | origin | 22030 |
      | destination | 90210 |
      | moveDate | 01/01/2019 |
    And the focus is in field "houseHold"
    And I fill in the following:
      | houseHold | 14500 |
      | proGear | 2001 |
      | dependent | 501 |
    And I wait 10 seconds until I see "dependent" field contains "501"
    When I press "Calculate"
    And I wait 10 seconds until I get a response with text "Your PPM Incentive Estimate"
    Then I should see "From: Fairfax, VA 22030 to Beverly Hills, CA 90210"
    And I should see "Rank 2 with dependents."
    And I should see "14500 lbs"
    And I should see "2000 lbs"
    And I should see "500 lbs"
    And I should see "$14700-$16700"
    And I should see "$8820-$10020 (60%)"

  Scenario: Fields need to be fill in
    Given I visit "/resources/ppm-estimator"
    When I press "Calculate"
    Then I should see "Please select an item in the list."
    And I should see "Please fill out this field."