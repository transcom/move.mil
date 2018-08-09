@javascript @api
Feature: PPM Estimator
  In order to get an estimate for how much the government will pay for the move
  As an anonymous user
  I need to be able to use the PPM estimator tool

  Scenario: Visit the ppm tool page
    Given I visit "/resources/ppm-estimator"
    Then I should see "What is your rank?"
    And I should see "Calculate"

  Scenario: Form can be submitted
    Given I visit "/resources/ppm-estimator"
    When I press "Calculate"
    Then I should see "Please select an item in the list."