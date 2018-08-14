@javascript @api
Feature: Weight Estimator
  In order to know how much my personal belongings weigh
  As an anonymous user
  I need to be able to use the weight estimator tool

  Scenario: Visit the weight estimator page
    Given I visit "/resources/weight-estimator"
    Then I should see "TOTAL Estimate:"
