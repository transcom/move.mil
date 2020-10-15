# 2020-10-13 - Maintaining pinned Alpine package versions

Last Updated: 13 Oct 2020

Author: Mike Kania

Goal: Maintaining pinned Alpine packages versions

Context: In order to have reproducible Docker image builds, we explicitly
specify alpine package version we wish to install. Over time those pinned
version defined in the [Dockerfile](https://github.com/transcom/move.mil/blob/master/docker/Dockerfile)
will be superseded and will need to be updated. This document describes how to update those packages.

## Finding and Updating the version

If the existing version of a package is no longer available in Alpine.
The CircleCI build-image job will fail with the following error

```
ERROR: unsatisfiable constraints:
  mysql-client-10.4.15-r0:
```

The first step is to figure out which package needs upgrading. In the example
above `mysql-client` is in need of upgrading.

The next step is looking up the latest version in the alpine package repository.
We are currently using Alpine 3.12, so you can search for the latest version of
the package by going to the [alpine package repo](https://pkgs.alpinelinux.org/packages?branch=v3.12&arch=x86_64),
search for the package your getting an error.

In this example, `MYSQL_CLIENT_VERSION` in the [Dockerfile](https://github.com/transcom/move.mil/blob/master/docker/Dockerfile)
needs to be updated from `10.4.13-r0` to `10.4.15-r0`.

