pipeline {
    agent none

    stages {
        stage('Integration UI Test') {
            parallel {
                stage('Deploy') {
                    agent any
                    steps {
                        sh './jenkins/scripts/deploy.sh'
                        input message: 'Finished using the web site? (Click "Proceed" to continue)'
                        sh './jenkins/scripts/kill.sh'
                    }
                }
                stage('Headless Browser Test') {
                    agent {
                        docker {
                            image 'maven'
                            args '--platform linux/amd64 -u root --network jenkins-php-selenium-test-1_jenkins-net --entrypoint=""'
                        }
                    }
                    steps {
                        sh 'mvn -B -DskipTests clean package'
                        sh 'mvn test'
                    }
                    post {
                        always {
                            junit 'target/surefire-reports/*.xml'
                        }
                    }
                }
            }
        }
        stage('SonarQube Analysis') {
            agent any
                environment {
        SONARQUBE_TOKEN = 'sqp_621aaa94319a27fdefe5be5dc73f9e7be5e03af1'
    }
            steps {
                // script {
                //     // SONARQUBE_SCANNER_HOME = tool name: 'SonarQube Scanner', type: 'hudson.plugins.sonar.SonarRunnerInstallation'
                // }
                withSonarQubeEnv('SonarQube Scanner') {
                    sh '''
                    SonarQube Scanner/bin/sonar-scanner \
                    -Dsonar.projectKey=OWASP \
                    -Dsonar.sources=./src \
                    -Dsonar.host.url=http://sonarqube:9000 \
                    -Dsonar.login=${SONARQUBE_TOKEN}
                    '''
                }
            }
        }
    }
}
