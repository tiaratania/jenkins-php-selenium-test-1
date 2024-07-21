pipeline {
    agent none
    stages {
        stage('Integration UI Test') {
            parallel {
                stage('Deploy') {
                    agent any
                    steps {
                        scripts{

                            //ensure proper cleanup of existing containers
                            sh 'docker rm -f my-apache-php-app || true'

                        }
                        

                        sh './jenkins/scripts/deploy.sh'
                        input message: 'Finished using the web site? (Click "Proceed" to continue)'
                        sh './jenkins/scripts/kill.sh'
                    }
                }

            }
        }
    }
}