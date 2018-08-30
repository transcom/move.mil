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
    And I visit "/resources/ppm-estimator"
    And I wait 12 seconds until I see text "What is your rank?"
    And I select "rank-2" from "entitlement"
    And I select the radio button "Yes, I have dependents" with the id "with-dependent"
    And I fill in "origin" with "22030"
    And I fill in "destination" with "90210"
    And I fill in "moveDate" with "01/01/2019"
    And I fill in "houseHold" with "14500"
    And I fill in "proGear" with "2000"
    And I fill in "dependent" with "500"
    When I press "Calculate"
    And I wait 60 seconds until I get a response with text "locations"

  Scenario: Fields need to be fill in
    Given I visit "/resources/ppm-estimator"
    When I press "Calculate"
    Then I should see "Please select an item in the list."
    And I should see "Please fill out this field."
