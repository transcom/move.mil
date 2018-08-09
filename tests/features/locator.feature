@javascript @api
Feature: Locator Maps
  In order to contact a PPPO
  As an anonymous user
  I need to be able to use the Locator Maps

  Scenario: Visit the Locator Maps page
    Given I visit "/resources/locator-maps"
    Then I should see "Search"

  Scenario: Form can be submitted
    Given I visit "/resources/locator-maps"
    When I press "Search"
    Then I should see "Please fill out this field."