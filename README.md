# Versionize Arch
A composer plugin provides new extra repository type `va`, working same as `artifact` but fetch version from filename, and a command for build all required packages as versionize archive.

## Installation

### Global scope installation

```shell
composer global require revonia/versionize-arch
```

### Project scope installation

```shell
composer require revonia/versionize-arch
```

## Usage

### Use `va` repository
In order to use `va` repository, add below to composer.json file (or merge into `extra` field).

Notice that add `va` repository to composer `repositories` field won't work.

```json
{
    "extra": {
        "extra-repositories": [
            {
                "type": "va",
                "url": "va-repo/"
            }
        ]
    }
}
```

When using `composer require`, composer will read versionize archives from `url` field and install if needed.

### Build versionize archive
It is a waste of time to making archives and renaming, command `build-va-repo` will keep you happy.

Make sure you are in a composer project directory, then run `composer build-va-repo`, it will automatic create an directory named `va-repo`, all your installed composer packages will be archiving into it, and named like `package-name$$version.zip`. More options please see `composer help build-va-repo`.

By using `va-repo` directory as `va` repository, you can install composer packages without internet connection, It was useful when your application running on local network but need some update, you don't need any extra server for holding this repository.
