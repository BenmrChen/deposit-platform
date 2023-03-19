# metasens

## Prerequisite

* gcloud command line
* docker for mac
* bash version >= 4
* mutagen
* mutagen-compose

## Pre setup steps

```shell
brew install bash
brew install mutagen-io/mutagen/mutagen-beta
brew install mutagen-io/mutagen/mutagen-compose-beta
brew cask install google-cloud-sdk
gcloud auth login
gcloud auth configure-docker
gcloud auth configure-docker asia-east1-docker.pkg.dev
```

> And copy builds/configs/user_defined.sh.tpl to builds/configs/user_defined.sh, and modify string `chang_me_please` to your github personal access token, And you're all done.

## How to develop

### Some prepare task

1. Goto : System Preferences > Security & Privacy > Full Disk Access, and add the terminal app you use to give access to.
2. execute some command.

```shell
./builds/helpers/nfs-server-setup
```

### Start / stop docker stack

```shell
./builds/run-tasks.sh run # start
./builds/run-tasks.sh down # stop
```

### Enter application docker container

```shell
./builds/helpers/exec bash
```

### How to test

```shell
./builds/helpers/vendor-cmd phpcs # for phpcs
./builds/helpers/vendor-cmd phplint # for php lint
./builds/helpers/vendor-cmd phpunit # for php unit
./builds/helpers/spelling-linter # for spelling check
./builds/run-tasks.sh code-quality # run pull request ci job on jenkins (almost)
```

### How to add new excluded word for spelling check

* add txt file named `cspell-extra-words.txt` in project root folder
* add new excluded word

### Laravel composer package local development

```shell
./builds/helpers/user-local-vendor # swap composer package source from git to local path temporary.
./builds/helpers/reset-vendor # reset your composer vendor
```

### swagger API docs

* url : <http://localhost:8080>
* generate & re-generate : `php artisan make:api-docs --scheme=http`
