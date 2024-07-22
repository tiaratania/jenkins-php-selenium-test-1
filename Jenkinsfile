pipeline {
    agent none
    environment {
        SONARQUBE_SCANNER_HOME = tool name: 'SonarQube Scanner'
        SONARQUBE_TOKEN = 'sqp_621aaa94319a27fdefe5be5dc73f9e7be5e03af1'

    }
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
        // stage('SonarQube Analysis') {
        //     agent any
        //     steps {
        //         withSonarQubeEnv('SonarQube') {
        //                 sh '''
        //                 SonarQube Scanner/bin/sonar-scanner \
        //                     -Dsonar.projectKey=Test \
        //                     -Dsonar.sources=. \
        //                     -Dsonar.host.url=http://jenkins-php-selenium-test-1-sonarqube-1:9000 \
        //                     -Dsonar.login=sqp_f6e86c149a7db4794734c068e089531d110a1bb2
        //                 '''
                    
        //         }
        //     }
        // }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube') {
                    sh '''
                    ${SONARQUBE_SCANNER_HOME}/bin/sonar-scanner \
                    -Dsonar.projectKey=OWASP \
                    -Dsonar.sources=./src \
                    -Dsonar.host.url=http://jenkins-php-selenium-test-1-sonarqube-1:9000 \
                    -Dsonar.login=${SONARQUBE_TOKEN}
                    '''
                }
            }
        }
        
    }
}