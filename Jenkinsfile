#!groovy
@Library('shared-libs') _

def ciRobotGcpProjectId = 'sevensenses-auto-bot'
def ciRobotCredentialsId = 'ci-robot'
def gitHubTokenId = 'ci-robot-github-token'
def impersonateAccount = 'jenkins-ci-robot@sevensenses-nm-beta.iam.gserviceaccount.com'
def nodeType = 'ci-slave'
def appName = 'metasens-users'
def garHost = 'asia-east1-docker.pkg.dev'
def releaseGarUrl = 'asia-east1-docker.pkg.dev/sevensenses-nm-beta/metasens'
def releaseManualVer = 'develop'
def deployJob = '../Deployment-beta'
def skipThisBuild = false

pipeline {
    agent {
        label nodeType
    }
    options {
        ansiColor('xterm')
        timeout(time: 1, unit: 'HOURS')
        quietPeriod(60)
        disableResume()
        throttleJobProperty(categories: ['users'], throttleEnabled: true, throttleOption: 'category')
        buildDiscarder(logRotator(numToKeepStr: '10'))
    }
    stages {
        stage('Prepare') {
            steps {
                script {
                    skipThisBuild = shouldSkipThisBuild()
                }
            }
        }
        stage('Testing') {
            when {
                expression {
                   return skipThisBuild == false
                }
            }
            steps {
                runWithUtilsDocker {
                    withGcpCredentials(ciRobotGcpProjectId, ciRobotCredentialsId) {
                        withGitHubToken(gitHubTokenId) {
                            garDockerLogin(garHost)
                            sh('./builds/run-tasks.sh code-quality')
                        }
                    }
                }
            }
        }
        stage('Release') {
            when {
                allOf {
                    branch 'master'
                    not { changeRequest() }
                    expression { return skipThisBuild == false }
                }
            }
            steps {
                runWithUtilsDocker {
                    withGcpCredentials(ciRobotGcpProjectId, ciRobotCredentialsId) {
                        withGitHubToken(gitHubTokenId) {
                            script {
                                env.APP_NAME = appName
                                env.RELEASE_GCR_REPO = releaseGarUrl
                                env.RELEASE_PUSH_GCR_OAUTH_TOKEN = getGcpOauthToken(impersonateAccount)
                            }
                            garDockerLogin(garHost)
                            sh('./builds/run-tasks.sh release ' + releaseManualVer)
                        }
                    }
                }
                build job: deployJob, parameters: [string(name: 'DEPLOY_VERSION', value: releaseManualVer)], wait: false
                setBuildMessage("release version: ${releaseManualVer}")
            }
        }
    }
    post {
        always {
            archiveReport('php')
        }
        cleanup {
            deleteDir()
        }
    }
}
