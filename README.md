# Composer template for Drupal projects

[![CircleCI](https://circleci.com/gh/Bixal/move.mil.svg?style=svg)](https://circleci.com/gh/Bixal/move.mil)

This repository contains the source code for the [Move.mil](https://www.move.mil) website, the official portal to the Defense Personal Property System (DPS). DPS is an online system managed by the U.S. [Department of Defense](https://www.defense.gov) (DoD) [Transportation Command](http://www.ustranscom.mil) (USTRANSCOM) and is used by service members and their families to manage household goods moves.

This version of Move.mil was built in support of USTRANSCOM's mission. This website's source code is made available to the open source community with the hope that community contributions will improve functionality, add features, and mature this work.

## Contributing

For details on setting up your development environment and contributing to this project, see [CONTRIBUTING.md][contributing].

## License

As part of the Defense Digital Service's goal of bringing technology industry practices to the U.S. Department of Defense, we welcome contributions to this repository from the open source community. If you are interested in contributing to this project, please review [CONTRIBUTING.md][contributing] and [LICENSE.md][license]. Those files describe how to contribute to this work. A list of contributors to this project is maintained in [CONTRIBUTORS.md][contributors].

Works created by U.S. Federal employees as part of their jobs typically are not eligible for copyright in the United States. In places where the contributions of U.S. Federal employees are not eligible for copyright, this work is in the public domain. In places where it is eligible for copyright, such as some foreign jurisdictions, this work is licensed as described in [LICENSE.md][license].

## Uploading discounts files

To make a new discount file ready for uploading follow the following steps:

1. Run the encryptscript.sc 'COMMAND: ./encryptscript.sc'
2. Give the absolute path of the file.
3. Give the effective date of the file.
4. Add the encrypted file 'discounts-{effective_date.csv.enc}' to staging in 'lib/data/''.
5. Push your changes.
6. Make a pull request     




[contributing]: https://github.com/Bixal/move.mil/blob/master/CONTRIBUTING.md
[contributors]: https://github.com/Bixal/move.mil/blob/master/CONTRIBUTORS.md
[license]: https://github.com/Bixal/move.mil/blob/master/LICENSE.md