Laravel REST-tester [![Build Status](https://img.shields.io/travis/fuzz-productions/rest-tester/master.svg?style=flat)](https://travis-ci.org/fuzz-productions/rest-tester)
===========================================================================================================================================================================
A suite of helper methods to test REST APIs.

## Setup
1. Require-dev the composer package
1. Extend your base API test case from `Fuzz\RestTests\BaseRestTestCase`
1. Adjust `setUp` and `tearDown` as needed. The tests for this package are a good example of how to use it.
   1. This package extends `orchestra/testbench` so all available functionality is present in `rest-tester` 

## Helper Traits
### Base
1. `Fuzz\RestTests\BaseRestTestCase` provides some helper methods to configure tests for a RESTful API

### Resources
1. `Fuzz\RestTests\Resources\RestfulResource` provides helper methods to test endpoints for restful resources
1. Add `Fuzz\RestTests\Resources\TestResourceX` (where X is the resource action) traits depending on which actions need to be tested

### OAuth
1. `Fuzz\RestTests\AuthTraits\OAuthTrait` provides methods to authenticate, refresh tokens, retrieve tokens from request objects, create users/clients with scopes, etc.

## Who tests the testers?
Run `phpunit` after `composer install`.
