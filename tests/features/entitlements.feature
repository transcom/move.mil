@javascript @api
Feature: Entitlements
  In order to see my moving allowance
  As an anonymous user
  I need to be able to use the entitlements tool

  Scenario: Visit the entitlements page
    Given I visit "/entitlements"
    Then I should see "What is your rank?"