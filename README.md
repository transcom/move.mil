# Composer template for Drupal projects

[![CircleCI](https://circleci.com/gh/Bixal/move.mil.svg?style=svg)](https://circleci.com/gh/Bixal/move.mil)

This repository contains the source code for the [Move.mil](https://www.move.mil) website, the official portal to the Defense Personal Property System (DPS). DPS is an online system managed by the U.S. [Department of Defense](https://www.defense.gov) (DoD) [Transportation Command](http://www.ustranscom.mil) (USTRANSCOM) and is used by service members and their families to manage household goods moves.

This version of Move.mil was built in support of USTRANSCOM's mission. This website's source code is made available to the open source community with the hope that community contributions will improve functionality, add features, and mature this work.

## Contributing

For details on setting up your development environment and contributing to this project, see [CONTRIBUTING.md][contributing].

## License

As part of the Defense Digital Service's goal of bringing technology industry practices to the U.S. Department of Defense, we welcome contributions to this repository from the open source community. If you are interested in contributing to this project, please review [CONTRIBUTING.md][contributing] and [LICENSE.md][license]. Those files describe how to contribute to this work. A list of contributors to this project is maintained in [CONTRIBUTORS.md][contributors].

Works created by U.S. Federal employees as part of their jobs typically are not eligible for copyright in the United States. In places where the contributions of U.S. Federal employees are not eligible for copyright, this work is in the public domain. In places where it is eligible for copyright, such as some foreign jurisdictions, this work is licensed as described in [LICENSE.md][license].

## Update tools data

### Uploading discounts files

Each quarter there is a new Traffic Distribution List (TDL) file(s) from US TRANSCOM containing the new discounts that need to be applied by the PPM tool.

1. They will send the file(s) through the AMRDEC Safe Access File Exchange Tool.
1. The files will be available at [AMRDEC](http://safe.amrdec.army.mil). Follow the instructions from the e-mail you will receive.
1. There are 2 types of TDL files. The Peak (PK) discounts and the Non-Peak (NP). PK contains the discounts that are effective in the peak season. NP are discounts after the peak seasons, these are subject to change after the current quarter ends. Both are discounts with a start and end date, and they do not overlap. Only one discount should exist for a moving date.
1. Go to [Move.mil](https://move.mil/user/login) and log in with your admin user.
1. Navigate to the Parser Admin.
1. Expand Discounts tab.
1. Choose the discount file.
1. Set the starting effective date (those will be moving dates).
1. If you don't need the previous data to remain there, check the `Clear table` checkbox. Otherwise leave it uncheck.
1. Click `Save configuration`.
1. Repeat the previous steps for the next file if available.

### Uploading locations files

Each 2 weeks there is a new XML file with location updates from US TRANSCOM containing corrections or addition of addresses, phones, or e-mail addresses.

1. They will upload the file on Pivotal.
1. Go to [Move.mil](https://move.mil/user/login) and log in with your admin user.
1. Navigate to the Locations Admin.
1. Choose the XML file and wait until it is uploaded.
1. Click on 'Update Locations' and wait until the process has finished. Do not execute this process more than once at a time.
1. All the Drupal locations that have a `CNSL_ORG_ID` will be updated: name, address, e-mails, geolocation, and phones.
1. This tool will add new locations, in case a new location is missing in Drupal.
1. This tool will remove offices that exist on the exception list.



[contributing]: https://github.com/Bixal/move.mil/blob/master/CONTRIBUTING.md
[contributors]: https://github.com/Bixal/move.mil/blob/master/CONTRIBUTORS.md
[license]: https://github.com/Bixal/move.mil/blob/master/LICENSE.md