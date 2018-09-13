@javascript @api
Feature: Entitlements
  In order to see my moving allowance
  As an anonymous user
  I need to be able to use the entitlements tool

  Scenario: Visit the entitlements page
    Given I visit "/entitlements"
    Then I should see "What is your rank?"

  Scenario: Get CONUS + dependents table
    Given there is an entitlement "rank-1"
    And I visit "/entitlements"
    And I wait 5 seconds until I see text "What is your rank?"
    And I select "rank-1" from "entitlement"
    And I select the radio button "Yes, I have dependents" with the id "with-dependent"
    And I select the radio button "CONUS" with the id "conus"
    Then I should see "Military pay grade: Rank 1"
    And I should see "Dependency Status: Yes, I have dependents (spouse/children) that are authorized to move"
    And I should see "Move type: CONUS"
    And I should see "8000 lbs."
    And I should see "+ 2000 lbs."
    And I should see "+ 500 lbs."

  Scenario: Get OCONUS warning
    Given there is an entitlement "rank-2"
    And I visit "/entitlements"
    And I wait 5 seconds until I see text "What is your rank?"
    And I select "rank-2" from "entitlement"
    And I select the radio button "No, I do not have dependents" with the id "not-dependent"
    And I select the radio button "OCONUS" with the id "oconus"
    Then I should see "Military pay grade: Rank 2"
    And I should see the warning message containing "Important: Certain overseas (OCONUS) locations have weight restrictions"
